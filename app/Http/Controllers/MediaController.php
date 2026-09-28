<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manage-content');

        $query = MediaAsset::query();
        if ($request->filled('q')) {
            $search = '%'.mb_substr(trim((string) $request->query('q')), 0, 200).'%';
            $query->where(fn ($q) => $q->where('original_name', 'like', $search)->orWhere('alt_text', 'like', $search));
        }
        if (in_array($request->query('type'), ['image/png', 'image/webp'], true)) {
            $query->where('mime_type', $request->query('type'));
        }

        return view('cms.media', ['media' => $query->latest()->paginate(12)->withQueryString()]);
    }

    public function store(Request $request)
    {
        $this->authorize('manage-content');
        $data = $request->validate(['image' => 'required|file|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:max_width=6000,max_height=6000', 'alt_text' => 'required|string|max:255', 'rights' => 'required|string|max:3000']);
        $upload = $request->file('image');
        $dimensions = getimagesize($upload->getRealPath());
        if ($dimensions[0] * $dimensions[1] > 12000000) {
            throw \Illuminate\Validation\ValidationException::withMessages(['image' => 'Use an image of at most 12 megapixels.']);
        }
        $image = @imagecreatefromstring(file_get_contents($upload->getRealPath()));
        abort_unless($image, 422, 'The image could not be decoded.');
        $scale = min(1, 1600 / max(imagesx($image), imagesy($image)));
        if ($scale < 1) {
            $resized = imagescale($image, max(1, (int) round(imagesx($image) * $scale)), max(1, (int) round(imagesy($image) * $scale)), IMG_BICUBIC);
            abort_unless($resized, 422, 'The image could not be resized.');
            imagedestroy($image);
            $image = $resized;
        }
        imagesavealpha($image, true);
        $webp = function_exists('imagewebp');
        ob_start();
        $encoded = $webp ? imagewebp($image, null, 82) : imagepng($image);
        $bytes = ob_get_clean();
        imagedestroy($image);
        abort_unless($encoded && strlen($bytes), 422, 'The image could not be encoded.');
        $mime = $webp ? 'image/webp' : 'image/png';
        $path = 'media/'.Str::uuid().($webp ? '.webp' : '.png');
        abort_unless(Storage::disk('local')->put($path, $bytes), 500, 'Image could not be stored.');
        try {
            MediaAsset::create(['user_id' => $request->user()->id, 'path' => $path, 'original_name' => mb_substr($upload->getClientOriginalName(), 0, 255), 'mime_type' => $mime, 'size' => strlen($bytes), 'alt_text' => $data['alt_text'], 'rights' => $data['rights']]);
        } catch (\Throwable $error) {
            Storage::disk('local')->delete($path);
            throw $error;
        }

        return back()->with('status', 'Image uploaded.');
    }

    public function update(Request $request, MediaAsset $media)
    {
        $this->authorize('manage-content');
        $media->update($request->validate(['alt_text' => 'required|string|max:255', 'rights' => 'required|string|max:3000']));

        return back()->with('status', 'Image metadata saved.');
    }

    public function destroy(MediaAsset $media)
    {
        $this->authorize('manage-content');
        if (Article::withTrashed()->where('media_asset_id', $media->id)->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['image' => 'This image is referenced by an article, including articles in trash.']);
        }
        $path = $media->path;
        $media->delete();
        Storage::disk('local')->delete($path);

        return back()->with('status', 'Image deleted.');
    }

    public function show(Request $request, MediaAsset $media)
    {
        $public = Article::published()->where('media_asset_id', $media->id)->exists();
        abort_unless($public || $request->user()?->can('studio'), 404);
        abort_unless(Storage::disk('local')->exists($media->path), 404);

        return response()->file(Storage::disk('local')->path($media->path), ['Content-Type' => $media->mime_type === 'image/webp' ? 'image/webp' : 'image/png', 'X-Content-Type-Options' => 'nosniff', 'Cache-Control' => $public ? 'public, max-age=300' : 'private, no-store']);
    }
}
