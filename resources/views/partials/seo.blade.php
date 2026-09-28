@php($seo = app(\App\Services\PublicationSeo::class)->metadata($__env->yieldContent('title'), $__env->yieldContent('description'), $article ?? null, ($preview ?? false) || isset($exception)))
<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if($seo['noindex'])<meta name="robots" content="noindex,nofollow">@endif
<link rel="canonical" href="{{ $seo['canonical'] }}">
<meta property="og:site_name" content="{{ $seo['name'] }}">
<meta property="og:type" content="{{ $seo['published'] ? 'article' : 'website' }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta name="twitter:card" content="{{ $seo['image'] ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['description'] }}">
@if($seo['image'])<meta property="og:image" content="{{ $seo['image'] }}"><meta name="twitter:image" content="{{ $seo['image'] }}">@endif
@if($seo['schema'])<script type="application/ld+json">{!! json_encode($seo['schema'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) !!}</script>@endif

@if(!isset($exception))
@php($publisher = \App\Models\SiteSetting::where('key','adsense_publisher_id')->value('value'))
@if(preg_match('/^pub-[0-9]{16}$/',$publisher ?? ''))<meta name="google-adsense-account" content="ca-{{ $publisher }}">@endif
@endif
