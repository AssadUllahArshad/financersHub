@extends('layouts.site')
@section('title','Home')
@section('content')
<section class="wrap opening"><div class="opening-copy"><span class="eyebrow">THE FINANCERSHUB EDIT</span><h1>Understand money.<br><em>Move with confidence.</em></h1><p>Thoughtful guides on personal finance, investing, banking and the choices that shape your financial life.</p></div></section>
@if($articles->isNotEmpty())@php($lead=$articles->first())
<section class="wrap front-grid {{ $articles->count() === 1 ? 'single-story' : '' }}" aria-label="Featured stories"><article class="front-lead">@if($lead->mediaAsset)<a class="lead-image" href="{{ route('articles.show',$lead->slug) }}"><img src="{{ route('media.show',$lead->media_asset_id) }}" alt="{{ $lead->mediaAsset->alt_text }}" width="1600" height="1000" fetchpriority="high"></a>@endif<div class="lead-copy"><span class="eyebrow">THE LEAD / {{ $lead->category->name }}</span><h2><a href="{{ route('articles.show',$lead->slug) }}">{{ $lead->title }}</a></h2><p>{{ $lead->excerpt }}</p><a class="text-link" href="{{ route('articles.show',$lead->slug) }}">Read the guide ↗</a></div></article>@if($articles->count() > 1)<div class="front-rail"><div class="rail-heading"><span class="eyebrow">ALSO IN FOCUS</span></div>@foreach($articles->slice(1,2) as $item)@include('publication.card')@endforeach</div>@endif</section>
@if($articles->count() > 3)<section class="wrap section"><div class="section-head"><h2>Guides from the desk</h2><a href="{{ route('search') }}">The full library ↗</a></div><div class="shelf-cards">@foreach($articles->slice(3) as $item)@include('publication.card')@endforeach</div></section>
@endif
@else<section class="wrap section"><h2>The library is being prepared.</h2><p>Approved guides will appear here when they are published.</p></section>@endif
<section class="wrap library-strip"><h2>Find a guide for the question on your mind.</h2><a class="text-link" href="{{ route('search') }}">Browse all guides ↗</a></section>
@endsection
