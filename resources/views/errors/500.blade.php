@extends('layouts.site')
@section('title', __('Something went wrong'))
@section('main-class', 'wrap section empty')
@section('content')
    <span class="eyebrow">500</span>
    <h1>{{ __('Something went wrong') }}</h1>
    <p>{{ __('Please try again shortly.') }}</p><a class="btn" href="{{ \App\Support\Localization::route('home') }}">{{ __('Return home') }}</a>
@endsection
