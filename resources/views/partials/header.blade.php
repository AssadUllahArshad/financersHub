<a class="skip" href="#main">{{ __('Skip to content') }}</a>
<header class="masthead">
    <div class="wrap masthead-main"><a class="logo" href="{{ \App\Support\Localization::route('home') }}"><img
                src="{{ asset('assets/logo-primary.svg') }}" width="323" height="62" alt="FinancersHub"></a>
        <div class="actions">
            <nav class="language-switch" aria-label="{{ __('Language') }}">
                @foreach(config('localization.locales') as $code => $language)
                    <a href="{{ \App\Support\Localization::switchUrl($code) }}" lang="{{ $code }}" hreflang="{{ $code }}" @if(app()->getLocale() === $code) aria-current="true" @endif>{{ $code === 'en' ? 'EN' : 'ES' }}<span class="sr-only"> — {{ $language }}</span></a>
                @endforeach
            </nav>
            <form class="header-search" role="search" action="{{ \App\Support\Localization::route('search') }}"><label class="sr-only"
                    for="header-query">{{ __('Search guides') }}</label><input class="form-control" id="header-query" type="search"
                    name="q" placeholder="{{ __('Search guides') }}" /><button type="submit"
                    aria-label="{{ __('Search guides') }}"><x-icon name="search" /></button></form><button
                class="iconbtn theme-toggle" id="theme" type="button" aria-label="{{ __('Switch to dark mode') }}"
                title="{{ __('Switch to dark mode') }}"><x-icon name="moon" class="theme-moon" /><x-icon name="sun" class="theme-sun" /></button><button class="iconbtn menu-toggle"
                id="menu" type="button" aria-label="{{ __('Open menu') }}" aria-controls="mobile-nav"
                aria-expanded="false"><x-icon name="menu" /></button>
        </div>
    </div>
    <div class="nav-line">
        <div class="wrap nav-wrap">
            <nav class="nav" aria-label="{{ __('Topics and pages') }}"><a
                    href="{{ \App\Support\Localization::route('categories.personal-finance') }}">{{ __('Personal Finance') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.investing') }}">{{ __('Investing') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.banking') }}">{{ __('Banking') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.credit') }}">{{ __('Credit') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.business') }}">{{ __('Business') }}</a><a
                    href="{{ \App\Support\Localization::route('categories.saving') }}">{{ __('Saving') }}</a><a
                    href="{{ \App\Support\Localization::route('tools.compound-interest') }}">{{ __('Calculator') }}</a><a
                    href="{{ \App\Support\Localization::route('about') }}">{{ __('About') }}</a><a href="{{ \App\Support\Localization::route('faq') }}">{{ __('FAQs') }}</a><a
                    href="{{ \App\Support\Localization::route('contact') }}">{{ __('Contact') }}</a></nav><a class="nav-guide"
                href="{{ \App\Support\Localization::route('editorial-policy') }}">{{ __('How we write') }}<span aria-hidden="true">↗</span></a>
        </div>
    </div>
    <nav class="mobile-nav" id="mobile-nav" aria-label="{{ __('Mobile navigation') }}">
        <form class="mobile-search" role="search" action="{{ \App\Support\Localization::route('search') }}"><label class="sr-only"
                for="mobile-query">{{ __('Search guides') }}</label><input class="form-control" id="mobile-query" name="q"
                type="search" placeholder="{{ __('Search guides') }}"><button type="submit">{{ __('Search') }}</button></form><a
            href="{{ \App\Support\Localization::route('categories.personal-finance') }}">{{ __('Personal Finance') }}</a><a
            href="{{ \App\Support\Localization::route('categories.investing') }}">{{ __('Investing') }}</a><a
            href="{{ \App\Support\Localization::route('categories.banking') }}">{{ __('Banking') }}</a><a
            href="{{ \App\Support\Localization::route('categories.credit') }}">{{ __('Credit') }}</a><a
            href="{{ \App\Support\Localization::route('categories.business') }}">{{ __('Business') }}</a><a
            href="{{ \App\Support\Localization::route('categories.saving') }}">{{ __('Saving') }}</a><a
            href="{{ \App\Support\Localization::route('tools.compound-interest') }}">{{ __('Calculator') }}</a><a href="{{ \App\Support\Localization::route('about') }}">{{ __('About') }}</a><a
            href="{{ \App\Support\Localization::route('faq') }}">{{ __('FAQs') }}</a><a href="{{ \App\Support\Localization::route('contact') }}">{{ __('Contact') }}</a><a
            href="{{ \App\Support\Localization::route('editorial-policy') }}">{{ __('Editorial policy') }}</a>
    </nav>
</header>
