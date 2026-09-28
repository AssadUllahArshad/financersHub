<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['reference', 'name', 'email', 'subject', 'article_url', 'message', 'resolved_at'];

    protected $casts = ['name' => 'encrypted', 'email' => 'encrypted', 'message' => 'encrypted', 'resolved_at' => 'datetime', 'read_at' => 'datetime', 'notification_due_at' => 'datetime', 'notification_claimed_at' => 'datetime', 'notified_at' => 'datetime'];

    protected $hidden = ['name', 'email', 'message'];
}
