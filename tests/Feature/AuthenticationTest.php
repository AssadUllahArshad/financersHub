<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin(): void
    {
        foreach (['', '/articles', '/editor', '/media', '/settings', '/newsletter', '/faq'] as $path) {
            $this->get('/admin'.$path)->assertRedirect('/login');
        }
    }

    public function test_editor_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create(['role' => 'editor', 'password' => 'long-test-password']);
        $this->post('/login', ['email' => $user->email, 'password' => 'long-test-password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_readers_and_invalid_credentials_are_rejected(): void
    {
        $reader = User::factory()->create(['role' => 'reader']);
        $this->actingAs($reader)->get('/admin')->assertForbidden();
        auth()->logout();
        $this->post('/login', ['email' => $reader->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_roles_restrict_management(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $this->assertFalse($author->can('manage-content'));
        $this->assertFalse($author->can('manage-settings'));
        $this->assertTrue($author->can('studio'));
    }
}
