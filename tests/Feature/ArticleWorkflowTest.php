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

    public function test_inline_image_and_table_survive_save_preview_and_publication(): void
    {
        $this->data['body'] = '<h2 style="text-align:center">Budget</h2><p>Useful explanation.</p><figure><img src="/uploads/images/example.webp" alt="Budget chart"><figcaption>Budget totals</figcaption></figure><table><tbody><tr><th scope="col" colspan="2">Total</th></tr><tr><td>Food</td><td>100</td></tr></tbody></table>';
        $article = $this->draft();
        $this->get('/admin/articles/'.$article->id.'/preview')->assertOk()->assertSee('alt="Budget chart"', false)->assertSee('colspan="2"', false)->assertSee('section-1');
        $article->update(['status' => 'published', 'published_at' => now()->subMinute()]);
        auth()->logout();
        $this->get('/articles/'.$article->slug)->assertOk()->assertSee('<figcaption>Budget totals</figcaption>', false)->assertSee('alt="Budget chart"', false)->assertSee('BreadcrumbList');
    }

    public function test_script_only_body_is_rejected_after_sanitizing(): void
    {
        $this->post('/admin/articles', array_merge($this->data, ['body' => '<script>alert(1)</script>']))->assertSessionHasErrors('body');
        $this->assertDatabaseCount('articles', 0);
    }

    private function draft(): Article
    {
        $this->post('/admin/articles', $this->data)->assertSessionHasNoErrors()->assertRedirect();

        return Article::firstOrFail();
    }

    public function test_composer_can_save_and_publish_featured_article_atomically(): void
    {
        $this->post('/admin/articles', array_merge($this->data, ['save_action' => 'apply', 'desired_status' => 'published', 'is_featured' => 1]))->assertSessionHasNoErrors();
        $article = Article::firstOrFail();
        $this->assertSame('published', $article->status);
        $this->assertTrue($article->is_featured);
        $this->assertSame(1, $article->reading_minutes);
        $this->get('/')->assertSee($article->title);
        $this->get('/articles/'.$article->slug)->assertSee('min read')->assertSee('Suggest a correction')->assertSee('In this guide');
    }

    public function test_composer_author_cannot_publish_or_feature_an_article(): void
    {
        $writer = User::factory()->create(['role' => 'author']);
        $profile = AuthorProfile::create(['user_id' => $writer->id, 'name' => 'Staff writer', 'slug' => 'staff-writer']);
        $this->actingAs($writer);
        $payload = array_merge($this->data, ['author_profile_id' => $profile->id, 'save_action' => 'apply', 'desired_status' => 'published', 'is_featured' => 1]);
        $this->post('/admin/articles', $payload)->assertForbidden();
        $this->assertDatabaseCount('articles', 0);
        $this->post('/admin/articles', array_merge($payload, ['save_action' => 'save']))->assertSessionHasNoErrors();
        $article = Article::firstOrFail();
        $this->assertFalse($article->is_featured);
        $this->assertSame('draft', $article->status);
    }

    public function test_failed_publication_rolls_back_content_changes(): void
    {
        $this->post('/admin/articles', array_merge($this->data, ['sources' => '', 'save_action' => 'apply', 'desired_status' => 'published']))->assertSessionHasErrors('status');
        $this->assertDatabaseCount('articles', 0);
        $this->assertDatabaseCount('article_revisions', 0);
    }

    public function test_composer_scheduling_and_rescheduling_persist(): void
    {
        $this->post('/admin/articles', array_merge($this->data, ['save_action' => 'apply', 'desired_status' => 'scheduled', 'publish_date' => now()->addDays(2)->format('Y-m-d H:i:s')]))->assertSessionHasNoErrors();
        $article = Article::firstOrFail();
        $this->assertSame('scheduled', $article->status);
        $date = now()->addDays(3)->startOfMinute();
        $this->put('/admin/articles/'.$article->id, array_merge($this->data, ['save_action' => 'apply', 'desired_status' => 'scheduled', 'publish_date' => $date->format('Y-m-d H:i:s'), 'revision_id' => $article->revisions()->max('id')]))->assertSessionHasNoErrors();
        $this->assertTrue($article->fresh()->scheduled_at->equalTo($date));
        $this->get('/articles/'.$article->slug)->assertNotFound();
    }

    public function test_multiple_categories_are_saved_listed_protected_and_restored(): void
    {
        $secondary = Category::create(['name' => 'Saving', 'slug' => 'saving']);
        $this->data['categories'] = [$secondary->id];
        $article = $this->draft();
        $this->assertCount(2, $article->categories);
        $revision = $article->revisions()->latest('id')->first();
        $this->get('/categories/saving')->assertDontSee($article->title);
        foreach (['in-review', 'published'] as $state) {
            $this->post('/admin/articles/'.$article->id.'/transition', ['status' => $state])->assertSessionHasNoErrors();
        }
        $this->get('/categories/saving')->assertOk()->assertSee($article->title);
        $this->get('/categories/investing')->assertOk()->assertSee($article->title);
        $this->get('/admin/articles?category='.$secondary->id)->assertSee($article->title);
        $this->delete('/admin/content/categories/'.$secondary->id)->assertSessionHasErrors('content');
        $this->put('/admin/articles/'.$article->id, array_merge($this->data, ['categories' => [], 'revision_id' => $article->revisions()->max('id')]))->assertSessionHasNoErrors();
        $this->assertCount(1, $article->fresh()->categories);
        $this->post('/admin/articles/'.$article->id.'/revisions/'.$revision->id, [])->assertSessionHasNoErrors();
        $this->assertCount(2, $article->fresh()->categories);
        $this->assertSame('draft', $article->fresh()->status);
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
