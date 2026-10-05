<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Localization
{
    public static function route(string $name, mixed $parameters = [], bool $absolute = true, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $name = preg_replace('/^es\./', '', $name);
        if (preg_match('/^(categories|articles|authors)\.([a-z-]+)$/', $name, $match) && ! in_array($match[2], ['show', 'index'])) {
            $parameters = ['slug' => $match[2]];
            $name = $match[1].'.show';
        }
        if ($locale === 'es' && Route::has('es.'.$name)) {
            $name = 'es.'.$name;
        }

        return route($name, $parameters, $absolute);
    }

    public static function switchUrl(string $locale): string
    {
        $route = request()->route();
        $name = $route?->getName();
        $baseName = preg_replace('/^es\./', '', $name ?? '');
        $public = Route::has('es.'.$baseName) || preg_match('/^(categories|articles|authors)\./', $baseName);
        if (! $name || ! $public || ! Route::has($baseName)) {
            return self::route('home', [], true, $locale);
        }
        // Route::view also carries internal defaults (view, data, status).
        // Only the public resource slug belongs in generated URLs.
        $parameters = array_intersect_key($route->parameters(), ['slug' => true]);
        $url = self::route($name, $parameters, true, $locale);
        $query = request()->only(['q', 'topic', 'sort', 'page', 'subject', 'article']);

        return $url.($query ? '?'.http_build_query($query) : '');
    }
}
