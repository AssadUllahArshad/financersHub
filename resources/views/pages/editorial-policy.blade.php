@extends('layouts.site')
@section('title', __('Editorial policy'))
@section('description', __('Read our standards for sources, editorial review, AI-assisted drafts, corrections and commercial disclosures.'))
@section('main-class', 'wrap info-page info-editorial-policy')
@section('content')
    <x-breadcrumbs :items="[['label' => 'Editorial policy']]" />
    <header class="info-hero"><span class="eyebrow">{{ __('HOW WE WORK') }} </span>
        <h1>{{ __('A standard readers can see.') }} </h1>
        <p>{{ __('Readers should be able to see where a claim comes from, what it means, and when it needs another look. These standards guide our publication decisions.') }} </p>
    </header>
    <div class="info-keynote"><span class="eyebrow">{{ __('EDITORIAL COMMITMENT') }} </span>
        <p>{{ __('Show sources and limits clearly. Label commercial material. Correct meaningful errors.') }} </p>
    </div>
    <div class="info-layout">
        <article class="info-body">
            <section class="info-section" id="section-1"><span class="info-number">01</span>
                <div>
                    <h2>{{ __('Sources and verification') }} </h2>
                    <p>{{ __('Use checkable primary material where relevant. Attribute figures, explain assumptions, and distinguish established information from interpretation.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-2"><span class="info-number">02</span>
                <div>
                    <h2>{{ __('Authors and review') }} </h2>
                    <p>{{ __('Show the writer and any relevant reviewer with an accurate biography. Do not imply professional qualifications that have not been verified.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-3"><span class="info-number">03</span>
                <div>
                    <h2>{{ __('Updates and corrections') }} </h2>
                    <p>{{ __('Show publication and update dates. Correct material errors clearly and review time-sensitive information when it changes.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-4"><span class="info-number">04</span>
                <div>
                    <h2>{{ __('Commercial separation') }} </h2>
                    <p>{{ __('Label sponsored content and advertising. Keep commercial placements visually distinct from editorial stories.') }} </p>
                </div>
            </section>
        </article>
        <aside class="info-aside">
            <nav class="info-toc" aria-label="{{ __('On this page') }}"><span class="eyebrow">{{ __('IN THIS SECTION') }} </span><a
                    href="#section-1"><span>01</span> {{ __('Sources and verification') }} </a><a href="#section-2"><span>02</span> {{ __('Authors and review') }} </a><a href="#section-3"><span>03</span> {{ __('Updates and corrections') }} </a><a
                    href="#section-4"><span>04</span> {{ __('Commercial separation') }} </a></nav>
            <div class="info-aside-note"><span class="eyebrow">{{ __('PLEASE NOTE') }} </span>
                <h3>{{ __('Human editorial review') }} </h3>
                <p>{{ __('AI-assisted drafts stay unpublished until editorial review. The editor must verify sources, calculations, originality and image rights before publishing. A team byline does not imply individual professional credentials.') }} </p>
            </div>
        </aside>
    </div>
    <div class="info-end">
        <div><span class="eyebrow">{{ __('KEEP EXPLORING') }} </span>
            <h2>{{ __('Find what you need next.') }} </h2>
            <p>{{ __('More context about the publication and the content you read here.') }} </p>
        </div>
        <div class="info-next-links"><a href="{{ \App\Support\Localization::route('disclaimer') }}">{{ __('Financial disclaimer') }} <span
                    aria-hidden="true">↗</span></a><a href="{{ \App\Support\Localization::route('contact') }}">{{ __('Contact') }} <span
                    aria-hidden="true">↗</span></a><a href="{{ \App\Support\Localization::route('search') }}">{{ __('Article library') }} <span
                    aria-hidden="true">↗</span></a></div>
    </div>
@endsection
