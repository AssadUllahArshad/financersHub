@extends('layouts.site')
@section('title', __('About'))
@section('description', __('Learn about FinancersHub, our educational finance guides, publication team and approach to explaining money decisions.'))
@section('main-class', 'wrap info-page info-about')
@section('content')
    <x-breadcrumbs :items="[['label' => 'About']]" />
    <header class="info-hero"><span class="eyebrow">{{ __('THE PUBLICATION') }} </span>
        <h1>{{ __('Finance deserves a clearer read.') }} </h1>
        <p>{{ __('Money decisions deserve explanations you can actually use. FinancersHub is an educational finance publication for readers who want the details, the trade-offs, and a clear place to start.') }} </p>
    </header>
    <div class="info-keynote"><span class="eyebrow">{{ __('OUR PURPOSE') }} </span>
        <p>{{ __('Help readers ask better questions before they choose a financial product, plan a goal, or change course.') }} </p>
    </div>
    <section class="info-cards" aria-label="{{ __('What readers can expect') }}">
        <div><span class="info-number">01</span>
            <h2>{{ __('Understand the question') }} </h2>
            <p>{{ __('Start with the choice a reader is actually making.') }} </p>
        </div>
        <div><span class="info-number">02</span>
            <h2>{{ __('See the trade-offs') }} </h2>
            <p>{{ __('Make costs, limits, and uncertainty visible.') }} </p>
        </div>
        <div><span class="info-number">03</span>
            <h2>{{ __('Know what comes next') }} </h2>
            <p>{{ __('Point to current terms and useful questions to ask.') }} </p>
        </div>
    </section>
    <div class="info-layout">
        <article class="info-body">
            <section class="info-section" id="section-1"><span class="info-number">01</span>
                <div>
                    <h2>{{ __('What you will find') }} </h2>
                    <p>{{ __('Practical guides, explainers, and comparisons across personal finance, investing, banking, credit, and business. Each article should begin with a question readers actually face.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-2"><span class="info-number">02</span>
                <div>
                    <h2>{{ __('How we want to write') }} </h2>
                    <p>{{ __('Plain language, visible sources, clear limits, and a useful next step. Readers should be able to see who prepared a piece and when it was last reviewed.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-3"><span class="info-number">03</span>
                <div>
                    <h2>{{ __('Meet the team') }} </h2>
                    <p>{{ __('Our guides use the FinancersHub Editorial Team byline. Visit the Authors page for our team biography, and use the contact form to suggest a topic or report a correction.') }} </p>
                </div>
            </section>
        </article>
        <aside class="info-aside">
            <nav class="info-toc" aria-label="{{ __('On this page') }}"><span class="eyebrow">{{ __('IN THIS SECTION') }} </span><a
                    href="#section-1"><span>01</span> {{ __('What you will find') }} </a><a href="#section-2"><span>02</span> {{ __('How we want to write') }} </a><a href="#section-3"><span>03</span> {{ __('Meet the team') }} </a></nav>
            <div class="info-aside-note"><span class="eyebrow">{{ __('PLEASE NOTE') }} </span>
                <h3>{{ __('Education first') }} </h3>
                <p>{{ __('Our guides and calculator explain concepts and assumptions. They do not provide personalized financial advice.') }} </p>
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
