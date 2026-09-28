<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ArticleWorkflow
{
    public function snapshot(Article $article, ?User $user, string $action): void
    {
        $snapshot = $article->only(['title', 'slug', 'excerpt', 'body', 'author_profile_id', 'category_id', 'media_asset_id', 'seo_title', 'seo_description', 'disclosure', 'sources']);
        $snapshot['tags'] = $article->tags()->pluck('tags.id')->all();
        $article->revisions()->create(['user_id' => $user?->id, 'action' => $action, 'snapshot' => $snapshot]);
    }

    public function transition(Article $article, ?User $user, string $target, ?string $schedule = null): Article
    {
        return DB::transaction(function () use ($article, $user, $target, $schedule) {
            $article = Article::lockForUpdate()->findOrFail($article->id);
            $allowed = [
                'draft' => ['in-review'], 'in-review' => ['draft', 'scheduled', 'published'],
                'scheduled' => ['draft', 'published', 'unpublished'], 'published' => ['unpublished'],
                'unpublished' => ['draft', 'in-review'],
            ];
            if (! in_array($target, $allowed[$article->status] ?? [], true)) {
                throw ValidationException::withMessages(['status' => 'This state transition is not available.']);
            }
            if ($user) {
                if ($target === 'in-review') {
                    abort_unless($user->can('update', $article), 403);
                } else {
                    abort_unless($user->can('publish', $article), 403);
                }
            } else {
                abort_unless($article->status === 'scheduled' && $target === 'published' && $article->scheduled_at?->lte(now()), 403);
            }
            if (in_array($target, ['scheduled', 'published'], true)) {
                if ($article->is_demo || $article->authorProfile->is_demo || trim(strip_tags($article->body)) === '' || empty($article->sources)) {
                    throw ValidationException::withMessages(['status' => 'Publication requires real authorship, article content and primary sources. Demo content cannot be published.']);
                }
                if ($target === 'scheduled' && (! $schedule || ! \Illuminate\Support\Carbon::parse($schedule)->isFuture())) {
                    throw ValidationException::withMessages(['scheduled_at' => 'Choose a future date and time (UTC).']);
                }
            }
            $this->snapshot($article, $user, 'status:'.$article->status.'→'.$target);
            $article->status = $target;
            $article->scheduled_at = $target === 'scheduled' ? $schedule : null;
            if ($target === 'published') {
                $article->published_at ??= now();
            }
            $article->save();

            return $article;
        });
    }
}
