<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\FaqEntry;
use App\Models\SiteSetting;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    private function model(string $kind): string
    {
        return match ($kind) {
            'categories' => Category::class,'tags' => Tag::class,'authors' => AuthorProfile::class,'faq' => FaqEntry::class,default => abort(404)
        };
    }

    public function index(Request $request, string $kind)
    {
        $this->authorize('manage-content');
        $model = $this->model($kind);
        if ($request->filled('edit')) {
            return redirect()->route('admin.content.edit', [$kind, $request->integer('edit')]);
        }
        $query = $model::query();
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 200);
        if ($search !== '') {
            $query->where($kind === 'faq' ? 'question' : 'name', 'like', '%'.$search.'%');
        }
        if ($kind === 'faq' && in_array($request->query('status'), ['published', 'draft'], true)) {
            $query->where('published', $request->query('status') === 'published');
        }
        if ($kind !== 'faq') {
            $query->withCount(['articles' => fn ($q) => $q->withTrashed()]);
        }
        $sort = $request->query('sort') === 'newest' ? 'id' : ($kind === 'faq' ? 'position' : 'name');
        $query->orderBy($sort, $sort === 'id' ? 'desc' : 'asc');

        return view('cms.content', ['kind' => $kind, 'records' => $query->paginate(15)->withQueryString(), 'total' => $model::count()]);
    }

    public function form(string $kind, ?int $id = null)
    {
        $this->authorize('manage-content');
        $model = $this->model($kind);
        $editing = $id ? $model::findOrFail($id) : null;
        $users = collect();
        if ($kind === 'authors') {
            $users = \App\Models\User::whereIn('role', ['admin', 'editor', 'author'])
                ->where(fn ($q) => $q->whereDoesntHave('authorProfile')->when($editing?->user_id, fn ($q) => $q->orWhere('id', $editing->user_id)))->orderBy('name')->get(['id', 'name', 'role']);
        }

        return view('cms.content-form', compact('kind', 'editing', 'users'));
    }

    public function save(Request $request, string $kind, ?int $id = null)
    {
        $this->authorize('manage-content');
        $model = $this->model($kind);
        $record = $id ? $model::findOrFail($id) : new $model;
        $rules = $kind === 'faq' ? ['group' => 'sometimes|required|in:using-financershub,newsletter-and-contact,privacy-and-editorial-standards', 'question' => 'required|string|max:255', 'answer' => 'required|string|max:10000', 'position' => 'required|integer|min:0|max:10000', 'published' => 'boolean'] : ['name' => 'required|string|max:255', 'slug' => ['required', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:200', Rule::unique($record->getTable())->ignore($record->id)], 'description' => 'nullable|string|max:5000'];
        if ($kind === 'authors') {
            $rules = ['name' => $rules['name'], 'slug' => $rules['slug'], 'bio' => 'nullable|string|max:5000', 'user_id' => ['nullable', Rule::exists('users', 'id')->whereIn('role', ['admin', 'editor', 'author']), Rule::unique('author_profiles')->ignore($record->id)]];
        }
        $data = $request->validate($rules);
        if ($kind === 'faq') {
            $data['published'] = $request->boolean('published');
        }
        if ($record->exists && $kind !== 'faq' && $record->slug !== $data['slug'] && $this->referenced($kind, $record)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['slug' => 'This URL is used by articles and must remain stable.']);
        }
        $record->fill($data)->save();

        return redirect()->route('admin.'.$kind)->with('status', 'Content saved.');
    }

    private function referenced(string $kind, $record): bool
    {
        if ($kind === 'faq') {
            return false;
        }
        if ($kind === 'tags') {
            return $record->articles()->withTrashed()->exists();
        }

        return Article::withTrashed()->where($kind === 'authors' ? 'author_profile_id' : 'category_id', $record->id)->exists();
    }

    public function destroy(string $kind, int $id)
    {
        $this->authorize('manage-content');
        $model = $this->model($kind);
        $record = $model::findOrFail($id);
        if ($this->referenced($kind, $record)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['content' => 'This item is used by an article, including articles in trash. Reassign references before deleting.']);
        }
        $record->delete();

        return back()->with('status', 'Content deleted.');
    }

    public function faq()
    {
        $faqs = FaqEntry::where('published', true)->orderBy('position')->orderBy('id')->get();
        if ($faqs->isEmpty() && config('financershub.design_preview') && ! FaqEntry::exists()) {
            return view('design.faq');
        }

        return view('publication.faq', compact('faqs'));
    }

    public function settings()
    {
        $this->authorize('manage-settings');

        return view('cms.settings', ['settings' => SiteSetting::pluck('value', 'key')]);
    }

    public function saveSettings(Request $request)
    {
        $this->authorize('manage-settings');
        $data = $request->validate(['contact_recipient' => ['nullable', 'email', 'max:254', 'not_regex:/[\r\n]/'], 'newsletter_title' => 'required|string|max:200', 'newsletter_description' => 'required|string|max:1000', 'footer_copy' => 'required|string|max:1000']);
        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('status', 'Site copy saved.');
    }
}
