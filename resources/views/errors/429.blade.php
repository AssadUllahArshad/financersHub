@extends('layouts.site')
@section('title', __('Too many requests'))
@section('main-class', 'wrap section empty')
@section('content')
    <span class="eyebrow">429</span>
    <h1>{{ __('Too many requests') }}</h1>
    <p>{{ __('Please wait a little before trying again.') }}</p><a class="btn" href="{{ \App\Support\Localization::route('home') }}">{{ __('Return home') }}</a>
@endsection
