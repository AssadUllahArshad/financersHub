<?php

namespace App\Services;

use App\Models\Article;
use App\Models\SiteSetting;

class PublicationSeo
{
    public function url(string $path): string
    {
        return rtrim(config('app.url'), '/').'/'.ltrim($path, '/');
    }

    public function metadata(string $title, string $description, ?Article $article = null, bool $private = false): array
    {
        $settings = $private ? collect() : SiteSetting::whereIn('key', ['seo_site_name', 'seo_description'])->pluck('value', 'key');
        $name = $settings['seo_site_name'] ?? 'FinancersHub';
        $title = trim(strip_tags($title));
        $description = trim(strip_tags($description)) ?: ($settings['seo_description'] ?? 'Independent finance guides and practical explanations for better money decisions.');
        $noindex = ! config('financershub.search_indexing_enabled') || $private || config('financershub.design_preview') || request()->routeIs('search', 'es.search', '404', '500');
        $path = request()->getPathInfo();
        if (request()->integer('page') > 1) {
            $path .= '?page='.request()->integer('page');
        }
        $canonical = $this->url($path);
        if ($article && app()->getLocale() !== 'en' && ! $article->publicTranslation()) {
            $canonical = $this->url(route('articles.show', $article->slug, false));
        }
        $alternates = [];
        $route = request()->route();
        $routeName = preg_replace('/^es\./', '', $route?->getName() ?? '');
        if (preg_match('/^(categories|articles|authors)\.([a-z-]+)$/', $routeName, $match) && !in_array($match[2], ['show', 'index'])) {
            $routeName = $match[1].'.show';
        }
        if (! $private && \Illuminate\Support\Facades\Route::has('es.'.$routeName) && ! request()->routeIs('search', 'es.search')) {
            foreach (['en', 'es'] as $locale) {
                if ($article && $locale !== 'en' && ! $article->translations->contains(fn ($item) => $item->locale === $locale && $item->is_published)) {
                    continue;
                }
                $alternate = \App\Support\Localization::route($routeName, array_intersect_key($route->parameters(), ['slug' => true]), false, $locale);
                if (request()->integer('page') > 1) {
                    $alternate .= '?page='.request()->integer('page');
                }
                $alternates[$locale] = $this->url($alternate);
            }
            $alternates['x-default'] = $alternates['en'];
        }
        $published = $article && ! $noindex && $article->status === 'published' && ! $article->is_demo && $article->published_at?->lte(now()) && ! $article->trashed();
        $image = $published && $article->media_asset_id ? $this->url(route('media.show', $article->media_asset_id, false)) : null;
        $schema = null;
        $breadcrumb = null;
        if ($published) {
            $schema = ['@context' => 'https://schema.org', '@type' => 'Article', 'headline' => $article->title, 'description' => $description, 'mainEntityOfPage' => $canonical, 'datePublished' => $article->published_at->toIso8601String(), 'dateModified' => $article->updated_at->toIso8601String(), 'author' => ['@type' => $article->authorProfile->schema_type ?: 'Person', 'name' => $article->authorProfile->name, 'url' => $this->url(route('authors.show', $article->authorProfile->slug, false))], 'publisher' => ['@type' => 'Organization', 'name' => $name]];
            $breadcrumb = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $this->url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $article->category->name, 'item' => $this->url(route('categories.show', $article->category->slug, false))],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $article->title, 'item' => $canonical],
            ]];
            $schema['articleSection'] = __($article->category->name);
            $schema['inLanguage'] = $article->content_locale;
            if ($image) {
                $schema['image'] = [$image];
            }
        }

        return compact('name', 'description', 'canonical', 'noindex', 'published', 'image', 'schema', 'breadcrumb', 'alternates') + ['title' => ($title ? $title.' | ' : '').$name];
    }
}
