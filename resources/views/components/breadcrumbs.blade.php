@props(['items' => []])
<nav {{ $attributes->class(['bread', 'page-breadcrumbs']) }} aria-label="{{ __('Breadcrumb') }}">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ \App\Support\Localization::route('home') }}"><x-icon name="home" />{{ __('Home') }}</a></li>
        @foreach ($items as $item)
            <li class="breadcrumb-item"><span class="breadcrumb-separator" aria-hidden="true">/</span>
                @if (!empty($item['url']) && !$loop->last)<a href="{{ $item['url'] }}">{{ __($item['label'] ?? '') }}</a>@else<span
                        aria-current="page">{{ __($item['label'] ?? '') }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
