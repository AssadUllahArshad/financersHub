@extends('layouts.site')
@section('title', 'Page not found')
@section('main-class', 'wrap section empty')
@section('content')
<span class="eyebrow">404</span><h1>Page not found</h1><p>That address may have changed. Search the publication or return to the homepage.</p><a class="btn" href="{{ route('home') }}">Return home</a>
@endsection
