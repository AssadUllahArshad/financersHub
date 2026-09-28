@extends('layouts.studio')
@section('live','1')
@section('title','Articles')
@section('content')
<div class="studio-heading"><div><span class="admin-kicker">EDITORIAL STUDIO</span><h1>Articles</h1><p>Manage drafts, reviews and published guides.</p></div><a class="a-button primary" href="{{ route('admin.editor') }}">Write article</a></div>
<nav class="admin-tabs" aria-label="Article collection"><a class="{{ !request('trash') ? 'active' : '' }}" href="{{ route('admin.articles') }}">Article library</a><a class="{{ request('trash') ? 'active' : '' }}" href="{{ route('admin.articles',['trash'=>1]) }}">Trash</a></nav>
<form method="get" class="studio-panel admin-toolbar">
@if(request('trash'))<input type="hidden" name="trash" value="1">@endif
<label class="admin-field">Search<input type="search" name="q" value="{{ request('q') }}" placeholder="Search article titles"></label><label class="admin-field">Status<select name="status"><option value="">All statuses</option>@foreach(['draft','in-review','scheduled','published','unpublished'] as $state)<option value="{{ $state }}" @selected(request('status') === $state)>{{ ucfirst(str_replace('-',' ',$state)) }}</option>@endforeach</select></label>
<label class="admin-field">Category<select name="category"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category')==$category->id)>{{ $category->name }}</option>@endforeach</select></label>
<label class="admin-field">Sort by<select name="sort"><option value="">Recently updated</option><option value="title" @selected(request('sort')==='title')>Title A to Z</option></select></label><div class="toolbar-actions"><button class="a-button primary">Apply filters</button><a class="a-button secondary" href="{{ route('admin.articles',request('trash') ? ['trash'=>1] : []) }}">Reset</a></div></form>
<p class="admin-results">{{ $articles->total() }} {{ Str::plural('article',$articles->total()) }}{{ request('trash') ? ' in trash' : ' found' }}</p>
<div class="studio-panel"><div class="studio-table-wrap"><table class="admin-table"><thead><tr><th>Article</th><th>Author</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse($articles as $article)<tr><td><strong>{{ $article->title }}</strong><small class="record-caption">{{ $article->category->name }}</small> @if($article->is_demo)<small>Demo</small>@endif</td><td>{{ $article->authorProfile->name }}</td><td><span class="status-pill {{ $article->status === 'published' ? 'success' : 'neutral' }}">{{ ucfirst(str_replace('-',' ',$article->status)) }}</span></td><td>
@if($article->trashed())<form method="post" action="{{ route('admin.articles.restore',$article->id) }}">@csrf<button class="a-button secondary">Restore as draft</button></form>
@else<a href="{{ route('admin.articles.edit',$article) }}" class="a-button secondary compact">Edit</a> · <a href="{{ route('admin.articles.preview',$article) }}" class="a-button secondary compact">Preview</a>@endif
</td></tr>@empty<tr><td colspan="4">No articles match. Create a draft to get started.</td></tr>@endforelse
</tbody></table></div>{{ $articles->links('pagination.simple') }}</div>
@endsection
