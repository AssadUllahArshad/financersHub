<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReaderInteractionsTest extends TestCase
{
    use RefreshDatabase;

    private function payload(): array
    {
        return ['name' => 'Reader', 'email' => 'reader@example.com', 'subject' => 'Correction or update', 'message' => 'Please check the example calculation.', 'consent' => 1];
    }

    public function test_contact_is_really_stored_encrypted_and_visible_only_to_admin(): void
    {
        $this->post('/contact', $this->payload())->assertRedirect('/contact')->assertSessionHas('contact_status');
        $message = ContactMessage::firstOrFail();
        $this->assertSame('reader@example.com', $message->email);
        $this->assertNotSame('reader@example.com', DB::table('contact_messages')->value('email'));
        $this->get('/admin/contacts')->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'editor']))->get('/admin/contacts')->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get('/admin/contacts')->assertOk()->assertSee('Please check the example calculation.');
    }

    public function test_inbox_filters_and_reopening_preserve_delivery_and_read_state(): void
    {
        $first = ContactMessage::create(['reference' => '00000000-0000-4000-8000-000000000001', 'name' => 'Reader', 'email' => 'reader@example.test', 'subject' => 'Correction or update', 'message' => 'First unique message']);
        $first->forceFill(['resolved_at' => now(), 'read_at' => now(), 'notification_status' => 'sent'])->save();
        ContactMessage::create(['reference' => '00000000-0000-4000-8000-000000000002', 'name' => 'Reader', 'email' => 'other@example.test', 'subject' => 'Topic suggestion', 'message' => 'Second unique message']);
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get('/admin/contacts?reference='.$first->reference)->assertOk()->assertSee('First unique message')->assertDontSee('Second unique message');
        $this->get('/admin/contacts?subject=Topic%20suggestion')->assertOk()->assertSee('Second unique message')->assertDontSee('First unique message');
        $this->get('/admin/contacts?filter=pending')->assertOk()->assertDontSee('First unique message')->assertSee('Second unique message');
        $this->post('/admin/contacts/'.$first->id.'/reopen')->assertRedirect();
        $this->assertNull($first->fresh()->resolved_at);
        $this->assertNotNull($first->fresh()->read_at);
        $this->assertSame('sent', $first->fresh()->notification_status);
        $this->get('/admin/contacts?filter=pending')->assertSee('First unique message');
        $first->refresh()->forceFill(['resolved_at' => now()])->save();
        $this->actingAs(User::factory()->create(['role' => 'editor']))->post('/admin/contacts/'.$first->id.'/reopen')->assertForbidden();
        $this->assertNotNull($first->fresh()->resolved_at);
    }

    public function test_spam_and_invalid_submissions_are_rejected(): void
    {
        $this->post('/contact', array_merge($this->payload(), ['website' => 'spam']))->assertSessionHasErrors('website');
        $this->post('/contact', array_merge($this->payload(), ['consent' => 0]))->assertSessionHasErrors('consent');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_newsletter_does_not_claim_collection_without_provider(): void
    {
        $this->postJson('/newsletter/subscribe', ['email' => 'reader@example.com'])->assertStatus(503)->assertJsonFragment(['message' => 'Newsletter signup is not available yet. No email address has been collected.']);
        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }
}
