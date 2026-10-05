<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\User;
use App\Services\ResponsiveImages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResponsiveImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_generates_smaller_variants_preserves_original_and_removes_variants_on_delete(): void
    {
        Storage::fake('uploads');
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->postJson('/admin/media', ['image' => UploadedFile::fake()->image('large.png', 1400, 800), 'alt_text' => 'Diagram', 'rights' => 'Owned'])->assertCreated();
        $media = MediaAsset::firstOrFail();
        $images = app(ResponsiveImages::class);
        $hash = hash('sha256', Storage::disk('uploads')->get($media->path));
        foreach ([320, 640, 960] as $width) {
            $path = $images->path($media, $width);
            Storage::disk('uploads')->assertExists($path);
            $this->assertSame($width, getimagesize(Storage::disk('uploads')->path($path))[0]);
        }
        $this->assertSame(0, $images->generate($media));
        $this->assertSame($hash, hash('sha256', Storage::disk('uploads')->get($media->path)));
        $this->assertStringContainsString('640w', $media->srcset);
        $markup = $images->decorate('<img src="/uploads/'.$media->path.'" alt="Diagram">');
        $this->assertStringContainsString('srcset=', $markup);
        $this->get('/media/'.$media->id.'?w=640')->assertOk()->assertHeader('Content-Type', 'image/webp');
        $this->get('/media/'.$media->id.'?w=999')->assertNotFound();
        $this->get('/media/'.$media->id.'?w[]=640')->assertNotFound();
        auth()->logout();
        $this->get('/media/'.$media->id.'?w=640')->assertNotFound();
        $this->actingAs(User::where('role', 'admin')->first());
        $this->delete('/admin/media/'.$media->id)->assertSessionHasNoErrors();
        Storage::disk('uploads')->assertMissing($media->path);
        foreach ([320, 640, 960] as $width) {
            Storage::disk('uploads')->assertMissing($images->path($media, $width));
        }
    }
}
