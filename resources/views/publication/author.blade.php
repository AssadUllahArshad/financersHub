@extends('layouts.site')
@section('main-class','wrap author-page')
@section('title',$author->name)
@section('content')
<nav class="bread" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('authors.index') }}">Authors</a> / {{ $author->name }}</nav>
<header class="author-intro"><div class="author-large-avatar" aria-hidden="true">{{ mb_substr($author->name,0,1) }}</div><div><span class="eyebrow">AUTHOR PROFILE</span><h1>{{ $author->name }}</h1><p>{{ $author->bio }}</p><a class="text-link" href="{{ route('editorial-policy') }}">How we approach our guides ↗</a></div></header>
<section class="section author-work"><div class="section-head"><div><span class="eyebrow">PUBLISHED GUIDES</span><h2>Articles by {{ $author->name }}</h2></div></div><div class="card-grid">
@forelse($articles as $item)
@include('publication.card')
@empty
<p>No published guides yet.</p>
@endforelse
</div>{{ $articles->links('pagination.simple') }}</section>
@endsection
