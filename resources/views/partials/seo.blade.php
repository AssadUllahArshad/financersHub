@php($seo = app(\App\Services\PublicationSeo::class)->metadata($__env->yieldContent('title'), $__env->yieldContent('description'), $article ?? null, ($preview ?? false) || isset($exception)))
<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if ($seo['noindex'])
<meta name="robots" content="noindex,nofollow">@else
    <meta name="robots" content="index,follow,max-image-preview:large">
@endif
<link rel="canonical" href="{{ $seo['canonical'] }}">
@foreach($seo['alternates'] as $language => $url)
    <link rel="alternate" hreflang="{{ $language }}" href="{{ $url }}">
@endforeach
<meta property="og:locale" content="{{ (($article ?? null)?->content_locale ?? app()->getLocale()) === 'es' ? 'es_US' : 'en_US' }}">
<meta property="og:site_name" content="{{ $seo['name'] }}">
<meta property="og:type" content="{{ $seo['published'] ? 'article' : 'website' }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta name="twitter:card" content="{{ $seo['image'] ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['description'] }}">
@if ($seo['image'])
    <meta property="og:image" content="{{ $seo['image'] }}">
    <meta name="twitter:image" content="{{ $seo['image'] }}">
@endif
@if ($seo['schema'])
    <script type="application/ld+json">{!! json_encode($seo['schema'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) !!}</script>
@endif

@if (!isset($exception))
    @php($publisher = \App\Models\SiteSetting::where('key', 'adsense_publisher_id')->value('value'))
    @if (preg_match('/^pub-[0-9]{16}$/', $publisher ?? ''))
        <meta name="google-adsense-account" content="ca-{{ $publisher }}">
    @endif
@endif

@if ($seo['breadcrumb'])
    <script type="application/ld+json">{!! json_encode($seo['breadcrumb'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) !!}</script>
@endif

@if ($seo['published'])
    <meta property="article:published_time" content="{{ $article->published_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $article->updated_at->toIso8601String() }}">
    @if ($seo['image'])
        <meta property="og:image:alt" content="{{ $article->publicTranslation()?->image_alt ?: ($article->mediaAsset?->alt_text ?? $article->title) }}">
        <meta name="twitter:image:alt" content="{{ $article->publicTranslation()?->image_alt ?: ($article->mediaAsset?->alt_text ?? $article->title) }}">
    @endif
@endif
