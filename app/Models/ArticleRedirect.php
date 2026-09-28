<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleRedirect extends Model
{
    protected $fillable = ['slug', 'article_id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
