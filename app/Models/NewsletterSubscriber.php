<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'email_hash', 'status', 'consented_at', 'confirmed_at', 'unsubscribed_at', 'provider_id', 'confirmation_token_hash'];

    protected $casts = ['email' => 'encrypted', 'consented_at' => 'datetime', 'confirmed_at' => 'datetime', 'unsubscribed_at' => 'datetime'];

    protected $hidden = ['email', 'email_hash', 'confirmation_token_hash', 'provider_id'];
}
