@extends('layouts.studio')
@section('section','articles')
@section('title','Articles')
@section('intro','Plan, review, and organize every story in one place.')
@section('content')
<div class="list-toolbar"><div class="segmented" role="group" aria-label="Article status"><button class="selected" data-status-filter="all">All articles</button><button data-status-filter="published">Published</button><button data-status-filter="draft">Drafts</button><button data-status-filter="in-review">In review</button><button data-status-filter="scheduled">Scheduled</button></div><a class="a-button primary" href="{{ url('/admin/articles/create') }}">＋ New article</a></div>
<section class="studio-panel list-panel"><div class="table-controls"><label class="table-search">⌕ <input id="admin-search" type="search" placeholder="Search titles or categories" aria-label="Search articles"></label></div><div class="table-scroll"><table class="studio-table"><thead><tr><th>Article</th><th>Category</th><th>Status</th><th>Updated</th><th></th></tr></thead><tbody>
@forelse($articles as $article)
<tr data-row-status="{{ $article['status_slug'] }}" data-row-search="{{ strtolower($article['title'].' '.$article['category']) }}"><td><a class="story-title" href="{{ url('/admin/articles/'.$article['id'].'/edit') }}">{{ $article['title'] }}</a></td><td>{{ $article['category'] }}</td><td><x-status-badge :tone="$article['status_tone'] ?? 'neutral'">{{ $article['status'] }}</x-status-badge></td><td>{{ $article['updated_label'] }}</td><td><a class="row-action" href="{{ url('/admin/articles/'.$article['id'].'/edit') }}">Edit ↗</a></td></tr>
@empty<tr><td colspan="5">No articles found.</td></tr>@endforelse
</tbody></table></div><div class="table-footer"><span id="article-count">Showing {{ count($articles) }} articles</span></div></section>
@endsection
