<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\FaqEntry;
use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['financershub.design_preview' => false]);
        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_faq_publication_and_reordering_persist(): void
    {
        $this->post('/admin/content/faq', ['question' => 'Draft question', 'answer' => 'Private answer', 'position' => 3])->assertSessionHasNoErrors();
        $faq = FaqEntry::firstOrFail();
        $this->get('/faq')->assertOk()->assertDontSee('Private answer');
        $this->put('/admin/content/faq/'.$faq->id, ['question' => 'Public question', 'answer' => 'Verified answer', 'position' => 1, 'published' => 1, 'group' => 'newsletter-and-contact'])->assertSessionHasNoErrors();
        $this->get('/faq')->assertOk()->assertSee('Verified answer');
        $this->assertSame(1, $faq->fresh()->position);
        $this->assertSame('newsletter-and-contact', $faq->fresh()->group);
    }

    public function test_images_are_validated_private_until_used_and_metadata_persists(): void
    {
        Storage::fake('local');
        $this->post('/admin/media', ['image' => UploadedFile::fake()->create('bad.svg', 1, 'image/svg+xml'), 'alt_text' => 'Alt', 'rights' => 'Own work'])->assertSessionHasErrors('image');
        $this->post('/admin/media', ['image' => UploadedFile::fake()->image('photo.jpg', 200, 120), 'alt_text' => 'A notebook', 'rights' => 'Own work'])->assertSessionHasNoErrors();
        $image = MediaAsset::firstOrFail();
        Storage::disk('local')->assertExists($image->path);
        $this->get('/media/'.$image->id)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->put('/admin/media/'.$image->id, ['alt_text' => 'A blue notebook', 'rights' => 'Original photograph'])->assertSessionHasNoErrors();
        $this->assertSame('A blue notebook', $image->fresh()->alt_text);
        auth()->logout();
        $this->get('/media/'.$image->id)->assertNotFound();
    }

    public function test_referenced_taxonomy_cannot_be_deleted_or_have_its_url_changed(): void
    {
        $category = Category::create(['name' => 'Saving', 'slug' => 'saving']);
        $author = AuthorProfile::create(['name' => 'Writer', 'slug' => 'writer']);
        Article::create(['user_id' => auth()->id(), 'author_profile_id' => $author->id, 'category_id' => $category->id, 'title' => 'Draft', 'slug' => 'draft', 'excerpt' => 'Example', 'body' => '<p>Body</p>']);
        $this->delete('/admin/content/categories/'.$category->id)->assertSessionHasErrors('content');
        $this->put('/admin/content/categories/'.$category->id, ['name' => 'Saving', 'slug' => 'new-saving'])->assertSessionHasErrors('slug');
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'slug' => 'saving']);
    }

    public function test_large_upload_is_resized_with_matching_content_type(): void
    {
        Storage::fake('local');
        $this->post('/admin/media', ['image' => UploadedFile::fake()->image('large.jpg', 2400, 1200), 'alt_text' => 'Test image', 'rights' => 'Test fixture'])->assertSessionHasNoErrors();
        $image = MediaAsset::firstOrFail();
        $size = getimagesize(Storage::disk('local')->path($image->path));
        $this->assertSame(1600, $size[0]);
        $this->assertSame(800, $size[1]);
        $this->assertSame(function_exists('imagewebp') ? 'image/webp' : 'image/png', $size['mime']);
        $this->get('/media/'.$image->id)->assertOk()->assertHeader('Content-Type', $size['mime']);
    }

    public function test_settings_are_persistent_and_authors_cannot_manage_supporting_content(): void
    {
        $this->post('/admin/settings', ['newsletter_title' => 'The brief', 'newsletter_description' => 'Verified description', 'footer_copy' => 'Independent education'])->assertSessionHasNoErrors();
        $this->get('/')->assertOk()->assertSee('Independent education');
        $this->actingAs(User::factory()->create(['role' => 'author']));
        $this->post('/admin/content/tags', ['name' => 'Bad', 'slug' => 'bad'])->assertForbidden();
        $this->post('/admin/settings', [])->assertForbidden();
        $this->post('/admin/media', [])->assertForbidden();
    }
}
