<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = ['sources' => 'array', 'is_demo' => 'boolean', 'published_at' => 'datetime', 'scheduled_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authorProfile()
    {
        return $this->belongsTo(AuthorProfile::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function mediaAsset()
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function revisions()
    {
        return $this->hasMany(ArticleRevision::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_demo', false)->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
