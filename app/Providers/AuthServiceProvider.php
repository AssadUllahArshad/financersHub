<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [\App\Models\Article::class => \App\Policies\ArticlePolicy::class];

    public function boot(): void
    {
        Gate::define('studio', fn ($user) => in_array($user->role, ['admin', 'editor', 'author'], true));
        Gate::define('manage-content', fn ($user) => in_array($user->role, ['admin', 'editor'], true));
        Gate::define('manage-settings', fn ($user) => $user->role === 'admin');
    }
}
