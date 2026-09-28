<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationOperationsTest extends TestCase
{
    use RefreshDatabase;

    private function article(array $attributes = []): Article
    {
        $user = User::factory()->create(['role' => 'author']);
        $author = AuthorProfile::create(['name' => 'Real Writer', 'slug' => 'writer-'.$user->id, 'user_id' => $user->id]);
        $category = Category::firstOrCreate(['slug' => 'investing'], ['name' => 'Investing']);

        return Article::create($attributes + ['user_id' => $user->id, 'author_profile_id' => $author->id, 'category_id' => $category->id, 'title' => 'Useful guide', 'slug' => 'guide-'.$user->id, 'excerpt' => 'A useful explanation.', 'body' => '<p>Real article body.</p>', 'status' => 'published', 'published_at' => now()->subDay(), 'is_demo' => false]);
    }

    public function test_sitemap_includes_only_public_records_and_preview_disallows_indexing(): void
    {
        config(['financershub.design_preview' => false, 'app.url' => 'https://publication.example']);
        $published = $this->article(['slug' => 'visible']);
        foreach ([['status' => 'draft'], ['status' => 'scheduled'], ['is_demo' => true], ['published_at' => now()->addDay()]] as $i => $data) {
            $this->article($data + ['slug' => 'hidden-'.$i]);
        }
        $trashed = $this->article(['slug' => 'trashed']);
        $trashed->delete();
        $xml = $this->get('/sitemap.xml')->assertOk()->streamedContent();
        $this->assertNotFalse(simplexml_load_string($xml));
        $this->assertStringContainsString('https://publication.example/articles/visible', $xml);
        $this->assertStringContainsString('/authors/'.$published->authorProfile->slug, $xml);
        $this->assertStringNotContainsString('hidden-', $xml);
        $this->assertStringNotContainsString('trashed', $xml);
        $this->get('/robots.txt')->assertSee('Sitemap: https://publication.example/sitemap.xml');
        config(['financershub.design_preview' => true]);
        $this->assertStringNotContainsString('<url>', $this->get('/sitemap.xml')->streamedContent());
        $this->get('/robots.txt')->assertSee('Disallow: /');
    }

    public function test_article_metadata_is_safe_and_private_preview_has_no_article_schema(): void
    {
        config(['financershub.design_preview' => false, 'app.url' => 'https://publication.example']);
        $article = $this->article(['title' => 'Guide </script><script>alert(1)</script>', 'seo_title' => 'Custom title', 'seo_description' => 'Custom description']);
        $response = $this->get('/articles/'.$article->slug.'?tracking=ignored')->assertOk();
        $response->assertSee('<title>Custom title | FinancersHub</title>', false)->assertSee('content="Custom description"', false);
        $response->assertSee('href="https://publication.example/articles/'.$article->slug.'"', false);
        preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $response->getContent(), $matches);
        $schema = json_decode($matches[1], true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame($article->title, $schema['headline']);
        $this->assertSame('Real Writer', $schema['author']['name']);
        $this->assertStringNotContainsString('</script>', $matches[1]);
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get('/admin/articles/'.$article->id.'/preview')->assertOk()->assertSee('noindex,nofollow')->assertDontSee('application/ld+json');
        $this->get('/search?q=guide')->assertSee('noindex,nofollow');
        $this->get('/not-a-page')->assertNotFound()->assertSee('noindex,nofollow');
    }

    public function test_seo_settings_are_admin_only_and_dashboard_is_scoped(): void
    {
        config(['financershub.design_preview' => false]);
        $own = $this->article(['title' => 'Owned draft', 'status' => 'draft']);
        $this->article(['title' => 'Another writer story']);
        $this->actingAs($own->user)->get('/admin')->assertOk()->assertSee('Owned draft')->assertDontSee('Another writer story');
        $this->post('/admin/seo', ['seo_site_name' => 'Changed', 'seo_description' => 'Changed'])->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get('/admin')->assertOk()->assertSee('Owned draft')->assertSee('Another writer story')->assertDontSee('Sample content and figures');
        $this->post('/admin/seo', ['seo_site_name' => '', 'seo_description' => ''])->assertSessionHasErrors();
        $this->post('/admin/seo', ['seo_site_name' => 'Our Publication', 'seo_description' => 'Useful explanations.'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('site_settings', ['key' => 'seo_site_name', 'value' => 'Our Publication']);
        $this->get('/')->assertSee('Our Publication');
        $this->get('/admin/comments')->assertSee('Public comments are disabled');
        $this->get('/admin/advertisements')->assertSee('No advertising provider connected');
    }

    public function test_staff_login_is_standalone_and_registration_stays_unavailable(): void
    {
        $this->get('/login')->assertOk()->assertSee('Sign in to your studio')->assertSee('autocomplete="current-password"', false)->assertDontSee('newsletter-email');
        $this->get('/admin/login')->assertRedirect('/login');
        $this->get('/register')->assertNotFound();
        $this->post('/register', [])->assertNotFound();
    }
}
