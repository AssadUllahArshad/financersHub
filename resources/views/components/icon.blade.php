@props(['name' => 'article'])
@php
    $paths = [
        'home' => 'M3 11l9-8 9 8 M5 10v11h5v-7h4v7h5V10',
        'clock' => 'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0 M12 7v5l3 2',
        'list' => 'M9 6h12 M9 12h12 M9 18h12 M3 6h1 M3 12h1 M3 18h1',
        'article' => 'M6 3h9l4 4v14H6z M15 3v5h4 M9 12h7 M9 16h7',
        'search' => 'M21 21l-5-5 M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0',
        'calculator' => 'M6 2h12v20H6z M9 6h6 M9 10h1 M14 10h1 M9 14h1 M14 14h1 M9 18h1 M14 18h1',
        'wallet' => 'M3 6h17v15H3z M3 6V3h14v3 M15 11h6v5h-6z',
        'chart' => 'M4 3v18h17 M8 16v-5 M13 16V7 M18 16V4',
        'bank' => 'M3 8l9-5 9 5z M5 10v8 M10 10v8 M14 10v8 M19 10v8 M3 21h18',
        'card' => 'M3 5h18v14H3z M3 9h18 M6 15h4',
        'briefcase' => 'M3 7h18v14H3z M8 7V3h8v4 M3 12h18 M10 12v3h4v-3',
        'shield' => 'M12 2l8 3v6c0 5-4 9-8 11-4-2-8-6-8-11V5z M8 12l3 3 5-6',
        'user' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0 M4 22v-3a8 8 0 0 1 16 0v3',
        'mail' => 'M3 5h18v14H3z M3 5l9 8 9-8',
        'terminal' => 'M3 4h18v16H3z M7 8l4 4-4 4 M13 16h4',
        'lock' => 'M5 10h14v12H5z M8 10V6a4 4 0 0 1 8 0v4 M12 15v3',
        'save' => 'M3 3h15l3 3v15H3z M7 3v6h9V3 M7 21v-8h10v8',
        'plus' => 'M12 4v16 M4 12h16',
        'logout' => 'M9 3H3v18h6 M8 12h13 M16 7l5 5-5 5',
        'menu' => 'M4 6h16 M4 12h16 M4 18h16',
        'theme' => 'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0 M12 3v18',
        'sun' => 'M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0 M12 2v2 M12 20v2 M2 12h2 M20 12h2 M5 5l1.5 1.5 M17.5 17.5L19 19 M5 19l1.5-1.5 M17.5 6.5L19 5',
        'moon' => 'M20.9 13A9 9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z',
        'download' => 'M12 3v12 M7 10l5 5 5-5 M4 16v5h16v-5',
        'link' => 'M10 14l4-4 M8 16l-2 2a4 4 0 0 1-5-5l4-4a4 4 0 0 1 5 0 M14 8l2-2a4 4 0 0 1 5 5l-4 4a4 4 0 0 1-5 0',
        'arrow' => 'M5 12h14 M13 6l6 6-6 6',
        'help' => 'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0 M9 8a3 3 0 0 1 6 1c0 2-3 2-3 5 M12 17h.01',
    ];
@endphp
<svg {{ $attributes->class(['ui-icon']) }} width="20" height="20" viewBox="0 0 24 24" fill="none"
    stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
    focusable="false">
    <path d="{{ $paths[$name] ?? $paths['article'] }}" />
</svg>
