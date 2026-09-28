@extends('layouts.site')
@section('title',$heading)
@section('description',$description ?? '')
@section('content')
<section class="wrap section publication-library"><span class="eyebrow">FINANCERSHUB LIBRARY</span><h1>{{ $heading }}</h1><p>{{ $description }}</p>
<form method="get" action="{{ route('search') }}" class="searchbar"><label class="sr-only" for="library-query">Search published guides</label><input class="field" type="search" name="q" id="library-query" value="{{ $query }}" placeholder="Search published guides"><button class="btn">Search</button></form>
<p class="meta" role="status">{{ $articles->total() }} {{ Str::plural('guide',$articles->total()) }}{{ $query !== '' ? ' matching ?'.$query.'?' : ' in the library' }}</p><div class="shelf-cards">@forelse($articles as $item)@include('publication.card')@empty<div class="empty"><h2>No published guides found.</h2><p>Try another search or return later.</p></div>@endforelse</div>{{ $articles->links('pagination.simple') }}</section>
@endsection
