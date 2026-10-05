<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DesignIntegrationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(\App\Models\User::factory()->create(['role' => 'admin']));
    }

    public function test_active_pages_render_with_resolvable_assets_and_legacy_redirects(): void
    {
        $this->seed(\Database\Seeders\TaxonomySeeder::class);
        \App\Models\AuthorProfile::create(['name' => 'Editorial Team', 'slug' => 'editorial-team', 'schema_type' => 'Organization', 'is_demo' => false]);
        $paths = ['/', '/about', '/contact', '/faq', '/privacy', '/terms', '/disclaimer', '/editorial-policy', '/authors', '/authors/editorial-team', '/search', '/tools/compound-interest-calculator', '/categories/saving', '/admin', '/admin/articles', '/admin/editor', '/admin/media', '/admin/categories', '/admin/authors', '/admin/tags', '/admin/faq', '/admin/contacts', '/admin/settings', '/admin/seo', '/admin/profile', '/admin/comments', '/admin/newsletter', '/admin/advertisements'];
        foreach ($paths as $path) {
            $response = $this->get($path)->assertOk()->assertSee('FinancersHub');
            $dom = new \DOMDocument;
            @$dom->loadHTML($response->getContent());
            foreach ((new \DOMXPath($dom))->query('//*[@href or @src]') as $element) {
                $url = $element->getAttribute('href') ?: $element->getAttribute('src');
                $local = parse_url($url, PHP_URL_PATH);
                if ($local && (str_starts_with($local, '/build/') || str_starts_with($local, '/assets/'))) {
                    $this->assertFileExists(public_path(ltrim($local, '/')), $path.' references '.$local);
                }
            }
        }
        $this->get('/about.html')->assertRedirect('/about');
        $this->get('/categories/saving.html')->assertRedirect('/categories/saving');
        $this->get('/articles/never-published')->assertNotFound();
    }

    public function test_missing_page_uses_branded_error_with_correct_status(): void
    {
        config(['app.debug' => false]);
        $this->get('/not-a-real-page')->assertNotFound()->assertSee('FinancersHub');
        Route::get('/test-server-error', fn () => abort(500));
        $this->get('/test-server-error')->assertStatus(500)->assertSee('FinancersHub');
    }

    public function test_editor_uses_environment_configuration_and_has_textarea_fallback(): void
    {
        config(['financershub.tinymce_api_key' => 'test-key']);
        $this->get('/admin/editor')->assertOk()->assertSee('data-tinymce-key="test-key"', false)
            ->assertSee('textarea', false)->assertDontSee('activate-tinymce')->assertDontSee('/assets/admin.js');
    }
}
