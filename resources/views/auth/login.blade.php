<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Editorial sign in | FinancersHub</title>
<link rel="icon" href="{{ asset('assets/favicon.svg') }}"><script src="{{ asset('assets/login.js') }}" defer></script>@include('partials.fonts')
@include('partials.styles',['group'=>'login'])
</head>
<body class="login-page"><a class="skip" href="#main">Skip to sign in</a>
<header class="login-header"><a href="{{ route('home') }}"><img src="{{ asset('assets/logo-primary.svg') }}" width="230" height="44" alt="FinancersHub"></a><a href="{{ route('home') }}">Back to the publication &nearr;</a></header>
<main id="main" class="login-shell"><section class="login-intro"><span class="eyebrow">FINANCERSHUB / EDITORIAL STUDIO</span><h1>Good questions.<br><em>Clearer stories.</em></h1><p>A thoughtful space to write, review and publish practical explanations about money.</p><ul><li>Useful questions, clearly answered.</li><li>Sources that readers can follow.</li><li>Care in every published story.</li></ul><small>Independent thinking. Considered publishing.</small></section>
<section class="login-card" aria-labelledby="login-title"><span class="eyebrow">WELCOME BACK</span><h2 id="login-title">Sign in to your studio</h2><p>Use your editorial team account to continue.</p>
@if($errors->any())<div id="login-errors" class="login-alert" role="alert" tabindex="-1">{{ $errors->first() }}</div>@endif
<form method="post" action="{{ route('login.store') }}">@csrf
<label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required>
<label for="password">Password</label><div class="password-control"><input id="password" name="password" type="password" autocomplete="current-password" required><button id="password-toggle" type="button" aria-controls="password" aria-pressed="false" hidden>Show</button></div>
<small id="caps-lock" hidden>Caps Lock is on.</small><button class="login-submit" type="submit">Sign in <span aria-hidden="true">&rarr;</span></button></form>
<p class="login-help">Need access or help signing in? Contact your site administrator.</p><div class="login-card-footer">Editorial staff access only</div></section></main>
<footer class="login-footer"><span>&copy; {{ date('Y') }} FinancersHub</span><a href="{{ route('editorial-policy') }}">Our editorial standards</a></footer></body></html>
