@props(['path'])

@php
    $optimizedPath = $path . '.webp';
    $imagePath = Storage::disk('public')->exists($optimizedPath) ? $optimizedPath : $path;
@endphp

<img src="{{ Storage::url($imagePath) }}" {{ $attributes->merge(['decoding' => 'async']) }}>
