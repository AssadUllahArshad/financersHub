@extends('layouts.site')
@section('title', __('Terms of use'))
@section('description', __('Read the conditions for using FinancersHub articles, illustrations and educational tools.'))
@section('main-class', 'wrap info-page info-terms')
@section('content')
    <x-breadcrumbs :items="[['label' => 'Terms of use']]" />
    <header class="info-hero"><span class="eyebrow">{{ __('SITE TERMS') }} </span>
        <h1>{{ __('A fair framework for using the site.') }} </h1>
        <p>{{ __('This page explains the purpose and current features of FinancersHub. Please also read our privacy notice and financial disclaimer.') }} </p>
    </header>
    <div class="info-keynote"><span class="eyebrow">{{ __('EDUCATIONAL USE') }} </span>
        <p>{{ __('Articles and tools provide general information. They do not establish a personal advisory relationship or guarantee a financial outcome.') }} </p>
    </div>
    <div class="info-layout">
        <article class="info-body">
            <section class="info-section" id="section-1"><span class="info-number">01</span>
                <div>
                    <h2>{{ __('Using the content') }} </h2>
                    <p>{{ __('You can read guides, share links and use the calculator for learning. Contact the editorial team about reproducing articles or illustrations. Source links identify material published by other organizations.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-2"><span class="info-number">02</span>
                <div>
                    <h2>{{ __('Availability and changes') }} </h2>
                    <p>{{ __('The site offers educational guides, a savings calculator and a contact form. Reader accounts and newsletter registration are currently unavailable. Content and features may be updated; article pages show publication and update dates.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-3"><span class="info-number">03</span>
                <div>
                    <h2>{{ __('Important limitations') }} </h2>
                    <p>{{ __('Check the assumptions in worked examples and verify current details with the original source. External websites have their own content and policies. Any commercial relationship should be disclosed on the relevant page.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-4"><span class="info-number">04</span>
                <div>
                    <h2>{{ __('Questions about terms') }} </h2>
                    <p>{{ __('Use our contact form for questions about using the site, content permissions or corrections. Do not include passwords, account numbers or other sensitive financial information.') }} </p>
                </div>
            </section>
        </article>
        <aside class="info-aside">
            <nav class="info-toc" aria-label="{{ __('On this page') }}"><span class="eyebrow">{{ __('IN THIS SECTION') }} </span><a
                    href="#section-1"><span>01</span> {{ __('Using the content') }} </a><a href="#section-2"><span>02</span> {{ __('Availability and changes') }} </a><a href="#section-3"><span>03</span> {{ __('Important limitations') }} </a><a
                    href="#section-4"><span>04</span> {{ __('Questions about terms') }} </a></nav>
            <div class="info-aside-note"><span class="eyebrow">{{ __('PLEASE NOTE') }} </span>
                <h3>{{ __('Your circumstances matter') }} </h3>
                <p>{{ __('A general guide cannot account for your financial circumstances. Seek qualified advice when you need a recommendation specific to you.') }} </p>
            </div>
        </aside>
    </div>
    <div class="info-end">
        <div><span class="eyebrow">{{ __('KEEP EXPLORING') }} </span>
            <h2>{{ __('Find what you need next.') }} </h2>
            <p>{{ __('More context about the publication and the content you read here.') }} </p>
        </div>
        <div class="info-next-links"><a href="{{ \App\Support\Localization::route('editorial-policy') }}">{{ __('Editorial policy') }} <span
                    aria-hidden="true">↗</span></a><a href="{{ \App\Support\Localization::route('disclaimer') }}">{{ __('Financial disclaimer') }} <span
                    aria-hidden="true">↗</span></a><a href="{{ \App\Support\Localization::route('contact') }}">{{ __('Contact') }} <span
                    aria-hidden="true">↗</span></a><a href="{{ \App\Support\Localization::route('search') }}">{{ __('Article library') }} <span
                    aria-hidden="true">↗</span></a></div>
    </div>
@endsection
