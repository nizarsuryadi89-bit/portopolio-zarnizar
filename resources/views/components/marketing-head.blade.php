@props(['title', 'description', 'canonical', 'settings' => [], 'robots' => 'index, follow', 'image' => null])
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:site_name" content="Zarnizar">
<meta property="og:locale" content="id_ID">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
@if($image)
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:image" content="{{ $image }}">
@endif
@if(!empty($settings['google_site_verification']))
    <meta name="google-site-verification" content="{{ $settings['google_site_verification'] }}">
@endif
@if(preg_match('/^G-[A-Z0-9]{4,20}$/', $settings['ga4_measurement_id'] ?? ''))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga4_measurement_id'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
        window.gtag('js', new Date());
        window.gtag('config', {{ Illuminate\Support\Js::from($settings['ga4_measurement_id']) }}, {
            page_location: window.location.origin + window.location.pathname,
            page_referrer: document.referrer ? new URL(document.referrer).origin + new URL(document.referrer).pathname : '',
            allow_google_signals: false,
            allow_ad_personalization_signals: false
        });
    </script>
@endif
