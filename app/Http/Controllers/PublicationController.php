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
        return Article::published()->with(['category', 'categories', 'authorProfile', 'mediaAsset', 'translations'])->latest('published_at');
    }

    public function home()
    {
        $articles = $this->query()->reorder()->orderByDesc('is_featured')->latest('published_at')->limit(12)->get();

        return view('publication.home', ['articles' => $articles, 'topics' => Category::orderBy('name')->get()]);
    }

    public function search(Request $request)
    {
        $filters = $request->validate(['q' => 'nullable|string|max:200', 'topic' => 'nullable|string|exists:categories,slug', 'sort' => 'nullable|in:newest,oldest,title']);
        $query = trim($filters['q'] ?? '');
        $topic = $filters['topic'] ?? '';
        $sort = $filters['sort'] ?? 'newest';
        $articles = $this->query()->when($query !== '', fn ($q) => $q->where(function ($q) use ($query) {
            $q->where('title', 'like', '%'.$query.'%')->orWhere('excerpt', 'like', '%'.$query.'%')->orWhere('body', 'like', '%'.$query.'%');
            if (app()->getLocale() !== 'en') {
                $q->orWhereHas('translations', fn ($translation) => $translation->where('locale', app()->getLocale())->where('is_published', true)->where(fn ($text) => $text->where('title', 'like', '%'.$query.'%')->orWhere('excerpt', 'like', '%'.$query.'%')->orWhere('body', 'like', '%'.$query.'%')));
            }
        }));
        if ($topic !== '') {
            $articles->inCategory(Category::where('slug', $topic)->value('id'));
        }
        $articles->reorder()->orderBy($sort === 'title' ? 'title' : 'published_at', $sort === 'newest' ? 'desc' : 'asc')->orderBy('id');

        return view('publication.library', ['articles' => $articles->paginate(12)->withQueryString(), 'heading' => 'Article library', 'description' => 'Find a clear starting point for your next money question.', 'query' => $query, 'topic' => $topic, 'sort' => $sort, 'topics' => Category::orderBy('name')->get()]);
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->first();
        abort_unless($category, 404);

        return view('publication.category', ['articles' => $this->query()->inCategory($category->id)->paginate(12), 'category' => $category, 'categories' => Category::orderBy('name')->get()]);
    }

    public function authors()
    {
        return view('publication.authors', ['authors' => AuthorProfile::where('is_demo', false)->where(fn ($query) => $query->where('schema_type', 'Organization')->orWhereHas('articles', fn ($articles) => $articles->published()))->orderBy('name')->get()]);
    }

    public function author(string $slug)
    {
        $author = AuthorProfile::where('slug', $slug)->where('is_demo', false)->first();
        abort_unless($author, 404);

        return view('publication.author', ['articles' => $this->query()->where('author_profile_id', $author->id)->paginate(12), 'author' => $author]);
    }

    public function article(string $slug)
    {
        $article = $this->query()->where('slug', $slug)->first();
        if (! $article) {
            $redirect = ArticleRedirect::where('slug', $slug)->first();
            if ($redirect && $redirect->article && $this->query()->whereKey($redirect->article_id)->exists()) {
                return redirect(\App\Support\Localization::route('articles.show', $redirect->article->slug), 301);
            }
            abort(404);
        }
        $categoryIds = $article->categories->pluck('id')->push($article->category_id)->unique()->all();
        $related = $this->query()->where(fn ($query) => $query->whereIn('category_id', $categoryIds)->orWhereHas('categories', fn ($category) => $category->whereIn('categories.id', $categoryIds)))->whereKeyNot($article->id)->limit(3)->get();

        return view('publication.article', compact('article', 'related'));
    }
}
