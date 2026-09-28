<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor', 'author'], true);
    }

    public function view(User $user, Article $article): bool
    {
        return in_array($user->role, ['admin', 'editor'], true) || ($user->role === 'author' && $article->user_id === $user->id);
    }

    public function update(User $user, Article $article): bool
    {
        return in_array($user->role, ['admin', 'editor'], true) || ($user->role === 'author' && $article->user_id === $user->id && in_array($article->status, ['draft', 'unpublished'], true));
    }

    public function publish(User $user, Article $article): bool
    {
        return in_array($user->role, ['admin', 'editor'], true) && ! $article->is_demo;
    }

    public function delete(User $user, Article $article): bool
    {
        return $this->update($user, $article);
    }
}
