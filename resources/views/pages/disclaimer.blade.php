@extends('layouts.site')
@section('title', __('Financial disclaimer'))
@section('description', __('Understand the limits of FinancersHub guides and calculators, including assumptions, financial risks and general educational use.'))
@section('main-class', 'wrap info-page info-disclaimer')
@section('content')
    <x-breadcrumbs :items="[['label' => 'Financial disclaimer']]" />
    <header class="info-hero"><span class="eyebrow">{{ __('IMPORTANT CONTEXT') }} </span>
        <h1>{{ __('Information for learning, not a personal plan.') }} </h1>
        <p>{{ __('FinancersHub is for general education. An article can help you understand a decision, but it cannot account for your finances, goals, taxes, or tolerance for risk.') }} </p>
    </header>
    <div class="info-keynote"><span class="eyebrow">{{ __('PLEASE READ BEFORE ACTING') }} </span>
        <p>{{ __('Check current product terms and speak with a qualified professional when a decision depends on your personal circumstances.') }} </p>
    </div>
    <section class="info-checklist" aria-labelledby="checklist-title">
        <div><span class="eyebrow">{{ __('A PRACTICAL CHECK') }} </span>
            <h2 id="checklist-title">{{ __('Before relying on an article') }} </h2>
        </div>
        <ol>
            <li><span>01</span> {{ __('Check the date and source of the information.') }} </li>
            <li><span>02</span> {{ __('Verify rates, fees, eligibility, and terms with the provider.') }} </li>
            <li><span>03</span> {{ __('Get qualified guidance when your own circumstances determine the answer.') }} </li>
        </ol>
    </section>
    <div class="info-layout">
        <article class="info-body">
            <section class="info-section" id="section-1"><span class="info-number">01</span>
                <div>
                    <h2>{{ __('No individualized advice') }} </h2>
                    <p>{{ __('Nothing on this site should be treated as personalized investment, financial, tax, legal, or other professional advice.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-2"><span class="info-number">02</span>
                <div>
                    <h2>{{ __('Check current details') }} </h2>
                    <p>{{ __('Rates, fees, eligibility, rules, and product terms can change. Verify important information with the relevant provider or official source before acting.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-3"><span class="info-number">03</span>
                <div>
                    <h2>{{ __('Understand the risk') }} </h2>
                    <p>{{ __('Financial decisions can have costs and risks. Consider your circumstances and seek qualified professional guidance when a decision depends on them.') }} </p>
                </div>
            </section>
            <section class="info-section" id="section-4"><span class="info-number">04</span>
                <div>
                    <h2>{{ __('Illustrations and calculators') }} </h2>
                    <p>{{ __('Worked examples and calculator results depend on the stated assumptions. They are illustrations, not forecasts or guarantees. Real outcomes may differ because of changing returns, fees, taxes and inflation.') }} </p>
                </div>
            </section>
        </article>
        <aside class="info-aside">
            <nav class="info-toc" aria-label="{{ __('On this page') }}"><span class="eyebrow">{{ __('IN THIS SECTION') }} </span><a
                    href="#section-1"><span>01</span> {{ __('No individualized advice') }} </a><a href="#section-2"><span>02</span> {{ __('Check current details') }} </a><a href="#section-3"><span>03</span> {{ __('Understand the risk') }} </a><a
                    href="#section-4"><span>04</span> {{ __('Illustrations and calculators') }} </a></nav>
            <div class="info-aside-note"><span class="eyebrow">{{ __('PLEASE NOTE') }} </span>
                <h3>{{ __('Before you act') }} </h3>
                <p>{{ __('Use published material as a starting point for questions. Check the current terms and the information that applies to you.') }} </p>
            </div>
        </aside>
    </div>
    <div class="info-end">
        <div><span class="eyebrow">{{ __('KEEP EXPLORING') }} </span>
            <h2>{{ __('Find what you need next.') }} </h2>
            <p>{{ __('More context about the publication and the content you read here.') }} </p>
        </div>
        <div class="info-next-links"><a href="{{ \App\Support\Localization::route('editorial-policy') }}">{{ __('Editorial policy') }} <span
                    aria-hidden="true">↗</span></a><a href="{{ \App\Support\Localization::route('contact') }}">{{ __('Contact') }} <span
                    aria-hidden="true">↗</span></a><a href="{{ \App\Support\Localization::route('search') }}">{{ __('Article library') }} <span
                    aria-hidden="true">↗</span></a></div>
    </div>
@endsection
