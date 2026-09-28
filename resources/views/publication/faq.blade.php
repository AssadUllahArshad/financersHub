@extends('layouts.site')
@section('main-class','wrap faq-page')
@section('title','FAQs')
@section('content')
<nav class="bread" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / <span aria-current="page">FAQs</span></nav>
<header class="faq-hero"><span class="eyebrow">HELP / COMMON QUESTIONS</span><h1>Answers that help<br><em>you find your way.</em></h1><p>How to use our guides, how to contact us, and where to find our editorial policies.</p></header>
@php($groups=['using-financershub'=>'Using FinancersHub','newsletter-and-contact'=>'Newsletter and contact','privacy-and-editorial-standards'=>'Privacy and editorial standards'])
<div class="faq-layout"><nav class="faq-jump" aria-label="FAQ topics"><strong>On this page</strong>
@foreach($groups as $key=>$label)
<a href="#{{ $key }}">{{ $label }} ↗</a>
@endforeach
</nav><div>
@foreach($groups as $key=>$label)
<section id="{{ $key }}" class="faq-group" aria-labelledby="faq-{{ $key }}"><div class="faq-group-head"><span class="eyebrow">{{ sprintf('%02d',$loop->iteration) }} / FAQ</span><h2 id="faq-{{ $key }}">{{ $label }}</h2></div><div>
@forelse($faqs->where('group',$key) as $faq)
<details class="faq-item"><summary>{{ $faq->question }}<span aria-hidden="true">+</span></summary><div class="faq-answer"><p>{{ $faq->answer }}</p></div></details>
@empty
<p>Answers for this topic are being prepared.</p>
@endforelse
</div></section>
@endforeach
<section class="faq-contact"><span class="eyebrow">STILL LOOKING?</span><h2>Find the right next step.</h2><p>Explore the library or send a question to the editorial team.</p><div><a href="{{ route('search') }}">Browse guides ↗</a><a href="{{ route('contact') }}">Contact us ↗</a></div></section>
</div></div>
@endsection
