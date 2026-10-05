<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ArticleHtml;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ArticleTranslationController extends Controller
{
    public function edit(Article $article, string $locale)
    {
        $this->authorize('update', $article);
        abort_unless($locale === 'es', 404);

        return view('admin.translation', ['article' => $article, 'locale' => $locale, 'translation' => $article->translations()->where('locale', $locale)->first()]);
    }

    public function update(Request $request, Article $article, string $locale, ArticleHtml $html)
    {
        $this->authorize('update', $article);
        abort_unless($locale === 'es', 404);
        $data = $request->validate([
            'title' => 'required|string|max:255', 'excerpt' => 'required|string|max:1000',
            'body' => 'required|string|max:200000', 'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300', 'disclosure' => 'nullable|string|max:3000',
            'image_alt' => 'nullable|string|max:255', 'is_published' => 'required|boolean', 'version' => 'required|integer|min:0',
        ]);
        if ($request->boolean('is_published')) {
            $this->authorize('publish', $article);
        }
        $data['body'] = $html->clean($data['body']);
        if (trim(strip_tags($data['body'])) === '' && ! str_contains($data['body'], '<img ')) {
            throw ValidationException::withMessages(['body' => 'Add readable translated content before saving.']);
        }
        DB::transaction(function () use ($article, $locale, $data) {
            $locked = Article::whereKey($article->id)->lockForUpdate()->firstOrFail();
            $this->authorize('update', $locked);
            if ($data['is_published']) {
                $this->authorize('publish', $locked);
            }
            $translation = $article->translations()->where('locale', $locale)->first();
            abort_if(($translation?->version ?? 0) !== (int) $data['version'], 409, 'This translation changed. Reload before saving.');
            $data['version']++;
            $article->translations()->updateOrCreate(['locale' => $locale], $data);
        });

        return back()->with('status', 'Spanish translation saved. English content is unchanged.');
    }
}
