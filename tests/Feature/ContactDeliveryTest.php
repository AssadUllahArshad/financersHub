<?php

namespace Tests\Feature;

use App\Mail\ContactNotification;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\ContactDelivery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactDeliveryTest extends TestCase
{
    use RefreshDatabase;

    private function submit(): ContactMessage
    {
        $this->post('/contact', ['name' => 'Test reader', 'email' => 'reader@example.test', 'subject' => 'Topic suggestion', 'message' => 'A test message with enough detail.', 'consent' => 1])->assertRedirect('/contact');

        return ContactMessage::latest('id')->firstOrFail();
    }

    public function test_notification_is_stored_then_sent_once_and_reply_to_is_reader(): void
    {
        Mail::fake();
        config(['mail.default' => 'smtp']);
        SiteSetting::create(['key' => 'contact_recipient', 'value' => 'owner@example.test']);
        $message = $this->submit();
        Mail::assertNothingSent();
        $this->assertSame('queued', $message->notification_status);
        $delivery = app(ContactDelivery::class);
        $this->assertTrue($delivery->send($message->id));
        $this->assertFalse($delivery->send($message->id));
        Mail::assertSent(ContactNotification::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('owner@example.test') && $mail->hasReplyTo('reader@example.test');
        });
        $this->assertSame('sent', $message->fresh()->notification_status);
    }

    public function test_mail_failure_preserves_message_and_retries_are_bounded(): void
    {
        config(['mail.default' => 'smtp', 'financershub.contact_recipient' => 'owner@example.test']);
        $message = $this->submit();
        Mail::shouldReceive('to')->times(3)->andThrow(new \RuntimeException('Synthetic transport failure'));
        $delivery = app(ContactDelivery::class);
        for ($i = 0; $i < 3; $i++) {
            $this->assertFalse($delivery->send($message->id));
            $this->assertSame('failed', $message->fresh()->notification_status);
            $this->travel(6)->minutes();
        }
        $this->assertFalse($delivery->send($message->id));
        $this->assertSame('A test message with enough detail.', $message->fresh()->message);
        $this->assertSame(3, $message->fresh()->notification_attempts);
    }

    public function test_unconfigured_delivery_and_admin_read_states(): void
    {
        config(['financershub.contact_recipient' => null]);
        $message = $this->submit();
        $this->assertSame('not_configured', $message->notification_status);
        $this->actingAs(User::factory()->create(['role' => 'editor']))->post('/admin/contacts/'.$message->id.'/read')->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get('/admin/contacts?filter=unread')->assertSee($message->message);
        $this->post('/admin/contacts/'.$message->id.'/read')->assertRedirect();
        $this->assertNotNull($message->fresh()->read_at);
        $this->get('/admin/contacts?filter=unread')->assertDontSee($message->message);
        $this->post('/admin/contacts/'.$message->id.'/read', ['unread' => 1])->assertRedirect();
        $this->assertNull($message->fresh()->read_at);
        $this->post('/admin/contacts/'.$message->id.'/retry')->assertStatus(422);
    }
}
