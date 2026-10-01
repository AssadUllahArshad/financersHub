@php
$groups = ['publication'=>['site','editorial','info','integration'],'studio'=>['site','editorial','info','admin','integration','studio-ui'],'login'=>['site','login']];
$manifestPath = public_path('assets/generated/manifest.json');
$latestSource = max(array_map(fn($style) => filemtime(public_path('assets/'.$style.'.css')), $groups[$group]));
$manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath),true) : [];
@endphp
@if(isset($manifest[$group]) && filemtime($manifestPath) >= $latestSource && is_file(public_path('assets/generated/'.$manifest[$group])))
<link rel="stylesheet" href="{{ asset('assets/generated/'.$manifest[$group]) }}">
@else
@foreach($groups[$group] as $style)<link rel="stylesheet" href="{{ asset('assets/'.$style.'.css') }}">@endforeach
@endif
