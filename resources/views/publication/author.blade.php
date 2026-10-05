@extends('layouts.site')
@section('main-class', 'wrap author-page')
@section('title', $author->name)
@section('description', Str::limit($author->bio ?: 'Published guides and contributor information from FinancersHub.',
    155, ''))
@section('content')
    <x-breadcrumbs :items="[['label' => 'Authors', 'url' => \App\Support\Localization::route('authors.index')], ['label' => $author->name]]" />
    <header class="author-intro">
        <div class="author-large-avatar" aria-hidden="true">{{ mb_substr($author->name, 0, 1) }} </div>
        <div><span class="eyebrow">{{ __('AUTHOR PROFILE') }} </span>
            <h1>{{ $author->name }} </h1>
            <p>{{ __($author->bio ?? '') }} </p><a class="text-link" href="{{ \App\Support\Localization::route('editorial-policy') }}">{{ __('How we approach our guides ↗') }} </a>
        </div>
    </header>
    <section class="section author-work">
        <div class="section-head">
            <div><span class="eyebrow">{{ __('PUBLISHED GUIDES') }} </span>
                <h2>{{ __('Articles by :name', ['name' => $author->name]) }} </h2>
            </div>
        </div>
        <div class="card-grid">
            @forelse($articles as $item)
                @include('publication.card')
            @empty
                <p>{{ __('No published guides yet.') }} </p>
            @endforelse
        </div>{{ $articles->links('pagination.simple') }}
    </section>
@endsection
