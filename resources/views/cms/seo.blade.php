@extends('layouts.studio')
@section('live','1')
@section('title','SEO & discovery')
@section('content')
<div class="studio-heading"><div><span class="admin-kicker">PUBLICATION</span><h1>SEO & discovery</h1><p>Set publication defaults. Individual articles use their own SEO title and description when supplied.</p></div></div>
<section class="studio-panel"><form method="post" action="{{ route('admin.seo.save') }}">@csrf
<label class="admin-field">Publication name<input name="seo_site_name" required maxlength="80" value="{{ old('seo_site_name',$settings['seo_site_name'] ?? 'FinancersHub') }}"></label>
<label class="admin-field">Default description<textarea name="seo_description" required maxlength="300" rows="3">{{ old('seo_description',$settings['seo_description'] ?? 'Independent finance guides and practical explanations for better money decisions.') }}</textarea></label>
<button class="a-button primary">Save SEO defaults</button></form></section>
<section class="studio-panel"><h2>Indexing status</h2><p>{{ config('financershub.design_preview') ? 'Design preview is enabled. Pages request no indexing and the sitemap is empty.' : 'Published articles are included in the sitemap. Drafts, scheduled articles, demo records and private previews are excluded.' }}</p><div class="operations-links"><a href="{{ route('sitemap') }}">View sitemap</a><a href="{{ route('robots') }}">View robots.txt</a></div></section>
@endsection
