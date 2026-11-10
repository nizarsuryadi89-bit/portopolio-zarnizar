@php
    $siteLogo = null;
    if (!empty($settings['favicon']) && Storage::disk('public')->exists($settings['favicon'])) {
        $siteLogo = Storage::url($settings['favicon']);
    } elseif (file_exists(public_path('apple-touch-icon.png'))) {
        $siteLogo = asset('apple-touch-icon.png');
    } elseif (file_exists(public_path('pwa-icon-192.png'))) {
        $siteLogo = asset('pwa-icon-192.png');
    }
@endphp

<nav class="sales-nav" aria-label="Navigasi utama">
    <div class="sales-shell sales-nav-inner">
        <a href="{{ route('site.home') }}" class="sales-brand" aria-label="Zarnizar, beranda">
            @if($siteLogo)
                <img src="{{ $siteLogo }}" alt="Logo Zarnizar"
                    class="w-8 h-8 rounded border-2 border-black object-cover shrink-0 shadow-[2px_2px_0px_#000]" width="32"
                    height="32">
            @endif
            <span>Z<span aria-hidden="true">/</span> ZARNIZAR<span class="sales-brand-dot"
                    aria-hidden="true">.</span></span>
            <span
                class="hidden sm:inline-block ml-1 text-[10px] font-bold uppercase tracking-wider bg-navy-blue text-white px-2 py-0.5 rounded brutal-border">Edukasi</span>
        </a>
        <details class="sales-mobile-menu">
            <summary>Menu <span aria-hidden="true">☰</span></summary>
            <div class="sales-mobile-links">
                <a href="{{ route('site.home') }}"
                    class="font-bold {{ request()->routeIs('site.home') ? 'text-navy-blue' : '' }}">
                    <i class="bi bi-house-door me-1"></i> Beranda
                </a>
                <a href="{{ route('pembelajaran.index') }}" class="font-bold text-navy-blue">
                    🎓 Pembelajaran Python (8 JP)
                </a>
                <a href="{{ route('gtk2026') }}">Apresiasi GTK 2026</a>
                <a href="{{ route('site.home') }}#portfolio">Karya &amp; Inovasi Digital</a>
                <a href="{{ route('site.home') }}#blog">Artikel &amp; Catatan Belajar</a>
                <a href="{{ route('profil') }}">Profil &amp; Curriculum Vitae (CV)</a>
                <hr class="border-gray-200 my-1">
                <a href="{{ route('kulapos') }}" class="text-xs text-gray-600">Produk / KulaPOS</a>
                <a href="{{ route('services') }}" class="text-xs text-gray-600">Konsultasi Layanan</a>
            </div>
        </details>
        <div class="sales-desktop-links">
            <a href="{{ route('site.home') }}" @if(request()->routeIs('site.home')) aria-current="page"
            class="font-bold text-navy-blue" @endif>
                <i class="bi bi-house-door me-1"></i> Beranda
            </a>
            <a href="{{ route('pembelajaran.index') }}" @if(request()->is('pembelajaran*')) aria-current="page" @endif
                class="font-bold text-navy-blue">
                <i class="bi bi-mortarboard me-1"></i> Pembelajaran
            </a>
            <a href="{{ route('gtk2026') }}" @if(request()->is('gtk2026*')) aria-current="page" @endif>GTK 2026</a>
            <a href="{{ route('site.home') }}#portfolio">Karya Inovasi</a>
            <a href="{{ route('site.home') }}#blog">Artikel</a>
            <a href="{{ route('profil') }}" @if(request()->routeIs('profil')) class="font-bold text-navy-blue"
            @endif>Profil &amp; CV</a>
            <a href="{{ route('pembelajaran.index') }}" class="sales-nav-cta">Mulai Belajar <span
                    aria-hidden="true">↗</span></a>
        </div>
    </div>
</nav>