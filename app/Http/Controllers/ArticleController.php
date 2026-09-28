<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleRedirect;
use App\Models\ArticleRevision;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\MediaAsset;
use App\Models\Tag;
use App\Services\ArticleHtml;
use App\Services\ArticleWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['authorProfile', 'category']);
        if ($request->user()->role === 'author') {
            $query->where('user_id', $request->user()->id);
        }
        if ($request->boolean('trash')) {
            $query->onlyTrashed();
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.mb_substr($request->string('q'), 0, 200).'%');
        }
        if (in_array($request->query('status'), ['draft', 'in-review', 'scheduled', 'published', 'unpublished'], true)) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }
        if ($request->query('sort') === 'title') {
            $query->orderBy('title');
        } else {
            $query->latest('updated_at');
        }

        return view('cms.articles', ['articles' => $query->paginate(15)->withQueryString(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function editor(Request $request, ?Article $article = null)
    {
        $article ??= new Article(['status' => 'draft', 'body' => '', 'sources' => []]);
        $this->authorize($article->exists ? 'view' : 'create', $article->exists ? $article : Article::class);

        return view('cms.editor', ['article' => $article, 'authors' => AuthorProfile::orderBy('name')->get(), 'categories' => Category::orderBy('name')->get(), 'tags' => Tag::orderBy('name')->get(), 'media' => MediaAsset::latest()->get()]);
    }

    public function save(Request $request, ArticleHtml $html, ArticleWorkflow $workflow, ?Article $article = null)
    {
        $new = ! $article || ! $article->exists;
        if ($new) {
            $article = null;
        }
        $this->authorize($new ? 'create' : 'update', $article ?? Article::class);
        $data = $request->validate([
            'title' => 'required|string|max:255', 'slug' => ['required', 'string', 'max:200', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('articles')->ignore($article?->id)],
            'excerpt' => 'required|string|max:1000', 'body' => 'required|string|max:200000',
            'author_profile_id' => 'required|exists:author_profiles,id', 'category_id' => 'required|exists:categories,id', 'media_asset_id' => 'nullable|exists:media_assets,id',
            'tags' => 'array', 'tags.*' => 'integer|exists:tags,id', 'sources' => 'nullable|string|max:5000',
            'seo_title' => 'nullable|string|max:255', 'seo_description' => 'nullable|string|max:300', 'disclosure' => 'nullable|string|max:3000',
            'revision_id' => 'nullable|integer',
        ]);
        if ($request->user()->role === 'author') {
            abort_unless(AuthorProfile::whereKey($data['author_profile_id'])->where('user_id', $request->user()->id)->exists(), 403);
        }
        if (ArticleRedirect::where('slug', $data['slug'])->when($article, fn ($q) => $q->where('article_id', '!=', $article->id))->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['slug' => 'This URL is reserved by an earlier article.']);
        }
        $data['body'] = $html->clean($data['body']);
        $data['sources'] = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $data['sources'] ?? ''))));
        foreach ($data['sources'] as $source) {
            if (! filter_var($source, FILTER_VALIDATE_URL) || ! in_array(strtolower(parse_url($source, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true)) {
                throw \Illuminate\Validation\ValidationException::withMessages(['sources' => 'Each source must be an HTTP or HTTPS URL.']);
            }
        }
        $tags = $data['tags'] ?? [];
        $revision = $data['revision_id'] ?? null;
        unset($data['tags'], $data['revision_id']);
        $article = DB::transaction(function () use ($article, $new, $data, $tags, $revision, $request, $workflow) {
            if (! $new) {
                $article = Article::lockForUpdate()->findOrFail($article->id);
                $this->authorize('update', $article);
                abort_if((int) $article->revisions()->max('id') !== (int) $revision, 409, 'This article changed. Reload before saving.');
                if ($article->slug !== $data['slug'] && $article->published_at) {
                    ArticleRedirect::firstOrCreate(['slug' => $article->slug], ['article_id' => $article->id]);
                }
                $workflow->snapshot($article, $request->user(), 'before edit');
                $article->fill($data)->save();
            } else {
                $article = Article::create($data + ['user_id' => $request->user()->id, 'status' => 'draft']);
            }
            $article->tags()->sync($tags);
            $workflow->snapshot($article, $request->user(), $new ? 'created' : 'saved');

            return $article;
        });

        return redirect()->route('admin.articles.edit', $article)->with('status', 'Draft content saved to the database.');
    }

    public function transition(Request $request, Article $article, ArticleWorkflow $workflow)
    {
        $data = $request->validate(['status' => 'required|in:draft,in-review,scheduled,published,unpublished', 'scheduled_at' => 'nullable|date']);
        $workflow->transition($article, $request->user(), $data['status'], $data['scheduled_at'] ?? null);

        return back()->with('status', 'Article status updated.');
    }

    public function preview(Article $article)
    {
        $this->authorize('view', $article);

        return response()->view('publication.article', ['article' => $article->load(['category', 'authorProfile', 'mediaAsset', 'tags']), 'related' => collect(), 'preview' => true])->header('Cache-Control', 'private, no-store')->header('X-Robots-Tag', 'noindex');
    }

    public function destroy(Request $request, Article $article, ArticleWorkflow $workflow)
    {
        $this->authorize('delete', $article);
        DB::transaction(function () use ($article, $request, $workflow) {
            $workflow->snapshot($article, $request->user(), 'deleted');
            $article->delete();
        });

        return redirect()->route('admin.articles')->with('status', 'Article moved to trash.');
    }

    public function restore(Request $request, int $id, ArticleWorkflow $workflow)
    {
        $article = Article::onlyTrashed()->findOrFail($id);
        $this->authorize('delete', $article);
        DB::transaction(function () use ($article, $request, $workflow) {
            $article->status = 'draft';
            $article->scheduled_at = null;
            $article->restore();
            $workflow->snapshot($article, $request->user(), 'restored as draft');
        });

        return back()->with('status', 'Article restored as a private draft.');
    }

    public function revision(Request $request, Article $article, ArticleRevision $revision, ArticleHtml $html, ArticleWorkflow $workflow)
    {
        $this->authorize('update', $article);
        abort_unless($revision->article_id === $article->id, 404);
        DB::transaction(function () use ($article, $revision, $request, $html, $workflow) {
            $workflow->snapshot($article, $request->user(), 'before revision restore');
            $data = $revision->snapshot;
            $tags = $data['tags'] ?? [];
            unset($data['tags'], $data['slug']);
            \Illuminate\Support\Facades\Validator::make($data, [
                'author_profile_id' => 'required|exists:author_profiles,id', 'category_id' => 'required|exists:categories,id', 'media_asset_id' => 'nullable|exists:media_assets,id',
            ])->validate();
            $data['body'] = $html->clean($data['body']);
            $article->fill($data + ['status' => 'draft', 'scheduled_at' => null])->save();
            $article->tags()->sync($tags);
            $workflow->snapshot($article, $request->user(), 'revision restored as draft');
        });

        return back()->with('status', 'Revision restored as a private draft. Current URL retained.');
    }
}
