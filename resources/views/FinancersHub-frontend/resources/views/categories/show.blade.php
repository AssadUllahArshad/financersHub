@extends('layouts.site')
@section('title', $category['name'].' | FinancersHub')
@section('content')
<div class="wrap">
  <x-breadcrumbs :items="[['label'=>$category['name']]]" />
  <section class="category-intro"><div><span class="eyebrow">THE FINANCERSHUB LIBRARY / {{ strtoupper($category['name']) }}</span><h1>{{ $category['name'] }}</h1><p>{{ $category['description'] }}</p><a class="text-link" href="#articles">Start reading ↗</a></div><div class="art"><img src="{{ asset($category['image'] ?? 'assets/images/editorial-investing.webp') }}" width="800" height="500" alt="{{ $category['image_alt'] ?? '' }}"></div></section>
  <section id="articles" class="section"><div class="section-head"><div><span class="eyebrow">THE EDIT</span><h2>Featured in {{ $category['name'] }}</h2></div></div><div class="card-grid">@forelse($articles as $article)<x-article-card :article="$article" />@empty<div class="empty">No articles here yet.</div>@endforelse</div></section>
</div>
@endsection
