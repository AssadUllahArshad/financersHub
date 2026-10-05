@extends('layouts.site')
@section('title', __('Temporarily unavailable'))
@section('main-class', 'wrap section empty')
@section('content')
    <span class="eyebrow">503</span>
    <h1>{{ __('Temporarily unavailable') }}</h1>
    <p>{{ __('We are performing maintenance. Please try again shortly.') }}</p><a class="btn" href="{{ \App\Support\Localization::route('home') }}">{{ __('Return home') }}</a>
@endsection
