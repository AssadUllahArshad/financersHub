<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleRedirect;
use App\Models\AuthorProfile;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    private function query()
    {
        return Article::published()->with(['category', 'authorProfile', 'mediaAsset'])->latest('published_at');
    }

    private function design(string $view)
    {
        return config('financershub.design_preview') && view()->exists('design.'.$view);
    }

    public function home()
    {
        $articles = $this->query()->limit(12)->get();
        if ($articles->isEmpty() && $this->design('index')) {
            return view('design.index');
        }

        return view('publication.home', compact('articles'));
    }

    public function search(Request $request)
    {
        if (! Article::published()->exists() && $this->design('search')) {
            return view('design.search');
        }
        $query = mb_substr(trim((string) $request->query('q', '')), 0, 200);
        $articles = $this->query()->when($query !== '', fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', '%'.$query.'%')->orWhere('excerpt', 'like', '%'.$query.'%')->orWhere('body', 'like', '%'.$query.'%')))->paginate(12)->withQueryString();

        return view('publication.library', ['articles' => $articles, 'heading' => 'Article library', 'description' => 'Search published guides.', 'query' => $query]);
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (! $category && $this->design('categories.'.$slug)) {
            return view('design.categories.'.$slug);
        }
        abort_unless($category, 404);

        return view('publication.category', ['articles' => $this->query()->where('category_id', $category->id)->paginate(12), 'category' => $category, 'categories' => Category::orderBy('name')->get()]);
    }

    public function authors()
    {
        return view('publication.authors', ['authors' => AuthorProfile::where('is_demo', false)->whereHas('articles', fn ($query) => $query->published())->orderBy('name')->get()]);
    }

    public function author(string $slug)
    {
        $author = AuthorProfile::where('slug', $slug)->where('is_demo', false)->first();
        if (! $author && $this->design('authors.'.$slug)) {
            return view('design.authors.'.$slug);
        }
        abort_unless($author, 404);

        return view('publication.author', ['articles' => $this->query()->where('author_profile_id', $author->id)->paginate(12), 'author' => $author]);
    }

    public function article(string $slug)
    {
        $article = $this->query()->where('slug', $slug)->first();
        if (! $article) {
            $redirect = ArticleRedirect::where('slug', $slug)->first();
            if ($redirect && $redirect->article && $this->query()->whereKey($redirect->article_id)->exists()) {
                return redirect()->route('articles.show', $redirect->article->slug, 301);
            }
            if (! Article::withTrashed()->where('slug', $slug)->exists() && $this->design('articles.'.$slug)) {
                return view('design.articles.'.$slug);
            }
            abort(404);
        }
        $related = $this->query()->where('category_id', $article->category_id)->whereKeyNot($article->id)->limit(3)->get();

        return view('publication.article', compact('article', 'related'));
    }
}
