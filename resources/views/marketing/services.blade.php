@extends('layouts.marketing', [
    'pageTitle' => 'Jasa Pembuatan Aplikasi Web Tasikmalaya | Zarnizar',
    'pageDescription' => 'Pengembangan aplikasi web, ERP, sistem gudang, dan sistem informasi sesuai kebutuhan. Lihat portofolio dan konsultasikan proyek.',
])
@section('content')
<section class="sales-section sales-hero">
    <div class="sales-shell sales-hero-grid">
        <div class="sales-hero-copy">
            <span class="sales-kicker">Tasikmalaya · Konsultasi online</span>
            <h1 class="sales-title">Jasa Pembuatan <em>Aplikasi Web</em> dan Sistem Informasi di Tasikmalaya</h1>
            <p class="sales-intro">Merapikan transaksi, persediaan, laporan, dan alur kerja melalui sistem yang sesuai kebutuhan usaha.</p>
            <div class="sales-actions"><x-whatsapp-link product="jasa-aplikasi" source="jasa-aplikasi" position="hero">Konsultasi Kebutuhan Aplikasi</x-whatsapp-link><a href="#pengalaman" class="sales-text-link">Lihat pengalaman ↓</a></div>
        </div>
        <aside class="sales-product-poster">
            <p class="sales-eyebrow">Sistem mengikuti cara usaha Anda bekerja</p>
            <h2 style="font-size: clamp(32px, 4vw, 48px); line-height: 1.1; letter-spacing: -.04em; font-weight: 700; margin: 32px 0">Dari proses yang tersebar,<br>ke alur yang lebih tertata.</h2>
            <div class="sales-poster-price"><p class="sales-eyebrow">Biaya pengembangan</p><p style="font-size: 23px; line-height: 1.3; font-weight: 700">Mulai dari penawaran sesuai ruang lingkup.</p></div>
            <p class="sales-poster-caption">Diskusikan masalah utama, prioritas, dan alur kerja. Biaya serta waktu ditentukan setelah kebutuhan dipahami.</p>
        </aside>
    </div>
</section>
<div class="sales-strip"><div class="sales-shell sales-strip-inner"><span>01 / Pahami kebutuhan</span><span>02 / Sepakati ruang lingkup</span><span>03 / Bangun bertahap</span></div></div>
<section class="sales-section sales-white">
    <div class="sales-shell"><p class="sales-eyebrow">Layanan pengembangan</p><h2 class="sales-heading">Bagian mana yang ingin Anda rapikan?</h2>
        <div class="sales-grid sales-grid-three">
            @foreach([
                ['Pesanan & penjualan', 'Bantu tim mencatat pesanan, mengikuti status pekerjaan, dan menyusun alur penjualan yang lebih jelas.'],
                ['Inventori & gudang', 'Petakan barang masuk, keluar, perpindahan, dan kebutuhan informasi persediaan sesuai proses usaha.'],
                ['ERP percetakan', 'Hubungkan kebutuhan pesanan, produksi, dan administrasi percetakan melalui modul yang disepakati.'],
                ['Sistem informasi', 'Bangun pencatatan dan alur administrasi yang sesuai dengan pengguna, peran, serta kebutuhan laporan.'],
                ['Integrasi API', 'Diskusikan pertukaran data antarsistem berdasarkan API, akses, dan batasan layanan yang tersedia.'],
                ['Website bisnis', 'Jelaskan layanan, tampilkan portofolio, dan arahkan calon pelanggan ke jalur konsultasi yang jelas.'],
            ] as [$title, $description])
                <div class="sales-card"><span class="sales-card-number">0{{ $loop->iteration }} / LAYANAN</span><h3>{{ $title }}</h3><p>{{ $description }}</p></div>
            @endforeach
        </div>
        <p class="sales-small">Setiap proyek memiliki ruang lingkup tersendiri. Fitur, integrasi, migrasi data, dan dukungan dicantumkan dalam penawaran.</p>
    </div>
</section>
<section class="sales-section">
    <div class="sales-shell sales-two-column"><div><p class="sales-eyebrow">Cara kita bekerja</p><h2 class="sales-heading">Jelas dulu kebutuhannya. Lalu mulai membangun.</h2><p class="sales-intro">Mulai dengan diskusi sekitar 15 menit untuk memahami usaha Anda. Kita tentukan prioritas dan hasil yang perlu dicapai sebelum membicarakan pengerjaan.</p><x-whatsapp-link product="jasa-aplikasi" source="jasa-aplikasi" position="process">Mulai Diskusi Kebutuhan</x-whatsapp-link></div>
        <ol class="sales-steps"><li><h3>Diskusi awal</h3><p>Kenali usaha, kendala, pengguna, dan alur yang berjalan.</p></li><li><h3>Analisis kebutuhan</h3><p>Petakan prioritas, data, serta batasan sistem.</p></li><li><h3>Penawaran & ruang lingkup</h3><p>Sepakati hasil, biaya, jadwal, termin, dan kriteria penerimaan.</p></li><li><h3>Pengerjaan sesuai termin</h3><p>Pengembangan dan peninjauan mengikuti tahapan yang disepakati.</p></li><li><h3>Uji coba bersama</h3><p>Periksa alur dan hasil terhadap kriteria penerimaan.</p></li><li><h3>Serah terima & dukungan</h3><p>Konfirmasikan akses, panduan, dan cakupan dukungan.</p></li></ol>
    </div>
</section>
<section id="pengalaman" class="sales-section sales-white">
    <div class="sales-shell"><p class="sales-eyebrow">Portofolio pengembang</p><h2 class="sales-heading">Pengalaman membangun sistem nyata.</h2><p class="sales-intro">Lihat proyek publik untuk mengenal pendekatan dan pekerjaan pengembangan Zarnizar.</p>
        @if($portfolios->isNotEmpty())
            <div class="sales-grid sales-grid-three">
                @foreach($portfolios as $project)
                    <article class="sales-card sales-project-card">
                        @if($project->image)<x-storage-image :path="$project->image" :alt="$project->title" loading="lazy" class="sales-project-image" />@endif
                        <div class="sales-project-copy"><h3>{{ $project->title }}</h3><p>{{ Str::limit(strip_tags($project->description), 150) }}</p><a href="{{ route('portfolio.show', $project->slug) }}" class="sales-text-link">Lihat studi proyek →</a></div>
                    </article>
                @endforeach
            </div>
        @endif
        <a href="{{ route('site.home') }}#portfolio" class="sales-button sales-button-light">Jelajahi Portofolio</a>
    </div>
</section>
<section class="sales-section"><div class="sales-shell"><p class="sales-eyebrow">Sebelum bekerja sama</p><h2 class="sales-heading">Pertanyaan tentang proyek Anda.</h2>
    <div class="sales-faq">
        <details><summary>Berapa biaya pembuatan aplikasi?</summary><p>Mulai dari penawaran sesuai ruang lingkup. Biaya mempertimbangkan alur, jumlah modul, integrasi, migrasi, dan dukungan yang diperlukan. Penawaran dibuat setelah kebutuhan dipahami.</p></details>
        <details><summary>Berapa lama pengerjaannya?</summary><p>Jadwal mengikuti ruang lingkup, kesiapan bahan, integrasi, serta tahapan review. Estimasi dan tonggak pengerjaan dibahas dalam penawaran.</p></details>
        <details><summary>Siapa yang mengelola hosting dan domain?</summary><p>Pengelolaan hosting, domain, akses, dan biaya layanan pihak ketiga disepakati sebelum pengerjaan. Jelaskan jika Anda sudah memiliki infrastruktur sendiri.</p></details>
        <details><summary>Apakah ada maintenance?</summary><p>Cakupan dukungan, masa perbaikan, dan maintenance lanjutan dicantumkan dalam kesepakatan. Pengembangan fitur baru dibahas sebagai ruang lingkup tambahan.</p></details>
        <details><summary>Bagaimana kepemilikan kode dan akses sistem?</summary><p>Kepemilikan kode, lisensi komponen pihak ketiga, repositori, serta akses operasional dijelaskan dalam perjanjian dan serah terima proyek.</p></details>
        <details><summary>Apakah pembayaran bisa bertahap?</summary><p>Termin pembayaran dapat dibahas berdasarkan tahapan pekerjaan dan hasil yang disepakati. Rinciannya tertulis dalam penawaran.</p></details>
    </div>
</div></section>
<x-sales-related-posts :posts="$relatedPosts" />
<x-sales-contact product="jasa-aplikasi" source="jasa-aplikasi" />
@endsection
