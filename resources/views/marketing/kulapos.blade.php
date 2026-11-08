@extends('layouts.marketing', [
    'pageTitle' => 'KulaPOS — Aplikasi Kasir Offline UMKM | Zarnizar',
    'pageDescription' => 'Kenali KulaPOS, aplikasi kasir Android offline untuk UMKM. Lihat demo, paket promo, dan konsultasikan kebutuhan toko.',
])
@section('content')
<section class="sales-section sales-hero">
    <div class="sales-shell sales-hero-grid">
        <div class="sales-hero-copy">
            <span class="sales-kicker">KulaPOS / Android / Offline</span>
            <h1 class="sales-title">Aplikasi Kasir <em>Offline</em> untuk UMKM — KulaPOS</h1>
            <p class="sales-intro">Catat transaksi usaha menggunakan aplikasi Android tanpa harus selalu tersambung internet. Cocok untuk pemilik usaha yang ingin beralih dari pencatatan manual.</p>
            <div class="sales-actions">
                <x-whatsapp-link product="kulapos" source="kulapos" position="hero" intent="demo">Minta Demo KulaPOS</x-whatsapp-link>
                <a href="#demo" class="sales-text-link" data-demo-product="kulapos">Kenali alur demo ↓</a>
            </div>
            <p class="sales-footnote">Mulai dari kebutuhan toko Anda. Lihat demo sebelum menentukan paket.</p>
        </div>
        <aside class="sales-product-poster" aria-label="Harga piloting KulaPOS">
            <p class="sales-eyebrow">Dari catatan manual ke langkah berikutnya</p>
            <span class="sales-product-name">KulaPOS<span style="color: var(--sales-lime)">.</span></span>
            <p class="sales-product-subtitle">Kasir Android. Tetap bisa offline.</p>
            <div class="sales-poster-price">
                <p class="sales-eyebrow">Harga peluncuran / piloting</p>
                <p class="sales-original-price"><s>Rp750.000</s> <span class="sales-small">harga normal</span></p>
                <p class="sales-price">Rp500.000</p>
            </div>
            <p class="sales-poster-caption">Rincian layanan mengikuti penawaran dan persetujuan. Penyesuaian khusus dikonsultasikan dan tidak otomatis termasuk harga piloting.</p>
        </aside>
    </div>
</section>
<div class="sales-strip"><div class="sales-shell sales-strip-inner"><span>01 / Android</span><span>02 / Pencatatan offline</span><span>03 / Demo sebelum memutuskan</span></div></div>
<section class="sales-section sales-white">
    <div class="sales-shell">
        <p class="sales-eyebrow">Berangkat dari keseharian usaha</p>
        <h2 class="sales-heading">Masih mengandalkan buku untuk mencatat penjualan?</h2>
        <div class="sales-grid sales-grid-three">
            <div class="sales-card"><span class="sales-card-number">01 / PENCATATAN</span><h3>Ingin mulai beralih dari manual?</h3><p>Kenali alur pencatatan transaksi melalui aplikasi Android. Uji apakah alurnya sesuai dengan kebiasaan di toko Anda.</p></div>
            <div class="sales-card"><span class="sales-card-number">02 / KONEKSI</span><h3>Internet tidak selalu tersedia?</h3><p>KulaPOS dirancang untuk pencatatan kasir offline. Konfirmasikan kebutuhan perangkat dan penggunaan saat demo.</p></div>
            <div class="sales-card"><span class="sales-card-number">03 / KECOCOKAN</span><h3>Butuh penjelasan sebelum memilih?</h3><p>Ceritakan jenis usaha dan kebutuhan Anda. Kita periksa kecocokan alur serta cakupan paket bersama.</p></div>
        </div>
    </div>
</section>
<section id="demo" class="sales-section">
    <div class="sales-shell sales-two-column">
        <div>
            <p class="sales-eyebrow">Lihat alur sebenarnya</p>
            <h2 class="sales-heading">Demo memakai skenario toko Anda.</h2>
            <p class="sales-intro">Ceritakan jenis usaha, perangkat Android yang tersedia, dan cara mencatat transaksi saat ini. Demo menggunakan data contoh agar Anda dapat menilai prosesnya dengan aman.</p>
            <x-whatsapp-link product="kulapos" source="kulapos" position="demo" intent="demo">Jadwalkan Demo</x-whatsapp-link>
        </div>
        <div>
            @if(!empty($settings['kulapos_screenshot']) || !empty($settings['kulapos_screenshot_secondary']))
                @if(!empty($settings['kulapos_screenshot']))
                <figure class="sales-figure">
                    <x-storage-image :path="$settings['kulapos_screenshot']" alt="Tampilan aplikasi KulaPOS pada perangkat Android" loading="lazy" class="sales-image" />
                    <figcaption>Tampilan KulaPOS. Konfirmasikan versi dan alur yang sesuai kebutuhan Anda saat demo.</figcaption>
                </figure>
                @endif
                @if(!empty($settings['kulapos_screenshot_secondary']))
                    <figure class="sales-figure" style="margin-top: 24px">
                        <x-storage-image :path="$settings['kulapos_screenshot_secondary']" alt="Tampilan tambahan alur penggunaan KulaPOS" loading="lazy" class="sales-image" />
                    </figure>
                @endif
            @else
                <div class="sales-card">
                    <p class="sales-eyebrow">Yang dibahas saat demo</p>
                    <h3>Kenali. Coba. Tanyakan.</h3>
                    <ul class="sales-checklist"><li>Alur pencatatan transaksi dengan data contoh.</li><li>Penggunaan offline di perangkat Android.</li><li>Kebutuhan dan batasan yang relevan untuk toko Anda.</li><li>Cakupan paket dan langkah mulai menggunakan.</li></ul>
                    <p class="sales-footnote">Minta tampilan aplikasi dan penjelasan langsung melalui WhatsApp.</p>
                </div>
            @endif
        </div>
    </div>
</section>
<section class="sales-section sales-white">
    <div class="sales-shell sales-two-column">
        <div><p class="sales-eyebrow">Paket yang jelas sejak awal</p><h2 class="sales-heading">Mulai dengan kebutuhan yang disepakati.</h2><p class="sales-intro">Penawaran piloting Rp500.000 menjadi titik awal pembicaraan. Pastikan rincian layanan dan kebutuhan perangkat telah dikonfirmasi sebelum menyetujui.</p><ul class="sales-checklist"><li>Aplikasi kasir Android dengan pencatatan offline.</li><li>Penjelasan penggunaan melalui demo.</li><li>Rencana instalasi dan orientasi sesuai penawaran.</li><li>Penyesuaian khusus dibahas sebagai ruang lingkup tersendiri.</li></ul></div>
        <ol class="sales-steps"><li><h3>Konsultasi kebutuhan</h3><p>Ceritakan jenis usaha dan alur transaksi Anda.</p></li><li><h3>Demo aplikasi</h3><p>Periksa alur dengan data contoh dan tanyakan batasannya.</p></li><li><h3>Kesepakatan & instalasi</h3><p>Konfirmasikan perangkat, paket, dan layanan sebelum mulai.</p></li><li><h3>Orientasi penggunaan</h3><p>Pelajari alur yang disepakati dan cara mendapatkan bantuan.</p></li></ol>
    </div>
</section>
<section class="sales-section">
    <div class="sales-shell"><p class="sales-eyebrow">Sebelum mulai</p><h2 class="sales-heading">Pertanyaan tentang KulaPOS.</h2>
        <div class="sales-faq">
            <details><summary>Apakah KulaPOS harus selalu terhubung ke internet?</summary><p>KulaPOS adalah aplikasi kasir Android offline untuk pencatatan transaksi. Tanyakan kebutuhan koneksi pada proses instalasi atau layanan lain saat demo.</p></details>
            <details><summary>Apa yang termasuk harga Rp500.000?</summary><p>Ini harga peluncuran/piloting, dengan harga normal Rp750.000. Rincian paket, instalasi, orientasi, dan dukungan mengikuti penawaran serta persetujuan. Minta rincian tertulis sebelum membeli.</p></details>
            <details><summary>Apakah aplikasi bisa disesuaikan untuk toko saya?</summary><p>Kebutuhan penyesuaian dapat dikonsultasikan. Perubahan khusus tidak otomatis termasuk dalam Rp500.000 dan akan dinilai berdasarkan ruang lingkupnya.</p></details>
            <details><summary>Bagaimana jika saya membutuhkan printer atau beberapa perangkat?</summary><p>Sebutkan perangkat dan alur yang dibutuhkan saat konsultasi. Dukungan printer, sinkronisasi, multi-cabang, pembayaran, serta fitur tambahan perlu diverifikasi melalui demo dan penawaran sebelum Anda memutuskan.</p></details>
            <details><summary>Bagaimana cara melihat demo?</summary><p>Klik Minta Demo KulaPOS, lalu kirim jenis usaha dan jadwal yang Anda inginkan. Jadwal akan dikonfirmasi melalui WhatsApp. Demo menggunakan data contoh.</p></details>
        </div>
    </div>
</section>
<x-sales-related-posts :posts="$relatedPosts" />
<x-sales-contact product="kulapos" source="kulapos" />
@endsection
