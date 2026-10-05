@extends('layouts.site')
@section('title', __($heading))
@section('description', __($description ?? ''))
@section('content')
    <section class="wrap section publication-library">
        <x-breadcrumbs :items="[['label' => 'Article library']]" /><span class="eyebrow">{{ __('FINANCERSHUB LIBRARY') }} </span>
        <h1>{{ __($heading ?? '') }} </h1>
        <p>{{ __($description ?? '') }} </p>
        <form method="get" action="{{ \App\Support\Localization::route('search') }}" class="library-filters">
            <label>{{ __('Search guides') }} <input class="form-control" type="search" name="q" value="{{ $query }}"
                    maxlength="200" placeholder="{{ __('A topic or money question') }}"></label>
            <label>{{ __('Topic') }} <select class="form-select" name="topic">
                    <option value="">{{ __('All topics') }} </option>
                    @foreach ($topics as $choice)
                        <option value="{{ $choice->slug }}" @selected($topic === $choice->slug)>{{ __($choice->name ?? '') }} </option>
                    @endforeach
                </select>
            </label>
            <label>{{ __('Sort by') }} <select class="form-select" name="sort">
                    <option value="newest" @selected($sort === 'newest')>{{ __('Newest first') }} </option>
                    <option value="oldest" @selected($sort === 'oldest')>{{ __('Oldest first') }} </option>
                    <option value="title" @selected($sort === 'title')>{{ __('Title A–Z') }} </option>
                </select></label>
            <button class="btn" type="submit"><x-icon name="search" />{{ __('Find guides') }} </button>
        </form>
        <div class="library-results-summary">
            <p class="meta" role="status">{{ $articles->total() }} {{ __($articles->total() === 1 ? 'guide' : 'guides') }}
                @if ($query !== '')
                    {{ __('matching') }} &ldquo;{{ $query }}&rdquo;
                    @endif @if ($topic !== '')
                        {{ __('in') }} {{ __($topics->firstWhere('slug', $topic)?->name ?? '') }}
                    @endif
            </p>
            @if ($query !== '' || $topic !== '' || $sort !== 'newest')
                <a href="{{ \App\Support\Localization::route('search') }}">{{ __('Clear filters') }} </a>
            @endif
        </div>
        <div class="shelf-cards">
            @forelse($articles as $item)
                @include('publication.card')@empty
                <div class="empty library-empty">
                    <h2>{{ __('No guides match this selection.') }} </h2>
                    <p>{{ __('Try a broader search or explore the savings calculator while new guides are being prepared.') }} </p><a
                        class="btn ghost" href="{{ \App\Support\Localization::route('search') }}">{{ __('Reset filters') }} </a> <a class="btn"
                        href="{{ \App\Support\Localization::route('tools.compound-interest') }}"><x-icon name="calculator" />{{ __('Try the calculator') }} </a>
                </div>
            @endforelse
        </div>{{ $articles->links('pagination.simple') }}
    </section>
@endsection
