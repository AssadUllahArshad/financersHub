<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_headers_and_staff_cache_protection(): void
    {
        $this->get('/')->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Content-Security-Policy', "base-uri 'self'; object-src 'none'; frame-ancestors 'self'");
        $this->get('/login')->assertHeader('Cache-Control', 'no-store, private');
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get('/admin/contacts')->assertOk()->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_write_routes_reject_missing_csrf_tokens(): void
    {
        // Laravel normally skips CSRF checks in tests; enable the real middleware here.
        $this->app->bind(VerifyCsrfToken::class, fn ($app) => new class($app, $app['encrypter']) extends VerifyCsrfToken
        {
            protected function runningUnitTests()
            {
                return false;
            }
        });
        $this->post('/login', ['email' => 'staff@example.test', 'password' => 'invalid'])->assertStatus(419);
        $this->post('/contact', [])->assertStatus(419);
        $this->actingAs(User::factory()->create(['role' => 'admin']))->post('/admin/seo', [])->assertStatus(419);
    }

    public function test_login_throttle_limits_repeated_attempts(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', ['email' => 'missing@example.test', 'password' => 'invalid'])->assertSessionHasErrors('email');
        }
        $this->post('/login', ['email' => 'missing@example.test', 'password' => 'invalid'])->assertStatus(429);
        $this->assertGuest();
    }
}
