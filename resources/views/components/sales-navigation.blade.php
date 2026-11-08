<nav class="sales-nav" aria-label="Navigasi utama">
    <div class="sales-shell sales-nav-inner">
        <a href="{{ route('site.home') }}" class="sales-brand" aria-label="Zarnizar, beranda">
            Z<span aria-hidden="true">/</span> ZARNIZAR<span class="sales-brand-dot" aria-hidden="true">.</span>
            <span class="hidden sm:inline-block ml-2 text-[10px] font-bold uppercase tracking-wider bg-navy-blue text-white px-2 py-0.5 rounded brutal-border">Edukasi</span>
        </a>
        <details class="sales-mobile-menu">
            <summary>Menu <span aria-hidden="true">☰</span></summary>
            <div class="sales-mobile-links">
                <a href="{{ route('pembelajaran.index') }}" class="font-bold text-navy-blue">🎓 Pembelajaran Python (8 JP)</a>
                <a href="{{ route('gtk2026') }}">Apresiasi GTK 2026</a>
                <a href="{{ route('site.home') }}#portfolio">Karya &amp; Inovasi Digital</a>
                <a href="{{ route('site.home') }}#blog">Artikel &amp; Catatan Belajar</a>
                <a href="{{ route('profil') }}">Profil &amp; Portofolio Pengampu</a>
                <hr class="border-gray-200 my-1">
                <a href="{{ route('kulapos') }}" class="text-xs text-gray-600">Produk / KulaPOS</a>
                <a href="{{ route('services') }}" class="text-xs text-gray-600">Konsultasi Layanan</a>
            </div>
        </details>
        <div class="sales-desktop-links">
            <a href="{{ route('pembelajaran.index') }}" @if(request()->is('pembelajaran*')) aria-current="page" @endif class="font-bold text-navy-blue">
                <i class="bi bi-mortarboard me-1"></i> Pembelajaran
            </a>
            <a href="{{ route('gtk2026') }}" @if(request()->is('gtk2026*')) aria-current="page" @endif>GTK 2026</a>
            <a href="{{ route('site.home') }}#portfolio">Karya Inovasi</a>
            <a href="{{ route('site.home') }}#blog">Artikel</a>
            <a href="{{ route('profil') }}" @if(request()->routeIs('profil')) class="font-bold text-navy-blue" @endif>Profil</a>
            <a href="{{ route('pembelajaran.index') }}" class="sales-nav-cta">Mulai Belajar <span aria-hidden="true">↗</span></a>
        </div>
    </div>
</nav>
