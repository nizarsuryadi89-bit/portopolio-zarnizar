<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-marketing-head :title="$pageTitle" :description="$pageDescription" :canonical="url()->current()" :settings="$settings" :image="!empty($settings['kulapos_screenshot']) && request()->routeIs('kulapos') ? url(Storage::url($settings['kulapos_screenshot'])) : null" />
    @if(!empty($settings['favicon']))
        <link rel="icon" href="{{ Storage::url($settings['favicon']) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="sales-page">
    <a href="#main-content" class="sales-skip-link">Langsung ke isi</a>
    <x-sales-navigation />
    <main id="main-content">@yield('content')</main>
    <footer class="sales-footer">
        <div class="sales-shell sales-footer-inner">
            <div><a href="{{ route('site.home') }}" class="sales-brand">ZARNIZAR.</a><p>Solusi Digital untuk UMKM & Bisnis</p></div>
            <div class="sales-footer-links">
                <a href="{{ route('site.home') }}#about">Tentang developer</a>
                <a href="{{ route('site.home') }}#portfolio">Portofolio</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @else
                    <a href="{{ route('login') }}">Login admin</a>
                @endauth
            </div>
            <p class="sales-small">© {{ date('Y') }} Zarnizar</p>
        </div>
    </footer>
</body>
</html>
