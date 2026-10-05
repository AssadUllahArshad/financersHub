@extends('layouts.site')
@section('title', __('Practical Finance Guides and Savings Tools'))
@section('description', __('Understand saving, budgeting and investing with source-linked finance guides and a free compound interest calculator from FinancersHub.'))
@section('content')
    <section class="wrap opening">
        <div class="opening-copy"><span class="eyebrow">{{ __('THE FINANCERSHUB EDIT') }} </span>
            <h1>{{ __('Understand money.') }} <br><em>{{ __('Move with confidence.') }} </em></h1>
            <p>{{ __('Thoughtful guides on personal finance, investing, banking and the choices that shape your financial life.') }} </p>
        </div>
    </section>
    @if ($articles->isNotEmpty())@php($lead = $articles->first())<section class="wrap front-grid {{ $articles->count() === 1 ? 'single-story' : '' }}" aria-label="{{ __('Featured stories') }}">
            <article class="front-lead">
                @if ($lead->mediaAsset)<a class="lead-image" href="{{ \App\Support\Localization::route('articles.show', $lead->slug) }}"><img
                            src="{{ \App\Support\Localization::route('media.show', $lead->media_asset_id) }}" alt="{{ $lead->publicTranslation()?->image_alt ?: $lead->mediaAsset->alt_text }}"
                            width="{{ $lead->mediaAsset->width ?? 1600 }}" height="{{ $lead->mediaAsset->height ?? 1000 }}"
                            srcset="{{ $lead->mediaAsset->srcset }}" sizes="(max-width: 800px) calc(100vw - 32px), {{ $articles->count() === 1 ? '1260px' : '850px' }}" fetchpriority="high"></a>
                @endif
                <div class="lead-copy">
                    <span class="eyebrow">{{ __('THE LEAD') }} / {{ __($lead->category->name ?? '') }} </span>
                    <h2><a href="{{ \App\Support\Localization::route('articles.show', $lead->slug) }}">{{ $lead->title }} </a></h2>
                    <p>{{ $lead->excerpt }} </p><a class="text-link" href="{{ \App\Support\Localization::route('articles.show', $lead->slug) }}">{{ __('Read the guide ↗') }} </a>
                </div>
            </article>
            @if ($articles->count() > 1)
                <div class="front-rail">
                    <div class="rail-heading"><span class="eyebrow">{{ __('ALSO IN FOCUS') }} </span></div>
                    @foreach ($articles->slice(1, 2) as $item)
                        @include('publication.card')
                    @endforeach
                </div>
            @endif
        </section>
        @if ($articles->count() > 3)
            <section class="wrap section">
                <div class="section-head">
                    <h2>{{ __('Guides from the desk') }} </h2><a href="{{ \App\Support\Localization::route('search') }}">{{ __('The full library ↗') }} </a>
                </div>
                <div class="shelf-cards">
                    @foreach ($articles->slice(3) as $item)
                        @include('publication.card')
                    @endforeach
                </div>
            </section>
        @endif
    @else<section class="wrap section">
            <h2>{{ __('The library is being prepared.') }} </h2>
            <p>{{ __('Approved guides will appear here when they are published.') }} </p>
        </section>
    @endif
    <section class="wrap library-strip">
        <h2>{{ __('Find a guide for the question on your mind.') }} </h2><a class="text-link" href="{{ \App\Support\Localization::route('search') }}">{{ __('Browse all guides ↗') }} </a>
    </section>
    <section class="wrap finance-tool-section" aria-labelledby="finance-tool-title">
        <div class="finance-tool-copy"><span class="eyebrow">{{ __('A PRACTICAL PLACE TO START') }} </span>
            <h2 id="finance-tool-title">{{ __('Put your savings plan into numbers.') }} </h2>
            <p>{{ __('Explore how your starting balance, monthly contributions and an assumed interest rate could work together over time.') }} </p><a class="btn" href="{{ \App\Support\Localization::route('tools.compound-interest') }}"><x-icon name="calculator" />{{ __('Open the savings calculator ↗') }} </a>
            <p class="tool-detail">{{ __('No account required · Runs in your browser · Download your results') }} </p>
        </div>
        <div class="finance-tool-steps"><span class="eyebrow">{{ __('THREE SIMPLE STEPS') }} </span>
            <ol>
                <li><strong>{{ __('Set your starting point') }} </strong><span>{{ __('Choose a balance and a monthly contribution.') }} </span></li>
                <li><strong>{{ __('Compare assumptions') }} </strong><span>{{ __('Change the time period or assumed rate.') }} </span></li>
                <li><strong>{{ __('Understand the result') }} </strong><span>{{ __('See contributions and illustrative interest separately.') }} </span></li>
            </ol><small>{{ __('Illustrations are not forecasts or guaranteed returns.') }} </small>
        </div>
    </section>
    <section class="wrap section" aria-labelledby="topic-grid-title">
        <div class="section-head">
            <div><span class="eyebrow">{{ __('FIND YOUR NEXT QUESTION') }} </span>
                <h2 id="topic-grid-title">{{ __('Explore by topic') }} </h2>
            </div><a href="{{ \App\Support\Localization::route('search') }}">{{ __('All guides ↗') }} </a>
        </div>
        <div class="finance-topic-grid">
            @foreach ($topics as $topic)
                <a class="finance-topic-card" href="{{ \App\Support\Localization::route('categories.show', $topic->slug) }}"><span
                        class="topic-symbol"><x-icon :name="match ($topic->slug) {
                            'personal-finance', 'saving' => 'wallet',
                            'investing', 'retirement' => 'chart',
                            'banking' => 'bank',
                            'credit', 'loans' => 'card',
                            'business' => 'briefcase',
                            'insurance' => 'shield',
                            'fintech' => 'calculator',
                            default => 'article',
                        }" /></span>
                    <h3>{{ __($topic->name ?? '') }} <span aria-hidden="true">{{ __('↗') }} </span></h3>
                    <p>{{ $topic->description }} </p>
                </a>
            @endforeach
        </div>
    </section>
@endsection
