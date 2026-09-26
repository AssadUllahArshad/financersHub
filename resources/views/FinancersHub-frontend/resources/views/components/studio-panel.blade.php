@props(['title','eyebrow' => null])
<section {{ $attributes->class(['studio-panel']) }}>
  <div class="panel-head"><div>@if($eyebrow)<span class="admin-kicker">{{ $eyebrow }}</span>@endif<h2>{{ $title }}</h2></div></div>
  {{ $slot }}
</section>
