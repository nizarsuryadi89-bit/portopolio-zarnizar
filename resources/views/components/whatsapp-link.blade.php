@props(['product' => 'jasa-aplikasi', 'source' => 'home', 'position' => 'content', 'intent' => 'consultation'])
@php
    $product = array_key_exists($product, config('marketing.whatsapp_messages')) ? $product : 'jasa-aplikasi';
    $whatsappUrl = 'https://wa.me/'.config('marketing.whatsapp_number').'?text='.rawurlencode(config('marketing.whatsapp_messages.'.$product));
@endphp
<a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" data-product="{{ $product }}" data-lead-source="{{ $source }}" data-lead-position="{{ $position }}" data-lead-intent="{{ $intent }}" {{ $attributes->merge(['class' => 'sales-button']) }}>
    {{ $slot }} <span aria-hidden="true">↗</span>
</a>
