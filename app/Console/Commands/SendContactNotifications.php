<?php

namespace App\Console\Commands;

use App\Models\ContactMessage;
use App\Services\ContactDelivery;
use Illuminate\Console\Command;

class SendContactNotifications extends Command
{
    protected $signature = 'contacts:send-notifications';

    protected $description = 'Deliver up to 20 queued contact notifications with bounded retries';

    public function handle(ContactDelivery $delivery): int
    {
        if (! $delivery->configured()) {
            $this->warn('Contact recipient and a sending mail transport must be configured.');

            return self::FAILURE;
        }
        ContactMessage::where('notification_status', 'sending')->where('notification_attempts', '>=', 3)
            ->where('notification_claimed_at', '<', now()->subMinutes(10))
            ->update(['notification_status' => 'failed', 'notification_claimed_at' => null]);
        $ids = ContactMessage::whereIn('notification_status', ['queued', 'failed', 'sending'])->where('notification_attempts', '<', 3)
            ->where(fn ($q) => $q->whereNull('notification_due_at')->orWhere('notification_due_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('notification_claimed_at')->orWhere('notification_claimed_at', '<', now()->subMinutes(10)))
            ->orderBy('id')->limit(20)->pluck('id');
        $sent = 0;
        foreach ($ids as $id) {
            $sent += (int) $delivery->send($id);
        }
        $this->info('Notifications accepted by mail transport: '.$sent);

        return self::SUCCESS;
    }
}
