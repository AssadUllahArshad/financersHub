<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\TestingContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminToolsTest extends TestCase
{
    use RefreshDatabase;

    public function test_console_requires_admin_password_and_only_runs_reviewed_commands(): void
    {
        $this->get('/admin/maintenance')->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'editor']))->get('/admin/maintenance')->assertForbidden();
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'unique-test-password']);
        $this->actingAs($admin);
        $this->post('/admin/maintenance', ['command' => 'migrate:fresh', 'password' => 'unique-test-password'])->assertSessionHasErrors('command');
        $this->post('/admin/maintenance', ['command' => 'view:clear', 'password' => 'wrong'])->assertSessionHasErrors('password');
        Artisan::shouldReceive('call')->once()->with('view:clear')->andReturn(0);
        Artisan::shouldReceive('output')->once()->andReturn('Compiled views cleared.');
        $this->post('/admin/maintenance', ['command' => 'view:clear', 'password' => 'unique-test-password'])->assertRedirect('/admin/maintenance');
        $this->assertDatabaseHas('maintenance_runs', ['user_id' => $admin->id, 'command' => 'view:clear', 'exit_code' => 0]);
        $this->assertDatabaseCount('users', 2);
    }

    public function test_migrations_require_confirmation_and_catalog_shows_terminal_commands(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin', 'password' => 'unique-test-password']));
        $this->get('/admin/maintenance')->assertOk()->assertSee('All installed Artisan commands')->assertSee('migrate:fresh')->assertSee('Terminal only');
        $this->post('/admin/maintenance', ['command' => 'migrate', 'password' => 'unique-test-password'])->assertSessionHasErrors('confirm_migration');
        Artisan::shouldReceive('call')->once()->with('migrate', ['--force' => true, '--no-interaction' => true])->andReturn(0);
        Artisan::shouldReceive('output')->once()->andReturn('Nothing to migrate.');
        $this->post('/admin/maintenance', ['command' => 'migrate', 'password' => 'unique-test-password', 'confirm_migration' => 1])->assertRedirect('/admin/maintenance');
        $this->assertDatabaseHas('maintenance_runs', ['command' => 'migrate', 'exit_code' => 0]);
    }

    public function test_admin_seeder_is_idempotent_and_keeps_existing_password(): void
    {
        config(['financershub.admin_seed_email' => 'admin@example.test', 'financershub.admin_seed_password' => 'unique-seed-password-123']);
        $this->seed(AdminSeeder::class);
        $admin = User::where('email', 'admin@example.test')->firstOrFail();
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('unique-seed-password-123', $admin->password));
        config(['financershub.admin_seed_password' => 'changed-password-456']);
        $this->seed(AdminSeeder::class);
        $this->assertTrue(Hash::check('unique-seed-password-123', $admin->fresh()->password));
        $this->assertDatabaseCount('users', 1);
    }

    public function test_test_data_is_repeatable_and_cannot_be_published(): void
    {
        Storage::fake('uploads');
        $this->seed(TestingContentSeeder::class);
        $count = Article::count();
        $this->seed(TestingContentSeeder::class);
        $this->assertSame($count, Article::count());
        $this->assertGreaterThanOrEqual(11, $count);
        $this->assertSame(0, Article::published()->count());
        $this->assertDatabaseCount('contact_messages', 3);
    }

    public function test_advertising_verification_is_validated_without_loading_ad_scripts(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->post('/admin/advertisements', ['adsense_publisher_id' => '<script>'])->assertSessionHasErrors();
        $this->post('/admin/advertisements', ['adsense_publisher_id' => 'pub-1234567890123456'])->assertSessionHasNoErrors();
        $this->get('/ads.txt')->assertOk()->assertSee('google.com, pub-1234567890123456, DIRECT, f08c47fec0942fa0');
        $this->get('/')->assertSee('ca-pub-1234567890123456')->assertDontSee('adsbygoogle.js')->assertDontSee('class="design-notice"', false);
    }
}
