@extends('layouts.site')
@section('title', __('Your session has expired'))
@section('main-class', 'wrap section empty')
@section('content')
    <span class="eyebrow">419</span>
    <h1>{{ __('Your session has expired') }}</h1>
    <p>{{ __('Reopen the form and try again. Your previous submission was not accepted.') }}</p><a class="btn"
        href="{{ \App\Support\Localization::route('home') }}">{{ __('Return home') }}</a>
@endsection
