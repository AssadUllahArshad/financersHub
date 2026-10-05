@extends('layouts.site')
@section('title', __('Access restricted'))
@section('main-class', 'wrap section empty')
@section('content')
    <span class="eyebrow">403</span>
    <h1>{{ __('Access restricted') }}</h1>
    <p>{{ __('You do not have permission to view this page.') }}</p><a class="btn" href="{{ \App\Support\Localization::route('home') }}">{{ __('Return home') }}</a>
@endsection
