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
<section class="publication-values wrap" aria-label="About our guides"><div><span class="eyebrow">READ WITH CONTEXT</span><h2>Make informed decisions.</h2><p>Our guides explain financial topics. They do not replace advice tailored to your circumstances.</p></div><nav aria-label="Publication information"><a href="{{ route('tools.compound-interest') }}">Savings calculator &nearr;</a><a href="{{ route('editorial-policy') }}">Editorial standards &nearr;</a><a href="{{ route('authors.index') }}">Meet the authors &nearr;</a><a href="{{ route('contact') }}">Questions &amp; corrections &nearr;</a></nav></section>
@include('partials.footer')
</body></html>
