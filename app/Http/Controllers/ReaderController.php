<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReaderController extends Controller
{
    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150', 'email' => ['required', 'email', 'max:254', 'not_regex:/[\r\n]/'],
            'subject' => ['required', Rule::in(['Question about an article', 'Correction or update', 'Topic suggestion', 'General inquiry'])],
            'article' => 'nullable|url:http,https|max:2000', 'message' => 'required|string|min:10|max:10000', 'website' => 'nullable|string|max:0', 'consent' => 'accepted',
        ]);
        $reference = (string) Str::uuid();
        $contact = ContactMessage::create(['reference' => $reference, 'name' => $data['name'], 'email' => $data['email'], 'subject' => $data['subject'], 'article_url' => $data['article'] ?? null, 'message' => $data['message']]);

        $contact->forceFill(['notification_status' => app(\App\Services\ContactDelivery::class)->configured() ? 'queued' : 'not_configured'])->save();

        return redirect()->route('contact')->with('contact_status', 'Your message has been stored for editorial review. Reference: '.$reference);
    }

    public function contacts(Request $request)
    {
        $this->authorize('manage-settings');

        $filters = ['all', 'unread', 'pending', 'reviewed', 'delivery-failed'];
        $filter = in_array($request->query('filter'), $filters, true) ? $request->query('filter') : 'all';
        $request->validate(['reference' => 'nullable|string|max:36', 'subject' => ['nullable', Rule::in(['Question about an article', 'Correction or update', 'Topic suggestion', 'General inquiry'])]]);
        $query = ContactMessage::latest()->orderByDesc('id');
        if ($request->filled('reference')) {
            $query->where('reference', trim($request->query('reference')));
        }
        if ($request->filled('subject')) {
            $query->where('subject', $request->query('subject'));
        }
        if ($filter === 'pending') {
            $query->whereNull('resolved_at');
        }
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }
        if ($filter === 'reviewed') {
            $query->whereNotNull('resolved_at');
        }
        if ($filter === 'delivery-failed') {
            $query->where('notification_status', 'failed');
        }

        return view('cms.contacts', ['messages' => $query->paginate(15)->withQueryString(), 'unread' => ContactMessage::whereNull('read_at')->count(), 'filter' => $filter, 'deliveryReady' => app(\App\Services\ContactDelivery::class)->configured()]);
    }

    public function resolve(ContactMessage $message)
    {
        $this->authorize('manage-settings');
        $message->forceFill(['resolved_at' => now(), 'read_at' => $message->read_at ?? now()])->save();

        return back()->with('status', 'Message marked as reviewed.');
    }

    public function reopen(ContactMessage $message)
    {
        $this->authorize('manage-settings');
        $message->forceFill(['resolved_at' => null])->save();

        return back()->with('status', 'Message reopened for editorial review.');
    }

    public function read(Request $request, ContactMessage $message)
    {
        $this->authorize('manage-settings');
        $message->forceFill(['read_at' => $request->boolean('unread') ? null : now()])->save();

        return back()->with('status', 'Message status updated.');
    }

    public function retry(ContactMessage $message, \App\Services\ContactDelivery $delivery)
    {
        $this->authorize('manage-settings');
        abort_unless($delivery->configured(), 422, 'Configure a recipient and sending mail transport first.');
        $updated = ContactMessage::whereKey($message->id)->whereIn('notification_status', ['failed', 'not_configured'])->whereNull('notified_at')
            ->update(['notification_status' => 'queued', 'notification_attempts' => 0, 'notification_due_at' => null, 'notification_claimed_at' => null]);
        abort_unless($updated, 409, 'Notification is already queued, sending or sent.');

        return back()->with('status', 'Notification queued for the next scheduler run.');
    }

    public function newsletter()
    {
        $this->authorize('manage-settings');

        return view('cms.newsletter', ['confirmed' => NewsletterSubscriber::where('status', 'confirmed')->count()]);
    }

    public function subscribe()
    {
        return response()->json(['message' => 'Newsletter signup is not available yet. No email address has been collected.'], 503);
    }
}
