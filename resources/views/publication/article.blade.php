@extends('layouts.site')
@section('title', $article->seo_title ?: $article->title)
@section('description', $article->seo_description ?: $article->excerpt)
@section('main-class', 'article-page')
@section('content')
    <div class="reading-progress" aria-hidden="true">
        <span id="reading-progress-bar"></span>
    </div>
    @php($reading = app(\App\Services\ArticleHtml::class)->prepare($article->body))<div class="wrap"><x-breadcrumbs :items="[
        ['label' => $article->category->name, 'url' => \App\Support\Localization::route('categories.show', $article->category->slug)],
        ['label' => $article->title],
    ]" />
        @if ($preview ?? false)
            <p class="admin-sample">Private editorial preview · {{ $article->status }} · not a public publication.</p>
        @endif
        <div class="article-layout reading-layout">
            <article class="reading-main" lang="{{ $article->content_locale }}">
                @if(app()->getLocale() !== $article->content_locale)
                    <p class="notice" lang="es">{{ __('English article shown because a Spanish translation is not available yet.') }} </p>
                @endif
                <header class="article-header"><span class="eyebrow">{{ __($article->category->name ?? '') }} / {{ __('Guide') }} </span>
                    <h1>{{ $article->title }} </h1>
                    <p class="deck">{{ $article->excerpt }} </p>
                    <div class="byline"><span class="avatar"
                            aria-hidden="true">{{ mb_substr($article->authorProfile->name, 0, 1) }} </span>
                        <div><a
                                href="{{ \App\Support\Localization::route('authors.show', $article->authorProfile->slug) }}"><strong>{{ $article->authorProfile->name }} </strong></a><br><span
                                class="meta">
                                @if ($article->published_at) {{ __('Published') }} <time
                                        datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->locale(app()->getLocale())->translatedFormat('F j, Y') }} </time>
                                    · {{ __('Updated') }} {{ $article->updated_at->locale(app()->getLocale())->translatedFormat('F j, Y') }}
                                @else
                                    Unpublished draft
                                @endif
                            </span>
                        </div>
                    </div>
                </header>
                <div class="article-actionbar"><span><x-icon name="clock" /> {{ $article->reading_minutes }} {{ __('min read') }} </span><a href="{{ \App\Support\Localization::route('editorial-policy') }}"><x-icon name="shield" />{{ __('Editorial standards') }} </a><button class="btn ghost" type="button" data-copy-link><x-icon name="link" />{{ __('Copy link') }} </button></div>
                <nav class="article-topic-links" aria-label="{{ __('Article categories') }}">
                    @foreach ($article->categories->prepend($article->category)->unique('id') as $assignedCategory)
                        <a href="{{ \App\Support\Localization::route('categories.show', $assignedCategory->slug) }}">{{ __($assignedCategory->name) }} </a>
                    @endforeach
                </nav>
                @if ($article->mediaAsset)<figure class="article-featured-media"><img src="{{ \App\Support\Localization::route('media.show', $article->media_asset_id) }}"
                            alt="{{ $article->publicTranslation()?->image_alt ?: $article->mediaAsset->alt_text }}" width="{{ $article->mediaAsset->width ?? 1200 }}"
                            height="{{ $article->mediaAsset->height ?? 675 }}" srcset="{{ $article->mediaAsset->srcset }}" sizes="(max-width: 800px) calc(100vw - 32px), 800px" fetchpriority="high"></figure>
                @endif
                @if (count($reading['headings']))
                    <details class="mobile-contents">
                        <summary><x-icon name="list" />{{ __('In this guide') }} </summary>
                        <nav aria-label="{{ __('Article sections') }}">
                            @foreach ($reading['headings'] as $heading)
                                <a href="#{{ $heading['id'] }}">{{ $heading['text'] }} </a>
                            @endforeach
                        </nav>
                    </details>
                @endif
                <div class="prose" id="article-reading-body">{!! app(\App\Services\ResponsiveImages::class)->decorate($reading['html']) !!}
                    @if ($article->sources)<section class="article-source"><span class="eyebrow">{{ __('SOURCES & FURTHER READING') }} </span>
                            <h2><x-icon name="article" />{{ __('Check the primary sources') }} </h2>
                            <ul>
                                @foreach ($article->sources as $source)
                                    <li><a href="{{ $source }}" rel="noopener noreferrer">{{ $source }} </a>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </div>
                <div class="disclosure"><x-icon name="shield" />
                    {{ $article->disclosure ?: 'General education only; not personalized financial, investment, tax, or legal advice.' }}
                </div>
                <aside class="reader-feedback">
                    <div>
                        <h2>{{ __('Help keep this guide accurate.') }} </h2>
                        <p>{{ __('Found something that needs a correction or a clearer explanation?') }} </p>
                    </div><a class="btn ghost"
                        href="{{ \App\Support\Localization::route('contact', ['subject' => 'Correction or update', 'article' => \App\Support\Localization::route('articles.show', $article->slug)]) }}"><x-icon
                            name="mail" />{{ __('Suggest a correction') }} </a>
                </aside>
                <section class="article-author-card"><span class="avatar"
                        aria-hidden="true">{{ mb_substr($article->authorProfile->name, 0, 1) }} </span>
                    <div><span class="eyebrow">{{ __('ABOUT THE AUTHOR') }} </span>
                        <h2>{{ $article->authorProfile->name }} </h2>
                        <p>{{ __($article->authorProfile->bio ?? '') }} </p>
                    </div>
                </section>
                @if ($related->isNotEmpty())<section class="section article-more">
                        <h2>{{ __('Continue reading') }} </h2>
                        <div class="article-related-grid">
                            @foreach ($related as $item)
                                @include('publication.card')
                            @endforeach
                        </div>
                    </section>
                @endif
            </article>
            <aside class="reading-sidebar">
                <div class="reading-sidebar-inner">
                    <div class="sidebar-box toc"><span class="eyebrow">{{ __('YOUR READING GUIDE') }} </span>
                        <h2><x-icon name="list" />{{ __('On this page') }} </h2>
                        @foreach ($reading['headings'] as $heading)
                            <a href="#{{ $heading['id'] }}">{{ $heading['text'] }} </a>
                        @endforeach
                        <a class="reading-library-link" href="{{ \App\Support\Localization::route('search') }}"><x-icon name="search" />{{ __('Browse the library') }} </a>
                    </div>
                    <div class="reading-tool-card"><x-icon name="calculator" />
                        <h2>{{ __('Explore the numbers.') }} </h2>
                        <p>{{ __('Try a savings scenario with our free calculator.') }} </p><a
                            href="{{ \App\Support\Localization::route('tools.compound-interest') }}">{{ __('Open calculator ↗') }} </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
