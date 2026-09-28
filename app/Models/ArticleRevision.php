<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleRevision extends Model
{
    protected $fillable = ['article_id', 'user_id', 'action', 'snapshot'];

    protected $casts = ['snapshot' => 'array'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
