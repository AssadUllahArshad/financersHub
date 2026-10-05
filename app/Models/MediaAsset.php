<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaAsset extends Model
{
    protected $fillable = ['user_id', 'path', 'original_name', 'mime_type', 'size', 'width', 'height', 'alt_text', 'rights'];

    public function getSrcsetAttribute(): string
    {
        return app(\App\Services\ResponsiveImages::class)->srcset($this);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
