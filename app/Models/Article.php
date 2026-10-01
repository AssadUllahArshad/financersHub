<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $guarded = ['id'];

    protected $attributes = ['is_featured' => false];

    protected $casts = ['sources' => 'array', 'is_featured' => 'boolean', 'is_demo' => 'boolean', 'published_at' => 'datetime', 'scheduled_at' => 'datetime'];

    protected static function booted(): void
    {
        static::saved(function (Article $article) {
            if ($article->wasRecentlyCreated || $article->wasChanged('category_id')) {
                $article->categories()->syncWithoutDetaching([$article->category_id]);
            }
        });
    }

    public function getReadingMinutesAttribute(): int
    {
        $text = html_entity_decode(strip_tags($this->body ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return max(1, (int) ceil(count(preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY)) / 200));
    }

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

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeInCategory($query, $id)
    {
        return $query->where(fn ($q) => $q->where('category_id', $id)->orWhereHas('categories', fn ($q) => $q->where('categories.id', $id)));
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
