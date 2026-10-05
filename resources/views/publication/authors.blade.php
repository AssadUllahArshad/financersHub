@extends('layouts.site')
@section('main-class', 'wrap author-page')
@section('title', __('Authors'))
@section('description', __('Meet the FinancersHub editorial team and read the biographies behind our educational finance guides.'))
@section('content')
    <x-breadcrumbs :items="[['label' => 'Authors']]" />
    <section class="page-hero"><span class="eyebrow">{{ __('THE PUBLICATION') }} </span>
        <h1>{{ __('Our authors') }} </h1>
        <p>{{ __('Meet the publication team and contributors.') }} </p>
    </section>
    <div class="card-grid section people-grid">
        @forelse($authors as $author)
            <article class="card">
                <div class="body"><span class="people-icon"><x-icon name="user" /></span>
                    <h2><a href="{{ \App\Support\Localization::route('authors.show', $author->slug) }}">{{ $author->name }} </a></h2>
                    <p>{{ __($author->bio ?? '') }} </p>
                </div>
            </article>
        @empty
            <p>{{ __('Contributor profiles will appear when guides are published.') }} </p>
        @endforelse
    </div>
@endsection
