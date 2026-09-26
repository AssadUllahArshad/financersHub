<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Editorial studio') | FinancersHub</title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="icon" href="{{ asset('assets/favicon.svg') }}" type="image/svg+xml">
  <link rel="stylesheet" href="{{ asset('assets/site.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/editorial.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin.css') }}">
  <script src="{{ asset('assets/admin.js') }}" defer></script>
</head>
<body>
@php
  $groups = [
    'Workspace'=>[['index','Overview'],['articles','Articles'],['editor','Write article'],['media','Media library']],
    'Editorial'=>[['categories','Categories'],['tags','Tags'],['authors','Authors'],['comments','Comments']],
    'Publication'=>[['seo','SEO & discovery'],['advertisements','Advertising'],['newsletter','Newsletter'],['settings','Settings']],
  ];
  $active = trim($__env->yieldContent('section', 'index'));
@endphp
<div class="studio">
  <aside class="studio-sidebar">
    <a class="studio-brand" href="{{ url('/') }}"><img src="{{ asset('assets/logo-white.svg') }}" alt="FinancersHub" width="184" height="34"><span>EDITORIAL STUDIO</span></a>
    <nav aria-label="Editorial studio">
      @foreach($groups as $group => $items)
        <div class="side-group"><div class="side-group-label">{{ $group }}</div>
          @foreach($items as [$key,$label])
            <a class="side-link {{ $active === $key ? 'active' : '' }}" href="{{ url('/admin/'.$key) }}" @if($active === $key) aria-current="page" @endif><span>{{ $label }}</span></a>
          @endforeach
        </div>
      @endforeach
    </nav>
    <div class="side-bottom"><div class="user-dot">FH</div><div><strong>Editorial team</strong><small>Workspace preview</small></div></div>
  </aside>
  <div class="studio-workspace"><header class="studio-topbar"><button class="mobile-studio-menu" id="studio-menu" type="button" aria-label="Open studio menu" aria-expanded="false">☰</button><div class="studio-crumb">FinancersHub / Editorial studio / @yield('title')</div><div class="topbar-actions"><button class="studio-theme" id="studio-theme" type="button" aria-label="Toggle studio dark mode">◐</button><a href="{{ url('/') }}" target="_blank" rel="noopener">View site ↗</a><a class="topbar-create" href="{{ url('/admin/editor') }}">＋ New article</a><span class="topbar-avatar">FH</span></div></header>
    <main id="main" class="studio-main"><div class="admin-sample"><span class="sample-dot"></span>Design preview · sample content and figures · changes are not saved</div><div class="studio-heading"><div><span class="admin-kicker">EDITORIAL STUDIO / {{ strtoupper($active) }}</span><h1>@yield('title')</h1><p>@yield('intro')</p></div></div>@yield('content')</main>
  </div>
</div>
</body>
</html>
