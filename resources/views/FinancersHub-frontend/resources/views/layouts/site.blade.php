<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'FinancersHub')</title>
  <meta name="description" content="@yield('description', 'Independent finance guides and practical explanations.')">
  @hasSection('canonical')<link rel="canonical" href="@yield('canonical')">@endif
  <link rel="icon" href="{{ asset('assets/favicon.svg') }}" type="image/svg+xml">
  <link rel="stylesheet" href="{{ asset('assets/site.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/editorial.css') }}">
  @stack('styles')
  @stack('head')
  <script src="{{ asset('assets/site.js') }}" defer></script>
</head>
<body id="top">
  <x-header />
  <main id="main">@yield('content')</main>
  <x-footer />
  @stack('scripts')
</body>
</html>
