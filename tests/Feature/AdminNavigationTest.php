<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\AdminDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_lists_filter_and_use_separate_create_and_edit_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $tag = Tag::create(['name' => 'Planning example', 'slug' => 'planning-example']);
        Tag::create(['name' => 'Different example', 'slug' => 'different-example']);
        $this->get('/admin/tags?q=Planning')->assertOk()->assertSee('Planning example')->assertDontSee('Different example');
        $this->get('/admin/content/tags/create')->assertOk()->assertSee('Create Tag');
        $this->get('/admin/content/tags/'.$tag->id.'/edit')->assertOk()->assertSee('Planning example');
        $this->get('/admin/content/authors/create')->assertOk()->assertSee('Linked staff account');
        $this->actingAs(User::factory()->create(['role' => 'author']))->get('/admin/content/tags/create')->assertForbidden();
    }

    public function test_maintenance_history_filters_command_and_outcome(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        foreach ([['view:clear', 0, 'Successful view cleanup'], ['route:cache', 1, 'Route cache needs attention']] as [$command, $exit, $output]) {
            \Illuminate\Support\Facades\DB::table('maintenance_runs')->insert(['user_id' => $admin->id, 'command' => $command, 'exit_code' => $exit, 'output' => $output, 'created_at' => now(), 'updated_at' => now()]);
        }
        $this->get('/admin/maintenance?outcome=success')->assertOk()->assertSee('Successful view cleanup')->assertDontSee('Route cache needs attention');
        $this->get('/admin/maintenance?command=route:cache&outcome=attention')->assertOk()->assertSee('Route cache needs attention')->assertDontSee('Successful view cleanup');
    }

    public function test_demo_seeder_preserves_edited_and_trashed_records(): void
    {
        Storage::fake('local');
        $this->seed(AdminDemoSeeder::class);
        $article = Article::where('is_demo', true)->firstOrFail();
        $article->update(['title' => 'My retained edit']);
        $article->delete();
        $count = Article::withTrashed()->count();
        $this->seed(AdminDemoSeeder::class);
        $this->assertSame($count, Article::withTrashed()->count());
        $this->assertSame('My retained edit', $article->fresh()->title);
        $this->assertTrue($article->fresh()->trashed());
        $this->assertSame(0, Article::published()->count());
    }
}
