@extends('layouts.studio')
@section('live', '1')
@section('title', $article->exists ? 'Edit article' : 'New article')
@section('content')
    @php
        $editable = !$article->exists || auth()->user()->can('update', $article);
        $canPublish = auth()->user()->can('manage-content') && !$article->is_demo;
        $states = match ($article->status) {
            'published' => ['published' => 'Published', 'unpublished' => 'Unpublished'],
            'scheduled' => [
                'scheduled' => 'Scheduled',
                'draft' => 'Draft',
                'published' => 'Published',
                'unpublished' => 'Unpublished',
            ],
            'in-review' => [
                'in-review' => 'In review',
                'draft' => 'Draft',
                'scheduled' => 'Scheduled',
                'published' => 'Published',
            ],
            default => [
                $article->status => ucfirst($article->status),
                'draft' => 'Draft',
                'in-review' => 'In review',
                'scheduled' => 'Scheduled',
                'published' => 'Published',
            ],
        };
        if (!$canPublish) {
            $states = array_intersect_key($states, array_flip([$article->status, 'in-review']));
        }
    @endphp
    <div class="composer-heading">
        <div><a class="admin-back" href="{{ route('admin.articles') }}">&larr; Articles</a>
            <h1>@yield('title')</h1>
            <p>Write with clarity. Review your sources. Publish with confidence.</p>
        </div><span class="status-pill">{{ ucfirst(str_replace('-', ' ', $article->status)) }}</span>
    </div>
    @if (!$authors->count() || !$categories->count())
        <p class="admin-sample">Create an author profile and category before saving.</p>
    @endif
    <form id="article-composer" class="article-composer" method="post"
        action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
        data-existing="{{ $article->exists ? '1' : '0' }}" data-current-status="{{ $article->status }}">
        @csrf @if ($article->exists)
            @method('put')
        @endif
        <input type="hidden" name="revision_id" value="{{ $article->exists ? $article->revisions()->max('id') : '' }}">
        <div class="composer-grid">
            <div class="composer-main">
                <section class="composer-card"><label class="admin-field"><span>Article title</span><input
                            class="form-control" id="editor-title" name="title" required maxlength="255"
                            placeholder="A clear, useful headline for your readers"
                            value="{{ old('title', $article->title) }}"><small>This becomes the page heading and the default
                            SEO title.</small></label></section>
                <section class="composer-card">
                    <h2>Content</h2><label class="admin-field">Excerpt
                        <textarea class="form-control" id="editor-summary" name="excerpt" required maxlength="1000" rows="3"
                            placeholder="A short summary for article cards and search results.">{{ old('excerpt', $article->excerpt) }}</textarea>
                    </label>
                    <div class="composer-body">
                        <h3>Body</h3>
                        <p id="tinymce-status" role="status">Loading visual editor. HTML editing is available while it
                            loads.</p><label class="sr-only" for="article-body">Article HTML</label>
                        <textarea class="form-control" id="article-body" name="body" rows="24" required
                            data-tinymce-key="{{ config('financershub.tinymce_api_key') }}">{{ old('body', $article->body) }}</textarea>
                        <div class="composer-tip"><span>Use headings, lists, tables and images. Upload images below with
                                their description and rights, then insert them from the image dialog. Scripts and embedded
                                players are removed on save.</span><span id="word-count"></span></div>
                    </div>
                </section>
                <section class="composer-card">
                    <h2>Featured image</h2><label class="admin-field">Choose from your media library<select
                            class="form-select" id="featured-media" name="media_asset_id">
                            <option value="">No featured image</option>
                            @foreach ($media as $image)
                                <option value="{{ $image->id }}" data-url="{{ asset('uploads/' . $image->path) }}"
                                    data-alt="{{ $image->alt_text }}" @selected(old('media_asset_id', $article->media_asset_id) == $image->id)>
                                    {{ $image->original_name }}</option>
                            @endforeach
                        </select>
                    </label><img id="featured-preview" class="composer-image" alt="" hidden>
                    @can('manage-content')
                        <details class="composer-upload">
                            <summary>Upload a new image</summary><label class="upload-area" id="image-dropzone"><span
                                    aria-hidden="true">&uarr;</span><strong>Choose an image or drag it here</strong><small>JPEG,
                                    PNG or WebP &middot; maximum 5 MB &middot; optimized on upload</small><input
                                    class="form-control" id="featured-file" type="file"
                                    accept="image/jpeg,image/png,image/webp"></label><label class="admin-field">Alternative
                                text<input class="form-control" id="upload-alt" maxlength="255"
                                    placeholder="Describe what the image shows"></label><label class="admin-field">Image
                                rights<input class="form-control" id="upload-rights" maxlength="3000"
                                    placeholder="Ownership or license information"></label><button type="button"
                                class="a-button secondary" id="upload-featured"
                                data-url="{{ route('admin.media.store') }}">Upload and select</button>
                            <p id="upload-status" role="status"></p>
                        </details>
                    @endcan
                </section>
                <section class="composer-card">
                    <h2>Sources &amp; disclosure</h2><label class="admin-field">Primary source URLs
                        <textarea class="form-control" name="sources" rows="3" placeholder="One https:// URL per line">{{ old('sources', implode("\n", $article->sources ?? [])) }}</textarea><small>Primary sources and verified authorship are required before
                            publishing.</small>
                    </label><label class="admin-field">Disclosure
                        <textarea class="form-control" name="disclosure" rows="2"
                            placeholder="Relevant relationships or context readers should know.">{{ old('disclosure', $article->disclosure) }}</textarea>
                    </label>
                </section>
                <section class="composer-card">
                    <h2>SEO &amp; metadata</h2><label class="admin-field">Meta title <small>Optional; defaults to the
                            article title</small><input class="form-control" id="seo-title" name="seo_title" maxlength="255"
                            value="{{ old('seo_title', $article->seo_title) }}"></label><label class="admin-field">Meta
                        description
                        <textarea class="form-control" id="seo-description" name="seo_description" maxlength="300" rows="3">{{ old('seo_description', $article->seo_description) }}</textarea><small id="seo-counter"></small>
                    </label>
                    <div class="composer-search-preview"><span>{{ request()->getHost() }} &rsaquo; articles &rsaquo; <span
                                id="search-preview-slug"></span></span>
                        <h3 id="search-preview-title">{{ $article->seo_title ?: $article->title ?: 'Your article title' }}
                        </h3>
                        <p id="search-preview-description">{{ $article->seo_description ?: $article->excerpt }}</p>
                    </div>
                </section>
            </div>
            <aside class="composer-side">
                <section class="composer-card">
                    <h2>Publish</h2><label class="admin-field">Status<select class="form-select" name="desired_status"
                            id="publish-status">
                            @foreach ($states as $value => $label)
                                <option value="{{ $value }}" @selected(old('desired_status', $article->status) === $value)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </label><label class="admin-field">Publish date (UTC)<input class="form-control"
                            type="datetime-local" id="publish-date" name="publish_date"
                            value="{{ old('publish_date', $article->scheduled_at?->format('Y-m-d\TH:i')) }}"><small>Choose
                            Scheduled and a future date to schedule publication.</small></label><label
                        class="admin-field">URL slug<input class="form-control" id="article-slug" name="slug"
                            required maxlength="200" pattern="[a-z0-9]+(-[a-z0-9]+)*"
                            value="{{ old('slug', $article->slug) }}"
                            placeholder="generated-from-your-title"><small>Generated while you type for new articles. You
                            can edit it.</small></label>
                    @if ($article->exists)
                        <a class="a-button secondary" href="{{ route('admin.articles.preview', $article) }}"
                            target="_blank" rel="noopener">Preview saved article &nearr;</a>
                    @endif
                </section>
                <section class="composer-card">
                    <h2>Organize</h2><label class="admin-field">Primary category<select class="form-select"
                            name="category_id" required>
                            <option value="">Choose a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $article->category_id) == $category->id)>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <fieldset class="composer-categories">
                        <legend>Additional categories</legend><input type="hidden" name="categories_present"
                            value="1">
                        <div>
                            @foreach ($categories as $category)
                                <label class="check-line"><input class="form-check-input" type="checkbox"
                                        name="categories[]" value="{{ $category->id }}"
                                        @checked(in_array(
                                                $category->id,
                                                old('categories', old('categories_present') ? [] : ($article->exists ? $article->categories->modelKeys() : []))))><span>{{ $category->name }}</span></label>
                            @endforeach
                        </div>
                        <small>The article appears in every selected category.</small>
                    </fieldset><label class="admin-field">Author<select class="form-select" name="author_profile_id"
                            required>
                            <option value="">Choose an author</option>
                            @foreach ($authors as $author)
                                @if (auth()->user()->role !== 'author' || $author->user_id === auth()->id())
                                    <option value="{{ $author->id }}" @selected(old('author_profile_id', $article->author_profile_id) == $author->id)>{{ $author->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </label><label class="admin-field">Tags<select class="form-select" name="tags[]" multiple>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $article->exists ? $article->tags->modelKeys() : [])))>{{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <div class="composer-reading"><span>Estimated reading time</span><strong
                            id="reading-estimate">{{ $article->reading_minutes }} min read</strong><small>Calculated
                            automatically from the article body.</small></div>
                </section>
                @can('manage-content')
                    <section class="composer-card">
                        <h2>Options</h2><input type="hidden" name="is_featured" value="0"><label
                            class="check-line"><input class="form-check-input" type="checkbox" name="is_featured"
                                value="1" @checked(old('is_featured', $article->is_featured))><span>Feature this article</span></label>
                        <p class="small-muted">Published featured articles take priority on the homepage. Drafts stay private.
                        </p>
                    </section>
                @endcan
            </aside>
        </div>
        <div class="composer-savebar"><span id="composer-save-status"
                role="status">{{ $article->exists ? 'Editing saved article' : 'New article' }}</span>
            <div><a class="a-button secondary" href="{{ route('admin.articles') }}">Cancel</a><button type="submit"
                    class="a-button secondary" id="save-article" name="save_action" value="save"
                    @disabled(!$editable)>{{ $article->exists ? 'Save changes' : 'Save as draft' }}</button><button
                    type="submit" class="a-button primary" id="apply-publication" name="save_action" value="apply"
                    @disabled(!$editable)>Save &amp; apply status</button></div>
        </div>
    </form>
    @if ($article->exists)
        @can('update', $article)
            <section class="composer-card"><h2>Article languages</h2><p>English is the default. Add Spanish content without changing the English article.</p><a class="a-button secondary" href="{{ route('admin.articles.translations.edit', [$article, 'es']) }}">Edit Spanish translation</a></section>
        @endcan
        <details class="composer-card composer-revisions">
            <summary>Revision history &middot; {{ $article->revisions()->count() }} saved versions</summary>
            @foreach ($article->revisions()->latest('id')->get() as $revision)
                <div class="revision-row"><span>{{ $revision->created_at }} &middot; {{ $revision->action }}</span>
                    @can('update', $article)
                        <form method="post" action="{{ route('admin.articles.revision', [$article, $revision]) }}">
                            @csrf<button class="a-button secondary">Restore as draft</button></form>
                    @endcan
                </div>
            @endforeach
        </details>
        @can('delete', $article)
            <form method="post" action="{{ route('admin.articles.destroy', $article) }}"
                data-confirm="Move this article to trash? It will no longer be public.">@csrf @method('delete')<button
                    class="a-button danger">Move to trash</button></form>
        @endcan
    @endif
    @vite('resources/js/composer.js')
@endsection
