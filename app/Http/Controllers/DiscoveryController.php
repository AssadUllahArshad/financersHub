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
                }
                foreach (Article::published()->select(['id', 'slug', 'updated_at'])->lazyById() as $article) {
                    $emit(route('articles.show', $article->slug, false), $article->updated_at->toAtomString());
                }
                foreach (Category::whereHas('articles', fn ($query) => $query->published())->lazyById() as $category) {
                    $emit(route('categories.show', $category->slug, false));
                }
                foreach (AuthorProfile::where('is_demo', false)->whereHas('articles', fn ($query) => $query->published())->lazyById() as $author) {
                    $emit(route('authors.show', $author->slug, false));
                }
            }
            echo '</urlset>';
        }, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(PublicationSeo $seo)
    {
        $body = config('financershub.design_preview') ? "User-agent: *\nDisallow: /\n" : "User-agent: *\nDisallow: /admin\nDisallow: /login\nDisallow: /search\nSitemap: ".$seo->url('/sitemap.xml')."\n";

        return response($body)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
