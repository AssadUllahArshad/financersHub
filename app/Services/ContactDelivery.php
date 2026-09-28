<?php

namespace App\Services;

use App\Mail\ContactNotification;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactDelivery
{
    public function recipient(): ?string
    {
        return \App\Models\SiteSetting::where('key', 'contact_recipient')->value('value') ?: config('financershub.contact_recipient');
    }

    public function configured(): bool
    {
        return (bool) filter_var($this->recipient(), FILTER_VALIDATE_EMAIL)
            && ! in_array(config('mail.default'), ['log', 'array'], true);
    }

    public function send(int $id): bool
    {
        if (! $this->configured()) {
            return false;
        }
        $contact = DB::transaction(function () use ($id) {
            $contact = ContactMessage::lockForUpdate()->find($id);
            if (! $contact || $contact->notification_attempts >= 3 || $contact->notified_at) {
                return null;
            }
            $eligible = in_array($contact->notification_status, ['queued', 'failed'], true)
                || ($contact->notification_status === 'sending' && $contact->notification_claimed_at?->lt(now()->subMinutes(10)));
            if (! $eligible || $contact->notification_due_at?->isFuture()) {
                return null;
            }
            $contact->forceFill(['notification_status' => 'sending', 'notification_claimed_at' => now(), 'notification_attempts' => $contact->notification_attempts + 1])->save();

            return $contact;
        });
        if (! $contact) {
            return false;
        }
        try {
            Mail::to($this->recipient())->send(new ContactNotification($contact));
            $contact->forceFill(['notification_status' => 'sent', 'notified_at' => now(), 'notification_claimed_at' => null])->save();

            return true;
        } catch (\Throwable $error) {
            $contact->forceFill(['notification_status' => 'failed', 'notification_due_at' => now()->addMinutes(5), 'notification_claimed_at' => null])->save();
            Log::warning('Contact notification failed.', ['reference' => $contact->reference]);

            return false;
        }
    }
}
