@props(['items' => []])
<nav class="bread" aria-label="Breadcrumb"><a href="{{ url('/') }}">Home</a>@foreach ($items as $item) / @if(!empty($item['url']))<a href="{{ $item['url'] }}">{{ $item['label'] }}</a>@else<span aria-current="page">{{ $item['label'] }}</span>@endif @endforeach</nav>
