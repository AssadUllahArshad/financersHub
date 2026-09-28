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

    public function test_every_supplied_screen_renders_and_local_assets_and_links_resolve(): void
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views/FinancersHub-frontend/dist')));
        $checked = [];
        foreach ($files as $file) {
            if ($file->getExtension() !== 'html') {
                continue;
            }
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen(resource_path('views/FinancersHub-frontend/dist')) + 1));
            $path = '/'.substr($relative, 0, -5);
            $path = match ($path) {
                '/index' => '/', '/admin/index' => '/admin', default => $path
            };
            $response = $this->get($path)->assertOk()->assertSee('FinancersHub')->assertSee('noindex,nofollow', false);
            $dom = new \DOMDocument;
            @$dom->loadHTML($response->getContent());
            foreach ((new \DOMXPath($dom))->query('//*[@href or @src or @action]') as $element) {
                if ($element->tagName === 'form' && strtolower($element->getAttribute('method')) === 'post') {
                    continue;
                }
                $url = $element->getAttribute('href') ?: ($element->getAttribute('src') ?: $element->getAttribute('action'));
                if (str_starts_with($url, '#') || preg_match('~^(mailto:|tel:|https?://(?!localhost))~', $url)) {
                    continue;
                }
                $local = parse_url($url, PHP_URL_PATH);
                if (! $local || isset($checked[$local])) {
                    continue;
                }
                $checked[$local] = true;
                if (str_starts_with($local, '/assets/')) {
                    $this->assertFileExists(public_path(ltrim($local, '/')), $path.' references '.$local);
                } else {
                    $this->get($local)->assertSuccessful();
                }
            }
            $this->get('/'.$relative)->assertRedirect($path);
        }
        $this->assertGreaterThan(47, count($checked));
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
