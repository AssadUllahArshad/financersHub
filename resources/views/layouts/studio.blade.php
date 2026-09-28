<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="@yield('description', 'Independent finance guides and practical explanations for better money decisions.')">
<meta name="robots" content="noindex,nofollow"><meta name="color-scheme" content="light dark">
<title>@yield('title') | FinancersHub</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">




<script src="{{ asset('assets/site.js') }}" defer></script>


<script src="{{ asset('assets/studio.js') }}" defer></script>
<script src="{{ asset('assets/studio-ui.js') }}" defer></script>
@include('partials.fonts')
@include('partials.styles',['group'=>'studio'])
</head><body id="top"><a class="skip" href="#main">Skip to content</a><div class="studio">
@include('partials.sidebar')
<div class="studio-workspace">@include('partials.topbar')
<main id="main" class="studio-main @hasSection('live') cms-main @endif">
@hasSection('live')
@else
<div class="admin-sample"><span class="sample-dot"></span>Design preview · Sample content and figures · Saving and publishing are not connected.</div>
@endif
@if(session('status'))<p class="admin-sample" role="status">{{ session('status') }}</p>@endif
@if($errors->any())<div class="admin-sample" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')
</main></div></div><dialog id="admin-confirm" aria-labelledby="confirm-title"><h2 id="confirm-title">Delete this entry?</h2><p id="confirm-description"></p><div class="form-actions"><button type="button" class="a-button secondary" data-confirm-cancel>Cancel</button><button type="button" class="a-button danger" data-confirm-accept>Delete entry</button></div></dialog></body></html>
