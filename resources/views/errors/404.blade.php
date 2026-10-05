@extends('layouts.site')
@section('title', __('Page not found'))
@section('main-class', 'wrap section empty')
@section('content')
    <span class="eyebrow">404</span>
    <h1>{{ __('Page not found') }}</h1>
    <p>{{ __('That address may have changed. Search the publication or return to the homepage.') }}</p><a class="btn"
        href="{{ \App\Support\Localization::route('home') }}">{{ __('Return home') }}</a>
@endsection
