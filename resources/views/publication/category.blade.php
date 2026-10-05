@extends('layouts.site')
@section('main-class', 'wrap')
@section('title', __($category->name))
@section('description', __($category->description ?? ''))
@section('content')
    <x-breadcrumbs :items="[['label' => $category->name]]" />
    <section class="category-intro {{ $articles->first()?->mediaAsset ? '' : 'no-feature-image' }}">
        <div><span class="eyebrow">{{ __('TOPIC') }} / {{ __($category->name ?? '') }} </span>
            <h1>{{ __($category->name ?? '') }} </h1>
            <p>{{ __($category->description ?? '') }} </p>
            @if ($articles->isNotEmpty())<a class="text-link" href="{{ \App\Support\Localization::route('articles.show', $articles->first()->slug) }}">{{ __('Start with a guide ↗') }} </a>
            @endif
        </div>
        @if ($articles->first()?->mediaAsset)<div class="art blue"><img src="{{ \App\Support\Localization::route('media.show', $articles->first()->media_asset_id) }}"
                    alt="{{ $articles->first()->mediaAsset->alt_text }}"
                    width="{{ $articles->first()->mediaAsset->width ?? 800 }}"
                    height="{{ $articles->first()->mediaAsset->height ?? 500 }}" srcset="{{ $articles->first()->mediaAsset->srcset }}" sizes="(max-width: 800px) calc(100vw - 32px), 600px"></div>
        @endif
    </section>
    <div class="content-grid section">
        <div>
            <section class="category-featured">
                <div class="section-head">
                    <div><span class="eyebrow">{{ __('START HERE') }} </span>
                        <h2>{{ __('Guides to :topic', ['topic' => mb_strtolower(__($category->name))]) }} </h2>
                    </div>
                </div>
                <div class="card-grid">
                    @forelse($articles as $item)
                        @include('publication.card')
                    @empty
                        <p>{{ __('Guides for this topic will appear when they are published.') }} </p>
                    @endforelse
                </div>{{ $articles->links('pagination.simple') }}
            </section>
        </div>
        <aside>
            <div class="sidebar-box">
                <h3>{{ __('Browse all topics') }} </h3>
                <div class="topics">
                    @foreach ($categories as $topic)
                        <a class="pill {{ $topic->id === $category->id ? 'active' : '' }}"
                            href="{{ \App\Support\Localization::route('categories.show', $topic->slug) }}">{{ __($topic->name ?? '') }} </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
@endsection
