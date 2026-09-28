<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\User;
use App\Services\ArticleHtml;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $editor;

    private array $data;

    protected function setUp(): void
    {
        parent::setUp();
        config(['financershub.design_preview' => false]);
        $this->editor = User::factory()->create(['role' => 'editor']);
        $author = AuthorProfile::create(['user_id' => $this->editor->id, 'name' => 'Verified Writer', 'slug' => 'verified-writer']);
        $category = Category::create(['name' => 'Investing', 'slug' => 'investing']);
        $this->data = ['title' => 'A real editorial guide', 'slug' => 'real-guide', 'excerpt' => 'Useful context', 'body' => '<h2>Read this</h2><p>Helpful body.</p>', 'author_profile_id' => $author->id, 'category_id' => $category->id, 'sources' => 'https://www.investor.gov/'];
        $this->actingAs($this->editor);
    }

    private function draft(): Article
    {
        $this->post('/admin/articles', $this->data)->assertSessionHasNoErrors()->assertRedirect();

        return Article::firstOrFail();
    }

    public function test_complete_editorial_journey_and_draft_privacy(): void
    {
        $article = $this->draft();
        $this->get('/admin/articles/'.$article->id.'/edit')->assertOk();
        $this->get('/admin/articles/'.$article->id.'/preview')->assertOk()->assertSee('Private editorial preview');
        $this->get('/articles/real-guide')->assertNotFound();
        $this->post('/admin/articles/'.$article->id.'/transition', ['status' => 'published'])->assertSessionHasErrors('status');
        $this->post('/admin/articles/'.$article->id.'/transition', ['status' => 'in-review'])->assertSessionHasNoErrors();
        $this->post('/admin/articles/'.$article->id.'/transition', ['status' => 'published'])->assertSessionHasNoErrors();
        $this->get('/articles/real-guide')->assertOk()->assertSee('Helpful body.')->assertSee('href="#section-1"', false)->assertSee('id="section-1"', false);
        $this->get('/')->assertOk()->assertSee($article->title);
        $this->get('/search?q=editorial')->assertOk()->assertSee($article->title);
        $this->post('/admin/articles/'.$article->id.'/transition', ['status' => 'unpublished'])->assertSessionHasNoErrors();
        $this->get('/articles/real-guide')->assertNotFound();
    }

    public function test_update_revision_restore_and_slug_redirect(): void
    {
        $article = $this->draft();
        foreach (['in-review', 'published'] as $state) {
            $this->post('/admin/articles/'.$article->id.'/transition', ['status' => $state])->assertSessionHasNoErrors();
        }
        $revision = $article->revisions()->first();
        $data = array_merge($this->data, ['slug' => 'new-guide', 'title' => 'Revised guide', 'revision_id' => $article->revisions()->max('id')]);
        $this->put('/admin/articles/'.$article->id, $data)->assertSessionHasNoErrors();
        $this->get('/articles/real-guide')->assertRedirect('/articles/new-guide');
        $this->put('/admin/articles/'.$article->id, $data)->assertStatus(409);
        $this->post('/admin/articles/'.$article->id.'/revisions/'.$revision->id)->assertSessionHasNoErrors();
        $this->assertSame('draft', $article->fresh()->status);
        $this->assertSame($this->data['title'], $article->fresh()->title);
        $this->get('/articles/new-guide')->assertNotFound();
    }

    public function test_scheduling_and_trash_restore_are_private_until_published(): void
    {
        $article = $this->draft();
        $this->post('/admin/articles/'.$article->id.'/transition', ['status' => 'in-review'])->assertSessionHasNoErrors();
        $this->post('/admin/articles/'.$article->id.'/transition', ['status' => 'scheduled', 'scheduled_at' => now()->addHour()->toDateTimeString()])->assertSessionHasNoErrors();
        $this->artisan('articles:publish-due')->assertSuccessful();
        $this->assertSame('scheduled', $article->fresh()->status);
        $this->travel(2)->hours();
        $this->artisan('articles:publish-due')->assertSuccessful();
        $this->assertSame('published', $article->fresh()->status);
        $this->delete('/admin/articles/'.$article->id)->assertRedirect();
        $this->get('/articles/real-guide')->assertNotFound();
        $this->post('/admin/articles/'.$article->id.'/restore')->assertRedirect();
        $this->assertSame('draft', $article->fresh()->status);
    }

    public function test_author_cannot_change_another_writers_article_or_publish(): void
    {
        $article = $this->draft();
        $outsider = User::factory()->create(['role' => 'author']);
        $this->actingAs($outsider);
        $this->get('/admin/articles/'.$article->id.'/preview')->assertForbidden();
        $this->put('/admin/articles/'.$article->id, $this->data)->assertForbidden();
        $this->delete('/admin/articles/'.$article->id)->assertForbidden();
        $this->post('/admin/articles/'.$article->id.'/transition', ['status' => 'in-review'])->assertForbidden();
    }

    public function test_active_markup_is_removed_and_safe_formatting_survives(): void
    {
        $clean = app(ArticleHtml::class)->clean('<h2 onclick="bad()">Heading</h2><script>alert(1)</script><svg onload="bad()"></svg><a href="javascript:bad()">Bad</a><p style="x">Text <strong>bold</strong></p><a href="https://example.com">Source</a>');
        $this->assertStringNotContainsString('script', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('onload', $clean);
        $this->assertStringNotContainsString('style=', $clean);
        $this->assertStringContainsString('<strong>bold</strong>', $clean);
        $this->assertStringContainsString('href="https://example.com"', $clean);
    }
}
