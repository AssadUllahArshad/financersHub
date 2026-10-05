<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Services\PublicationSeo;

class DiscoveryController extends Controller
{
    public function sitemap(PublicationSeo $seo)
    {
        return response()->stream(function () use ($seo) {
            echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $emit = function (string $path, ?string $modified = null) use ($seo) {
                echo '<url><loc>'.htmlspecialchars($seo->url($path), ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc>';
                if ($modified) {
                    echo '<lastmod>'.$modified.'</lastmod>';
                }
                echo '</url>';
            };
            if (! config('financershub.design_preview')) {
                foreach (['home', 'about', 'contact', 'faq', 'authors.index', 'editorial-policy', 'disclaimer', 'privacy', 'terms'] as $route) {
                    $emit(route($route, [], false));
                    $emit(route('es.'.$route, [], false));
                }
                $emit(route('tools.compound-interest', [], false));
                $emit(route('es.tools.compound-interest', [], false));
                foreach (Article::published()->with('translations')->select(['id', 'slug', 'updated_at'])->lazyById() as $article) {
                    $emit(route('articles.show', $article->slug, false), $article->updated_at->toAtomString());
                    foreach ($article->translations->where('is_published', true)->where('locale', 'es') as $translation) {
                        $emit(route('es.articles.show', $article->slug, false), $translation->updated_at->max($article->updated_at)->toAtomString());
                    }
                }
                foreach (Category::whereHas('articles', fn ($query) => $query->published())->lazyById() as $category) {
                    $emit(route('categories.show', $category->slug, false));
                    $emit(route('es.categories.show', $category->slug, false));
                }
                foreach (AuthorProfile::where('is_demo', false)->where(fn ($query) => $query->where('schema_type', 'Organization')->orWhereHas('articles', fn ($articles) => $articles->published()))->lazyById() as $author) {
                    $emit(route('authors.show', $author->slug, false));
                    $emit(route('es.authors.show', $author->slug, false));
                }
            }
            echo '</urlset>';
        }, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(PublicationSeo $seo)
    {
        $body = (config('financershub.design_preview') || !config('financershub.search_indexing_enabled')) ? "User-agent: *\nDisallow: /\n" : "User-agent: *\nDisallow: /admin\nDisallow: /login\nDisallow: /search\nSitemap: ".$seo->url('/sitemap.xml')."\n";

        return response($body)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
