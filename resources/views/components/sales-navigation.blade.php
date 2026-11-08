<nav class="sales-nav" aria-label="Navigasi utama">
    <div class="sales-shell sales-nav-inner">
        <a href="{{ route('site.home') }}" class="sales-brand" aria-label="Zarnizar, beranda">Z<span aria-hidden="true">/</span> ZARNIZAR<span class="sales-brand-dot" aria-hidden="true">.</span></a>
        <details class="sales-mobile-menu">
            <summary>Menu <span aria-hidden="true">☰</span></summary>
            <div class="sales-mobile-links">
                <a href="{{ route('kulapos') }}">Produk / KulaPOS</a>
                <a href="{{ route('services') }}">Layanan</a>
                <a href="{{ route('site.home') }}#portfolio">Portofolio</a>
                <a href="{{ route('site.home') }}#blog">Artikel</a>
                <a href="{{ route('site.home') }}#about">Tentang developer</a>
                <a href="{{ route('services') }}#konsultasi">Konsultasi</a>
            </div>
        </details>
        <div class="sales-desktop-links">
            <a href="{{ route('kulapos') }}" @if(request()->routeIs('kulapos')) aria-current="page" @endif>Produk</a>
            <a href="{{ route('services') }}" @if(request()->routeIs('services')) aria-current="page" @endif>Layanan</a>
            <a href="{{ route('site.home') }}#portfolio">Portofolio</a>
            <a href="{{ route('site.home') }}#blog">Artikel</a>
            <a href="{{ route('services') }}#konsultasi" class="sales-nav-cta">Konsultasi <span aria-hidden="true">↗</span></a>
        </div>
    </div>
</nav>
