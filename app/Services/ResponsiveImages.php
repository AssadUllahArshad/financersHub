<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Storage;

class ResponsiveImages
{
    public const WIDTHS = [320, 640, 960];

    public function path(MediaAsset $media, int $width): string
    {
        return dirname($media->path).'/responsive/'.pathinfo($media->path, PATHINFO_FILENAME).'-'.$width.'.webp';
    }

    public function generate(MediaAsset $media): int
    {
        $disk = Storage::disk('uploads');
        if (! function_exists('imagewebp') || ! $disk->exists($media->path)) {
            return 0;
        }
        $source = @imagecreatefromstring($disk->get($media->path));
        if (! $source) {
            return 0;
        }
        $count = 0;
        try {
            foreach (self::WIDTHS as $width) {
                $path = $this->path($media, $width);
                if ($width >= imagesx($source) || $disk->exists($path)) {
                    continue;
                }
                $resized = imagescale($source, $width, max(1, (int) round(imagesy($source) * $width / imagesx($source))), IMG_BICUBIC);
                if (! $resized) {
                    continue;
                }
                imagesavealpha($resized, true);
                ob_start();
                $encoded = imagewebp($resized, null, 80);
                $bytes = ob_get_clean();
                imagedestroy($resized);
                if ($encoded && $bytes && $disk->put($path, $bytes)) {
                    $count++;
                }
            }
        } finally {
            imagedestroy($source);
        }

        return $count;
    }

    public function srcset(MediaAsset $media): string
    {
        if (! $media->width) {
            return '';
        }
        $sources = [];
        foreach (self::WIDTHS as $width) {
            if ($width < $media->width && Storage::disk('uploads')->exists($this->path($media, $width))) {
                $sources[] = route('media.show', ['media' => $media->id, 'w' => $width]).' '.$width.'w';
            }
        }
        $sources[] = route('media.show', $media->id).' '.$media->width.'w';

        return implode(', ', $sources);
    }

    public function delete(MediaAsset $media): void
    {
        foreach (self::WIDTHS as $width) {
            Storage::disk('uploads')->delete($this->path($media, $width));
        }
    }

    public function decorate(string $html): string
    {
        preg_match_all('~<img\b[^>]*\bsrc="(/uploads/[^"?]+|/media/[0-9]+)"[^>]*>~', $html, $matches, PREG_SET_ORDER);
        if (! $matches) {
            return $html;
        }
        $paths = [];
        $ids = [];
        foreach ($matches as $match) {
            if (str_starts_with($match[1], '/uploads/')) {
                $paths[] = substr($match[1], 9);
            } else {
                $ids[] = (int) substr($match[1], 7);
            }
        }
        $media = MediaAsset::whereIn('path', $paths)->orWhereIn('id', $ids)->get();
        foreach ($matches as $match) {
            $asset = str_starts_with($match[1], '/uploads/') ? $media->firstWhere('path', substr($match[1], 9)) : $media->firstWhere('id', (int) substr($match[1], 7));
            if (! $asset || ! $asset->width) {
                continue;
            }
            // Prepared HTML has already been sanitized. Only generated attributes are added.
            $attributes = ' srcset="'.htmlspecialchars($this->srcset($asset), ENT_QUOTES, 'UTF-8').'" sizes="(max-width: 800px) calc(100vw - 32px), 720px"';
            $html = str_replace($match[0], substr($match[0], 0, -1).$attributes.'>', $html);
        }

        return $html;
    }
}
