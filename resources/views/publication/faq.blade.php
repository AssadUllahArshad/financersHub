@extends('layouts.site')
@section('main-class', 'wrap faq-page')
@section('title', __('Frequently Asked Questions'))
@section('description', __('Answers about FinancersHub guides, our savings calculator, editorial standards, privacy and contacting the team.'))
@section('content')
    <x-breadcrumbs :items="[['label' => 'FAQs']]" />
    <header class="faq-hero"><span class="eyebrow">{{ __('HELP / COMMON QUESTIONS') }} </span>
        <h1>{{ __('Answers that help') }} <br><em>{{ __('you find your way.') }} </em></h1>
        <p>{{ __('How to use our guides, how to contact us, and where to find our editorial policies.') }} </p>
    </header>
    @php($groups = ['using-financershub' => 'Using FinancersHub', 'newsletter-and-contact' => 'Newsletter and contact', 'privacy-and-editorial-standards' => 'Privacy and editorial standards'])<div class="faq-layout">
        <nav class="faq-jump" aria-label="{{ __('FAQ topics') }}"><strong>{{ __('On this page') }} </strong>
            @foreach ($groups as $key => $label)
                <a href="#{{ $key }}">{{ __($label ?? '') }} ↗</a>
            @endforeach
        </nav>
        <div>
            @foreach ($groups as $key => $label)
                <section id="{{ $key }}" class="faq-group" aria-labelledby="faq-{{ $key }}">
                    <div class="faq-group-head"><span class="eyebrow">{{ sprintf('%02d', $loop->iteration) }} / FAQ</span>
                        <h2 id="faq-{{ $key }}">{{ __($label ?? '') }} </h2>
                    </div>
                    <div>
                        @forelse($faqs->where('group',$key) as $faq)
                            <details class="faq-item">
                                <summary>{{ __($faq->question ?? '') }} <span aria-hidden="true">+</span></summary>
                                <div class="faq-answer">
                                    <p>{{ __($faq->answer ?? '') }} </p>
                                </div>
                            </details>
                        @empty
                            <p>{{ __('Answers for this topic are being prepared.') }} </p>
                        @endforelse
                    </div>
                </section>
            @endforeach
            <section class="faq-contact"><span class="eyebrow">{{ __('STILL LOOKING?') }} </span>
                <h2>{{ __('Find the right next step.') }} </h2>
                <p>{{ __('Explore the library or send a question to the editorial team.') }} </p>
                <div><a href="{{ \App\Support\Localization::route('search') }}">{{ __('Browse guides ↗') }} </a><a
                        href="{{ \App\Support\Localization::route('contact') }}">{{ __('Contact us ↗') }} </a>
                </div>
            </section>
        </div>
    </div>

    @if ($faqs->isNotEmpty())
        @php($faqSchema = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faqs->filter(fn($faq) => in_array($faq->group, ['using-financershub', 'newsletter-and-contact', 'privacy-and-editorial-standards']))->map(fn($faq) => ['@type' => 'Question', 'name' => __($faq->question), 'acceptedAnswer' => ['@type' => 'Answer', 'text' => __($faq->answer)]])->values()->all()])
        <script type="application/ld+json">{!! json_encode($faqSchema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) !!}</script>
    @endif

@endsection
