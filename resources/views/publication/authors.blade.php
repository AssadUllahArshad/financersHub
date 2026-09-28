@extends('layouts.site')
@section('main-class','wrap author-page')
@section('title','Authors')
@section('content')
<section class="page-hero"><span class="eyebrow">THE PUBLICATION</span><h1>Our authors</h1><p>Meet the contributors behind our published guides.</p></section>
<div class="card-grid section">
@forelse($authors as $author)
<article class="card"><div class="body"><h2><a href="{{ route('authors.show',$author->slug) }}">{{ $author->name }}</a></h2><p>{{ $author->bio }}</p></div></article>
@empty
<p>Contributor profiles will appear when guides are published.</p>
@endforelse
</div>
@endsection
