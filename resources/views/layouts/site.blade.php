<!doctype html>
<html lang="{{ app()->getLocale() }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @include('partials.seo')
    <meta name="color-scheme" content="light dark">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">

    @vite('resources/js/site.js')
    @include('partials.fonts')
    @include('partials.styles', ['group' => 'publication'])
</head>

<body id="top">
    @include('partials.header')
    <main id="main" class="@yield('main-class')">@yield('content')</main>
    <section class="publication-values wrap" aria-label="{{ __('About our guides') }}">
        <div><span class="eyebrow">{{ __('READ WITH CONTEXT') }}</span>
            <h2>{{ __('Make informed decisions.') }}</h2>
            <p>{{ __('Our guides explain financial topics. They do not replace advice tailored to your circumstances.') }}</p>
        </div>
        <nav aria-label="{{ __('Publication information') }}"><a href="{{ \App\Support\Localization::route('tools.compound-interest') }}">{{ __('Savings calculator ↗') }}</a><a href="{{ \App\Support\Localization::route('editorial-policy') }}">{{ __('Editorial standards ↗') }}</a><a
                href="{{ \App\Support\Localization::route('authors.index') }}">{{ __('Meet the authors ↗') }}</a><a
                href="{{ \App\Support\Localization::route('contact') }}">{{ __('Questions & corrections ↗') }}</a></nav>
    </section>
    @include('partials.footer')
</body>

</html>
