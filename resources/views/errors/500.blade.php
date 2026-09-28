@extends('layouts.site')
@section('title', 'Something went wrong')
@section('main-class', 'wrap section empty')
@section('content')
<span class="eyebrow">500</span><h1>Something went wrong</h1><p>Please try again shortly.</p><a class="btn" href="{{ route('home') }}">Return home</a>
@endsection
