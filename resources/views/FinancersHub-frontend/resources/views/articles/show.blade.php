@extends('layouts.site')
@section('title', $article['title'].' | FinancersHub')
@section('description', $article['excerpt'])
@section('canonical', $article['canonical'] ?? url('/articles/'.$article['slug']))
@section('content')
<div class="wrap">
  <x-breadcrumbs :items="[['label'=>$article['category'],'url'=>url('/categories/'.$article['category_slug'])],['label'=>$article['title']]]" />
  <div class="article-layout"><article>
    <header class="article-header"><span class="eyebrow">{{ $article['category'] }} / Guide</span><h1>{{ $article['title'] }}</h1><p class="deck">{{ $article['excerpt'] }}</p><div class="byline"><span class="avatar" aria-hidden="true">{{ $article['author_initials'] ?? 'FH' }}</span><div><strong>{{ $article['author_name'] }}</strong><br><span class="meta">Published <time datetime="{{ $article['published_at']->toDateString() }}">{{ $article['published_at']->format('F j, Y') }}</time> · {{ $article['read_time'] }}</span></div></div></header>
    <div class="share"><button class="btn ghost" type="button" data-copy-link>Copy link</button></div>
    @if(!empty($article['hero_image']))<figure><img src="{{ asset($article['hero_image']) }}" alt="{{ $article['hero_alt'] }}" width="1200" height="675" fetchpriority="high"><figcaption class="caption">{{ $article['hero_caption'] ?? '' }}</figcaption></figure>@endif
    <div class="prose">@if(!empty($article['key_points']))<section class="article-takeaways" aria-label="Key points"><span class="eyebrow">THE SHORT VERSION</span><h2>What to take from this guide</h2><ul>@foreach($article['key_points'] as $point)<li><span>{{ sprintf('%02d',$loop->iteration) }}</span>{{ $point }}</li>@endforeach</ul></section>@endif{!! $article['sanitized_html'] !!}@if(!empty($article['primary_source_url']))<section class="article-source"><span class="eyebrow">SOURCE &amp; FURTHER READING</span><h2>Check the primary source</h2><p><a href="{{ $article['primary_source_url'] }}" target="_blank" rel="noopener noreferrer">{{ $article['primary_source_name'] ?? 'View source' }} ↗</a></p></section>@endif</div>
    <div class="disclosure">{{ $article['disclaimer'] ?? 'General education only; not personalized financial advice.' }}</div>
    <section class="article-author-card" aria-label="About the author"><span class="avatar" aria-hidden="true">{{ $article['author_initials'] ?? 'FH' }}</span><div><span class="eyebrow">ABOUT THE AUTHOR</span><h2>{{ $article['author_name'] }}</h2><p>{{ $article['author_bio'] ?? 'Verified author information will appear here.' }}</p></div></section>
    @if(!empty($related))<section class="section article-more"><div class="section-head"><div><span class="eyebrow">FROM THE LIBRARY</span><h2>Continue reading</h2></div></div><div class="article-related-grid">@foreach($related as $item)<x-article-card :article="$item" />@endforeach</div></section>@endif
  </article><aside><div class="sidebar-box toc"><h3>On this page</h3>@foreach(($article['toc'] ?? []) as $heading)<a href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a>@endforeach</div></aside></div>
</div>
@endsection
