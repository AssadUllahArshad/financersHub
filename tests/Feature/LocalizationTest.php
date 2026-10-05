<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    private function article(): Article
    {
        config(['financershub.design_preview' => false, 'financershub.search_indexing_enabled' => true]);

        return Article::create([
            'user_id' => User::factory()->create(['role' => 'admin'])->id,
            'author_profile_id' => AuthorProfile::create(['name' => 'Editorial Team', 'slug' => 'team', 'is_demo' => false])->id,
            'category_id' => Category::create(['name' => 'Saving', 'slug' => 'saving'])->id,
            'title' => 'English savings guide', 'slug' => 'savings-guide', 'excerpt' => 'English introduction',
            'body' => '<p>English content.</p>', 'status' => 'published', 'published_at' => now()->subDay(), 'is_demo' => false,
        ]);
    }

    public function test_translation_only_images_follow_publication_and_cannot_be_deleted(): void
    {
        \Illuminate\Support\Facades\Storage::fake('uploads');
        $article = $this->article();
        $this->actingAs($article->user)->postJson('/admin/media', [
            'image' => \Illuminate\Http\UploadedFile::fake()->image('translation.png', 1000, 600),
            'alt_text' => 'Spanish diagram', 'rights' => 'Owned',
        ])->assertCreated();
        $media = \App\Models\MediaAsset::firstOrFail();
        $translation = $article->translations()->create([
            'locale' => 'es', 'title' => 'Guia', 'excerpt' => 'Resumen',
            'body' => '<p><img src="/uploads/'.$media->path.'" alt="Diagram"></p>',
            'is_published' => false,
        ]);
        auth()->logout();
        $this->get('/media/'.$media->id.'?w=640')->assertNotFound();
        $translation->update(['is_published' => true]);
        $this->get('/media/'.$media->id.'?w=640')->assertOk();
        $article->update(['status' => 'draft']);
        $this->get('/media/'.$media->id.'?w=640')->assertNotFound();
        $this->actingAs($article->user)->delete('/admin/media/'.$media->id)->assertSessionHasErrors('image');
        \Illuminate\Support\Facades\Storage::disk('uploads')->assertExists($media->path);
    }

    public function test_public_language_switch_and_admin_language_are_isolated(): void
    {
        $this->get('/es')->assertOk()->assertSee('lang="es"', false)->assertSee('Comprende tus finanzas.')->assertSee('/es/categories/saving', false);
        $this->get('/')->assertOk()->assertSee('lang="en"', false)->assertSee('Understand money.');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/admin/profile')->assertOk()->assertSee('My profile')->assertHeader('Content-Language', 'en');
        $this->get('/fr')->assertNotFound();
        $this->get('/es/about')->assertOk()->assertDontSee('view=', false)->assertDontSee('status=200', false);
    }

    public function test_article_fallback_is_english_and_draft_translation_is_private(): void
    {
        $article = $this->article();
        $article->translations()->create(['locale' => 'es', 'title' => 'Borrador secreto', 'excerpt' => 'Secreto', 'body' => '<p>Secreto.</p>']);
        $this->get('/es/articles/savings-guide')->assertOk()->assertSee('English savings guide')->assertDontSee('Borrador secreto')
            ->assertSee('lang="en"', false)->assertSee('traducción al español')->assertDontSee('hreflang="es" href=', false);
        $this->get('/es/search?q=Secreto')->assertDontSee('Borrador secreto');
        $this->assertStringNotContainsString('/es/articles/savings-guide', $this->get('/sitemap.xml')->streamedContent());
    }

    public function test_published_translation_appears_in_article_cards_search_and_sitemap(): void
    {
        $article = $this->article();
        $article->translations()->create(['locale' => 'es', 'title' => 'Guía de ahorro', 'excerpt' => 'Planificación familiar', 'body' => '<p>Contenido en español.</p>', 'is_published' => true]);
        $this->get('/es/articles/savings-guide')->assertOk()->assertSee('Guía de ahorro')->assertSee('Contenido en español.')
            ->assertSee('hreflang="es"', false)->assertSee('/es/articles/savings-guide', false);
        $this->get('/articles/savings-guide')->assertSee('English savings guide')->assertDontSee('Contenido en español.');
        $this->get('/es/search?q=Planificación')->assertSee('Guía de ahorro');
        $this->get('/es')->assertSee('Guía de ahorro');
        $this->assertStringContainsString('/es/articles/savings-guide', $this->get('/sitemap.xml')->streamedContent());
        $article->update(['status' => 'draft']);
        $this->get('/es/articles/savings-guide')->assertNotFound();
    }

    public function test_translation_saving_preserves_english_sanitizes_html_and_detects_conflicts(): void
    {
        $article = $this->article();
        $this->actingAs($article->user);
        $url = '/admin/articles/'.$article->id.'/translations/es';
        $data = ['title' => 'Guía', 'excerpt' => 'Resumen', 'body' => '<p>Texto seguro</p><script>alert(1)</script>', 'is_published' => 1, 'version' => 0];
        $this->get($url)->assertOk()->assertSee('Spanish translation');
        $this->put($url, $data)->assertSessionHasNoErrors()->assertRedirect();
        $translation = $article->translations()->firstOrFail();
        $this->assertStringNotContainsString('<script', $translation->body);
        $this->assertSame('English savings guide', $article->fresh()->title);
        $this->put($url, $data)->assertStatus(409);
        $this->put(str_replace('/es', '/fr', $url), $data)->assertNotFound();
    }

    public function test_translation_permissions_and_spanish_contact_validation(): void
    {
        $article = $this->article();
        $url = '/admin/articles/'.$article->id.'/translations/es';
        $this->get($url)->assertRedirect('/login');
        $author = User::factory()->create(['role' => 'author']);
        $this->actingAs($author)->get($url)->assertForbidden();
        $this->postJson('/es/contact', [])->assertUnprocessable()->assertSee('obligatorio');
    }

    public function test_spanish_contact_submission_preserves_language_and_spanish_errors_render(): void
    {
        $this->post('/es/contact', ['name' => 'Test Reader', 'email' => 'reader@example.test', 'subject' => 'General inquiry', 'message' => 'Una pregunta para el equipo editorial.', 'consent' => '1'])
            ->assertRedirect('/es/contact')->assertSessionHas('contact_status', fn ($text) => str_starts_with($text, 'Tu mensaje'));
        $this->get('/es/this-page-does-not-exist')->assertNotFound()->assertSee('Página no encontrada')->assertSee('lang="es"', false);
    }
}
