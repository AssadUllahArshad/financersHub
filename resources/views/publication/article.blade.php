@extends('layouts.site')
@section('title',$article->seo_title ?: $article->title)
@section('description',$article->seo_description ?: $article->excerpt)
@section('main-class','article-page')
@section('content')
@php($reading = app(\App\Services\ArticleHtml::class)->prepare($article->body))
<div class="wrap"><nav class="bread" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('categories.show',$article->category->slug) }}">{{ $article->category->name }}</a></nav>
@if($preview ?? false)<p class="admin-sample">Private editorial preview · {{ $article->status }} · not a public publication.</p>@endif
<div class="article-layout"><article><header class="article-header"><span class="eyebrow">{{ $article->category->name }} / Guide</span><h1>{{ $article->title }}</h1><p class="deck">{{ $article->excerpt }}</p><div class="byline"><span class="avatar" aria-hidden="true">{{ mb_substr($article->authorProfile->name,0,1) }}</span><div><a href="{{ route('authors.show',$article->authorProfile->slug) }}"><strong>{{ $article->authorProfile->name }}</strong></a><br><span class="meta">@if($article->published_at)Published <time datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->format('F j, Y') }}</time> · Updated {{ $article->updated_at->format('F j, Y') }}@else Unpublished draft
@endif</span></div></div></header>
<div class="share"><button class="btn ghost" type="button" data-copy-link>Copy link</button></div>
@if($article->mediaAsset)<figure><img src="{{ route('media.show',$article->media_asset_id) }}" alt="{{ $article->mediaAsset->alt_text }}" width="1200" height="675" fetchpriority="high"></figure>@endif
<div class="prose">{!! $reading['html'] !!}
@if($article->sources)<section class="article-source"><span class="eyebrow">SOURCES &amp; FURTHER READING</span><h2>Check the primary sources</h2><ul>@foreach($article->sources as $source)<li><a href="{{ $source }}" rel="noopener noreferrer">{{ $source }}</a></li>@endforeach</ul></section>@endif</div>
<div class="disclosure">{{ $article->disclosure ?: 'General education only; not personalized financial, investment, tax, or legal advice.' }}</div>
<section class="article-author-card"><span class="avatar" aria-hidden="true">{{ mb_substr($article->authorProfile->name,0,1) }}</span><div><span class="eyebrow">ABOUT THE AUTHOR</span><h2>{{ $article->authorProfile->name }}</h2><p>{{ $article->authorProfile->bio }}</p></div></section>
@if($related->isNotEmpty())<section class="section article-more"><h2>Continue reading</h2><div class="article-related-grid">@foreach($related as $item)@include('publication.card')@endforeach</div></section>@endif
</article><aside><div class="sidebar-box toc"><h3>On this page</h3>
@foreach($reading['headings'] as $heading)
<a href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a>
@endforeach
<a href="{{ route('search') }}">Browse all published guides ↗</a></div></aside></div></div>
@endsection
