<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationPreflightTest extends TestCase
{
    use RefreshDatabase;

    public function test_local_configuration_fails_without_changing_records(): void
    {
        $this->artisan('financershub:preflight')->assertFailed();
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_ready_configuration_passes_but_debug_or_insecure_cookies_fail(): void
    {
        $this->app['env'] = 'production';
        config(['app.debug' => false, 'app.key' => 'configured-test-key', 'app.url' => 'https://publication.example', 'financershub.design_preview' => false, 'session.secure' => true, 'session.http_only' => true, 'session.driver' => 'file', 'cache.default' => 'file']);
        User::factory()->create(['role' => 'admin']);
        $this->artisan('financershub:preflight')->assertSuccessful();
        config(['app.debug' => true]);
        $this->artisan('financershub:preflight')->assertFailed();
        config(['app.debug' => false, 'session.secure' => false]);
        $this->artisan('financershub:preflight')->assertFailed();
    }
}
