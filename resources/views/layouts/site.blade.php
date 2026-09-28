<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
@include('partials.seo')
<meta name="color-scheme" content="light dark">
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">




<script src="{{ asset('assets/site.js') }}" defer></script>
@include('partials.fonts')
@include('partials.styles',['group'=>'publication'])
</head><body id="top">
@include('partials.header')
<main id="main" class="@yield('main-class')">@yield('content')</main>
@include('partials.footer')
</body></html>
