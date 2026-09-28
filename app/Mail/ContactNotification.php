<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Mail\Mailable;

class ContactNotification extends Mailable
{
    public function __construct(public ContactMessage $contact) {}

    public function build()
    {
        return $this->subject('FinancersHub contact: '.$this->contact->subject)
            ->replyTo($this->contact->email, $this->contact->name)
            ->view('mail.contact-notification');
    }
}
