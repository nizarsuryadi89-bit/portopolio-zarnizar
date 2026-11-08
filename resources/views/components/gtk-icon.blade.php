@props(['name' => 'grid'])
@php
    $paths = [
        'arrow-right' => 'M5 12h14m-6-6 6 6-6 6', 'arrow-left' => 'M19 12H5m6-6-6 6 6 6',
        'expand' => 'M8 3H3v5m13-5h5v5M3 16v5h5m13-5v5h-5',
        'folder' => 'M3 7V5h6l2 2h10v13H3V7Z',
        'file' => 'M14 3H5v18h14V8l-5-5Zm0 0v5h5M8 13h8m-8 4h6',
        'grid' => 'M3 3h7v7H3V3Zm11 0h7v7h-7V3ZM3 14h7v7H3v-7Zm11 0h7v7h-7v-7Z',
        'clock' => 'M12 8v5l3 2M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z',
        'heart' => 'm12 21-9-9C-3 5 6-2 12 6 18-2 27 5 21 12l-9 9Z',
        'message' => 'M21 11a9 9 0 0 1-9 9H3l2-4a9 9 0 1 1 16-5ZM8 10h8m-8 4h5',
        'book' => 'M12 5c-3-2-6-2-10-1v15c4-1 7-1 10 1m0-15c3-2 6-2 10-1v15c-4-1-7-1-10 1V5Z',
        'check' => 'm5 12 4 4L19 6M21 12v9H3V3h12',
        'star' => 'm12 3 3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1 3-6Z',
        'vote' => 'm8 3 4 4 4-4m-4 4v8M5 11H3v10h18V11h-2M8 17h8',
        'users' => 'M16 21v-3a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v3m14-16a4 4 0 0 1 0 8m4 8v-3a4 4 0 0 0-3-4M13 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z',
        'code' => 'm8 6-6 6 6 6m8-12 6 6-6 6m-3-15-2 18',
        'search' => 'm21 21-6-6M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z',
        'close' => 'm6 6 12 12M6 18 18 6', 'print' => 'M7 8V3h10v5M7 17H3V8h18v9h-4M7 14h10v7H7v-7Z',
    ];
@endphp
<svg {{ $attributes->merge(['class' => 'gtk-icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['grid'] }}" /></svg>
