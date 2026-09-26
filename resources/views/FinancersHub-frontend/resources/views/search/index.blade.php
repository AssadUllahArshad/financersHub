@extends('layouts.site')
@section('title', 'Search | FinancersHub')
@section('content')
<div class="wrap section"><h1>Search FinancersHub</h1><form class="searchbar" method="get" action="{{ url('/search') }}"><input class="field" name="q" value="{{ $query ?? '' }}" aria-label="Search articles"><button class="btn">Search</button></form><div class="results">@forelse($articles as $article)<article class="result"><span class="eyebrow">{{ $article['category'] }}</span><h3><a href="{{ url('/articles/'.$article['slug']) }}">{{ $article['title'] }}</a></h3><p>{{ $article['excerpt'] }}</p></article>@empty<div class="empty">No matching articles. Try a broader term.</div>@endforelse</div></div>
@endsection
