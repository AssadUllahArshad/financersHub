@extends('layouts.site')
@section('main-class','wrap')
@section('title',$category->name)
@section('description',$category->description ?? '')
@section('content')
<nav class="bread" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / <span aria-current="page">{{ $category->name }}</span></nav>
<section class="category-intro {{ $articles->first()?->mediaAsset ? '' : 'no-feature-image' }}"><div><span class="eyebrow">TOPIC / {{ $category->name }}</span><h1>{{ $category->name }}</h1><p>{{ $category->description }}</p>
@if($articles->isNotEmpty())
<a class="text-link" href="{{ route('articles.show',$articles->first()->slug) }}">Start with a guide ↗</a>
@endif
</div>
@if($articles->first()?->mediaAsset)
<div class="art blue"><img src="{{ route('media.show',$articles->first()->media_asset_id) }}" alt="{{ $articles->first()->mediaAsset->alt_text }}" width="800" height="500"></div>
@endif
</section>
<div class="content-grid section"><div><section class="category-featured"><div class="section-head"><div><span class="eyebrow">START HERE</span><h2>Guides to {{ strtolower($category->name) }}</h2></div></div><div class="card-grid">
@forelse($articles as $item)
@include('publication.card')
@empty
<p>Guides for this topic will appear when they are published.</p>
@endforelse
</div>{{ $articles->links('pagination.simple') }}</section></div><aside><div class="sidebar-box"><h3>Browse all topics</h3><div class="topics">
@foreach($categories as $topic)
<a class="pill {{ $topic->id===$category->id ? 'active' : '' }}" href="{{ route('categories.show',$topic->slug) }}">{{ $topic->name }}</a>
@endforeach
</div></div></aside></div>
@endsection
