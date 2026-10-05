@extends('layouts.studio')
@section('live', '1')
@section('title', 'Spanish translation')
@section('content')
    <div class="studio-heading"><div><h1>Spanish translation</h1><p>{{ $article->title }}</p></div></div>
    <p><a class="a-button secondary" href="{{ route('admin.articles.edit', $article) }}">Back to English article</a></p>
    <form method="post" action="{{ route('admin.articles.translations.update', [$article, $locale]) }}">
        @csrf @method('put')
        <input type="hidden" name="version" value="{{ old('version', $translation?->version ?? 0) }}">
        <div class="composer-grid">
            <div class="composer-main">
                <section class="composer-card">
                    <h2>Spanish content</h2>
                    <p class="small-muted">The English article remains the default. The same categories, author, featured image, and sources apply to both languages.</p>
                    <label class="admin-field">Title<input class="form-control" lang="es" name="title" maxlength="255" value="{{ old('title', $translation?->title) }}" required></label>
                    <label class="admin-field">Excerpt<textarea class="form-control" lang="es" name="excerpt" maxlength="1000" required>{{ old('excerpt', $translation?->excerpt) }}</textarea></label>
                    <label for="article-body">Body</label>
                    <p id="tinymce-status" role="status">Loading visual editor…</p>
                    <textarea class="form-control" lang="es" id="article-body" name="body" rows="20" data-tinymce-key="{{ config('financershub.tinymce_api_key') }}" required>{{ old('body', $translation?->body) }}</textarea>
                    <small id="word-count"></small>
                </section>
                <section class="composer-card"><h2>Search and image details</h2>
                    <label class="admin-field">SEO title<input class="form-control" lang="es" name="seo_title" maxlength="255" value="{{ old('seo_title', $translation?->seo_title) }}"></label>
                    <label class="admin-field">SEO description<textarea class="form-control" lang="es" name="seo_description" maxlength="300">{{ old('seo_description', $translation?->seo_description) }}</textarea></label>
                    <label class="admin-field">Featured image alt text<input class="form-control" lang="es" name="image_alt" maxlength="255" value="{{ old('image_alt', $translation?->image_alt) }}"></label>
                    <label class="admin-field">Disclosure<textarea class="form-control" lang="es" name="disclosure" maxlength="3000">{{ old('disclosure', $translation?->disclosure) }}</textarea></label>
                </section>
            </div>
            <aside><section class="composer-card"><h2>Translation status</h2>
                <p>Spanish is shown only when this translation is enabled and the English article is publicly published. Otherwise readers see English.</p>
                <input type="hidden" name="is_published" value="0">
                @can('publish', $article)
                    <label class="check-line"><input class="form-check-input" type="checkbox" name="is_published" value="1" @checked(old('is_published', $translation?->is_published))> Publish Spanish translation</label>
                @else
                    <p>Your translation will be saved as a draft for editorial review.</p>
                @endcan
                <button class="a-button primary" type="submit"><x-icon name="save" /> Save translation</button>
            </section></aside>
        </div>
    </form>
@endsection
