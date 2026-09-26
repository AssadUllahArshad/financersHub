@extends('layouts.site')
@section('title', $page['title'].' | FinancersHub')
@section('description', $page['intro'])
@push('styles')<link rel="stylesheet" href="{{ asset('assets/info.css') }}">@endpush
@section('content')
<div class="wrap info-page info-{{ $page['slug'] ?? 'general' }}">
  <x-breadcrumbs :items="[['label'=>$page['title']]]" />
  <header class="info-hero"><span class="eyebrow">{{ $page['eyebrow'] }}</span><h1>{{ $page['headline'] }}</h1><p>{{ $page['intro'] }}</p></header>
  @if(!empty($page['keynote']))<div class="info-keynote"><span class="eyebrow">{{ $page['keynote']['label'] }}</span><p>{{ $page['keynote']['text'] }}</p></div>@endif
  @if(!empty($page['cards']))<section class="info-cards" aria-label="What readers can expect">@foreach($page['cards'] as $card)<div><span class="info-number">{{ sprintf('%02d',$loop->iteration) }}</span><h2>{{ $card['title'] }}</h2><p>{{ $card['text'] }}</p></div>@endforeach</section>@endif
  @if(!empty($page['checklist']))<section class="info-checklist" aria-labelledby="checklist-title"><div><span class="eyebrow">A PRACTICAL CHECK</span><h2 id="checklist-title">Before relying on an article</h2></div><ol>@foreach($page['checklist'] as $item)<li><span>{{ sprintf('%02d',$loop->iteration) }}</span>{{ $item }}</li>@endforeach</ol></section>@endif
  <div class="info-layout"><article class="info-body">
    @foreach($page['sections'] as $section)<section class="info-section" id="section-{{ $loop->iteration }}"><span class="info-number">{{ sprintf('%02d',$loop->iteration) }}</span><div><h2>{{ $section['heading'] }}</h2><p>{{ $section['body'] }}</p></div></section>@endforeach
  </article><aside class="info-aside"><nav class="info-toc" aria-label="On this page"><span class="eyebrow">IN THIS SECTION</span>@foreach($page['sections'] as $section)<a href="#section-{{ $loop->iteration }}"><span>{{ sprintf('%02d',$loop->iteration) }}</span>{{ $section['heading'] }}</a>@endforeach</nav><div class="info-aside-note"><span class="eyebrow">PLEASE NOTE</span><h3>{{ $page['aside_title'] }}</h3><p>{{ $page['aside_body'] }}</p></div></aside></div>
  <div class="info-end"><div><span class="eyebrow">KEEP EXPLORING</span><h2>Find what you need next.</h2><p>More context about the publication and the content you read here.</p></div><div class="info-next-links">@foreach(['editorial-policy'=>'Editorial policy','disclaimer'=>'Financial disclaimer','contact'=>'Contact','search'=>'Article library'] as $route=>$label)@if(($page['slug'] ?? '') !== $route)<a href="{{ url('/'.$route) }}">{{ $label }} <span aria-hidden="true">↗</span></a>@endif @endforeach</div></div>
</div>
@endsection
