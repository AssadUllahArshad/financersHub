@extends('layouts.studio')
@section('live','1')
@section('title', $article->exists ? 'Edit article' : 'Write article')
@section('content')
<div class="studio-heading"><div><span class="admin-kicker">EDITORIAL STUDIO</span><h1>@yield('title')</h1><p>Save your work, review sources, then submit for editorial review.</p></div></div>
@if(!$authors->count() || !$categories->count())<p class="admin-sample">Create an author profile and category before saving an article.</p>@endif
<form method="post" action="{{ $article->exists ? route('admin.articles.update',$article) : route('admin.articles.store') }}">@csrf
@if($article->exists)@method('put')@endif
<input type="hidden" name="revision_id" value="{{ $article->exists ? $article->revisions()->max('id') : '' }}">
<div class="editor-status"><span class="status status-blue">{{ $article->status }}</span><div>@if($article->exists)<a class="a-button secondary" href="{{ route('admin.articles.preview',$article) }}">Preview saved article</a>@endif <button class="a-button primary" @disabled($article->exists && !auth()->user()->can('update',$article))>Save article</button></div></div>
<div class="editing-layout"><div class="editing-main">
<section class="studio-panel story-setup"><label class="admin-field"><span>Headline</span><input class="headline-input" id="editor-title" name="title" required maxlength="255" value="{{ old('title',$article->title) }}"></label>
<label class="admin-field"><span>URL slug</span><input name="slug" required pattern="[a-z0-9]+(-[a-z0-9]+)*" value="{{ old('slug',$article->slug) }}"></label>
<label class="admin-field"><span>Short description</span><textarea id="editor-summary" name="excerpt" required rows="3">{{ old('excerpt',$article->excerpt) }}</textarea></label></section>
<section class="studio-panel editor-panel"><div class="editor-panel-top"><span class="admin-kicker">ARTICLE BODY</span></div><p id="tinymce-status" role="status">HTML editing is available. A configured Tiny Cloud key enables the visual editor.</p><label class="sr-only" for="article-body">Article HTML</label><textarea id="article-body" name="body" rows="20" required data-tinymce-key="{{ config('financershub.tinymce_api_key') }}">{{ old('body',$article->body) }}</textarea><div class="editor-bottom"><span>Scripts, styles and embeds are removed on save.</span><span id="word-count"></span></div></section>
<section class="studio-panel"><label class="admin-field"><span>Primary source URLs (one per line)</span><textarea name="sources" rows="4">{{ old('sources',implode("\n",$article->sources ?? [])) }}</textarea></label><label class="admin-field"><span>Disclosure</span><textarea name="disclosure">{{ old('disclosure',$article->disclosure) }}</textarea></label></section>
<section class="studio-panel"><label class="admin-field"><span>SEO title</span><input name="seo_title" value="{{ old('seo_title',$article->seo_title) }}"></label><label class="admin-field"><span>SEO description</span><textarea name="seo_description">{{ old('seo_description',$article->seo_description) }}</textarea></label></section>
</div><aside class="editing-side"><section class="studio-panel side-module">
<label class="admin-field"><span>Author</span><select name="author_profile_id" required><option value="">Choose author</option>@foreach($authors as $author)@if(auth()->user()->role !== 'author' || $author->user_id === auth()->id())<option value="{{ $author->id }}" @selected(old('author_profile_id',$article->author_profile_id)==$author->id)>{{ $author->name }}</option>@endif
@endforeach</select></label>
<label class="admin-field"><span>Category</span><select name="category_id" required><option value="">Choose category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id',$article->category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select></label>
<label class="admin-field"><span>Tags</span><select name="tags[]" multiple>@foreach($tags as $tag)<option value="{{ $tag->id }}" @selected(in_array($tag->id,old('tags',$article->exists ? $article->tags->modelKeys() : [])))>{{ $tag->name }}</option>@endforeach</select></label>
<label class="admin-field"><span>Featured image</span><select name="media_asset_id"><option value="">No image</option>@foreach($media as $image)<option value="{{ $image->id }}" @selected(old('media_asset_id',$article->media_asset_id)==$image->id)>{{ $image->original_name }} — {{ $image->alt_text }}</option>@endforeach</select></label>
</section></aside></div></form>
@if($article->exists)
<section class="studio-panel"><h2>Editorial workflow</h2><p>Publishing and scheduling require review, primary sources and a verified author. Schedule times use UTC.</p>
<form method="post" action="{{ route('admin.articles.transition',$article) }}">@csrf
<label class="admin-field">Next status<select name="status">@foreach(['draft','in-review','scheduled','published','unpublished'] as $state)<option value="{{ $state }}">{{ $state }}</option>@endforeach</select></label>
<label class="admin-field">Schedule (UTC)<input type="datetime-local" name="scheduled_at"></label><button class="a-button primary">Change status</button></form></section>
<section class="studio-panel"><h2>Revision history</h2>@foreach($article->revisions()->latest('id')->get() as $revision)<div class="revision-row"><span>{{ $revision->created_at }} · {{ $revision->action }}</span>@can('update',$article)<form method="post" action="{{ route('admin.articles.revision',[$article,$revision]) }}">@csrf<button class="a-button secondary">Restore as draft</button></form>@endcan</div>@endforeach</section>
@can('delete',$article)<form method="post" action="{{ route('admin.articles.destroy',$article) }}">@csrf @method('delete')<button class="a-button secondary">Move to trash</button></form>@endcan
@endif
@endsection
