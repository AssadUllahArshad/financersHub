<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorProfile extends Model
{
    protected $fillable = ['user_id', 'name', 'slug', 'bio', 'is_demo'];

    protected $casts = ['is_demo' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
