@extends('layouts.admin')
@section('title', 'Pemasaran Inbound — Zarnizar')
@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div><h1 class="h2">Pemasaran Inbound</h1><p class="mb-0">Konten yang membantu calon pelanggan memahami kebutuhan dan memulai percakapan.</p></div>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-primary">Screenshot & Analytics</a>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-6"><div class="card h-100"><div class="card-body"><h2 class="h5">KulaPOS</h2><p>Kasir Android offline. Piloting Rp500.000, harga normal Rp750.000. Konfirmasikan fitur dan cakupan sebelum penawaran.</p><a href="{{ route('kulapos') }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark">Lihat landing page</a></div></div></div>
    <div class="col-md-6"><div class="card h-100"><div class="card-body"><h2 class="h5">Jasa aplikasi</h2><p>Website, sistem informasi, inventori, ERP, dan integrasi API. Penawaran mengikuti ruang lingkup yang disepakati.</p><a href="{{ route('services') }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark">Lihat landing page</a></div></div></div>
</div>
<section class="card mb-4"><div class="card-body">
    <h2 class="h4">Enam draft artikel untuk ditinjau</h2>
    <p>Setiap draft berisi bahan awal dan CTA relevan. Tambahkan pengalaman serta gambar asli yang diizinkan, periksa fakta, lalu hapus catatan review sebelum publikasi. Tombol membuka draft yang sama jika sudah pernah dibuat; isinya tidak ditimpa.</p>
    <div class="list-group">
        @foreach($drafts as $slug => $draft)
            <div class="list-group-item d-flex justify-content-between align-items-start gap-3">
                <div><span class="badge bg-dark mb-2">{{ $draft['product'] }}</span><h3 class="h6">{{ $draft['title'] }}</h3><p class="small mb-0">{{ $draft['review'] }}</p></div>
                <form action="{{ route('admin.marketing.drafts.store', $slug) }}" method="POST">@csrf<button class="btn btn-sm btn-outline-primary text-nowrap" type="submit">Siapkan / buka draft</button></form>
            </div>
        @endforeach
    </div>
</div></section>
<section class="card mb-4"><div class="card-body">
    <h2 class="h4">Catatan mingguan di spreadsheet</h2>
    <p>Buat satu baris per minggu, landing page, dan sumber trafik. Catat angka agregat, tanpa nama atau nomor calon pelanggan. Tabel ini panduan pencatatan, bukan laporan hasil otomatis.</p>
    <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Kolom</th><th>Sumber / cara mengisi</th></tr></thead><tbody>
        <tr><td>Minggu mulai, landing page, sumber trafik</td><td>Misalnya tanggal awal minggu, /kulapos, Organic Search / status WA / Instagram.</td></tr>
        <tr><td>Sesi landing page</td><td>GA4: sesi menurut landing page dan sumber. Kosongkan jika analytics belum aktif.</td></tr>
        <tr><td>Klik WhatsApp</td><td>GA4: jumlah event contact_click, dipisahkan berdasarkan product dan source_page.</td></tr>
        <tr><td>Chat relevan yang masuk</td><td>Catat manual dari percakapan yang benar-benar diterima.</td></tr>
        <tr><td>Demo, proposal, closing</td><td>Catat jumlah aktual tiap tahap, bukan perkiraan dari klik.</td></tr>
        <tr><td>Nilai kontrak</td><td>Total nilai kesepakatan yang benar-benar disetujui pada minggu tersebut.</td></tr>
        <tr><td>Catatan & langkah berikutnya</td><td>Kendala berulang dan satu perbaikan prioritas untuk minggu depan.</td></tr>
    </tbody></table></div>
    <p class="small mb-0">Klik WhatsApp bukan bukti chat atau penjualan. Gunakan label WhatsApp: Baru Masuk, Minta Demo, Penawaran, Menunggu Keputusan, Deal, Tidak Lanjut.</p>
</div></section>
<section class="card mb-4"><div class="card-body">
    <h2 class="h4">Search Console & pengukuran</h2>
    <ol class="mb-3">
        <li>Setelah rilis, tambahkan properti Domain di Google Search Console untuk domain Anda. Pasang TXT verifikasi pada DNS menggunakan nilai dari Google.</li>
        <li>Jika memilih properti URL-prefix, masukkan token metode HTML tag di Pengaturan Website. Masukkan nilai content saja, lalu lakukan verifikasi.</li>
        <li>Pastikan APP_URL produksi memakai domain dan HTTPS yang benar. Periksa halaman produk, jasa, robots.txt, serta <a href="{{ route('sitemap') }}">sitemap.xml</a>.</li>
        <li>Kirim sitemap.xml melalui menu Sitemaps dan periksa URL landing page dengan URL Inspection. Pengindeksan dan peringkat tidak dijamin.</li>
        <li>Masukkan ID GA4 milik Anda di Pengaturan Website setelah memastikan tidak ada tag dari sistem lain. Kolom kosong berarti tag tidak dipasang.</li>
        <li>Periksa page_view, contact_click, dan demo_click di Realtime / DebugView. Parameter klik: product, source_page, placement, intent. Jangan mengirim data calon pelanggan ke analytics.</li>
    </ol>
    <p class="small mb-0"><a href="https://support.google.com/webmasters/answer/9008080" target="_blank" rel="noopener noreferrer">Panduan verifikasi Google</a> · <a href="https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap" target="_blank" rel="noopener noreferrer">Panduan sitemap</a> · <a href="https://developers.google.com/analytics/devguides/collection/ga4/events" target="_blank" rel="noopener noreferrer">Event GA4</a></p>
</div></section>
<section class="card mb-4"><div class="card-body">
    <h2 class="h4">WhatsApp Business</h2>
    <p><strong>Nomor bisnis:</strong> 08999770477. Perubahan nomor CTA dilakukan melalui konfigurasi dan persetujuan pemilik.</p>
    <h3 class="h6">Deskripsi profil</h3><p>Zarnizar — aplikasi kasir KulaPOS, website bisnis, dan pengembangan sistem web sesuai kebutuhan. Berbasis di Tasikmalaya. Konsultasi melalui WhatsApp.</p>
    <h3 class="h6">Pesan sambutan</h3><p>Halo, terima kasih sudah menghubungi Zarnizar. Boleh ceritakan jenis usaha dan kebutuhan Anda? Untuk informasi cepat, ketik 1 untuk KulaPOS, 2 untuk website, atau 3 untuk aplikasi kustom. Saya akan menanggapi pada jam layanan yang tercantum.</p>
    <h3 class="h6">Balasan cepat /kulapos</h3><p>KulaPOS adalah aplikasi kasir Android offline. Saat ini tersedia harga promo piloting Rp500.000 (harga normal Rp750.000). Saya bisa kirim demo dan menjelaskan cakupan layanan. Jenis usaha Anda apa?</p>
    <h3 class="h6">Balasan cepat /aplikasi</h3><p>Saya membantu pembuatan aplikasi web dan sistem informasi sesuai kebutuhan. Boleh tahu jenis usaha, masalah yang ingin diselesaikan, dan alur yang sekarang digunakan?</p>
    <h3 class="h6">Balasan cepat /demo</h3><p>Terima kasih. Saya dapat menjelaskan demo singkat sesuai kebutuhan. Mohon informasikan hari/jam di luar jam mengajar yang cocok, lalu saya konfirmasi ketersediaan.</p>
    <p class="small mb-0">Salin ke WhatsApp Business dan sesuaikan jam layanan Anda. Pengaturan pada aplikasi WhatsApp tidak diubah otomatis.</p>
</div></section>
<section class="card"><div class="card-body">
    <h2 class="h4">Rencana 30 hari</h2>
    <div class="table-responsive"><table class="table"><thead><tr><th>Minggu</th><th>Fokus</th></tr></thead><tbody><tr><td>1</td><td>Tinjau dua landing page, unggah screenshot asli, uji CTA, lengkapi profil WhatsApp Business.</td></tr><tr><td>2</td><td>Verifikasi Search Console, aktifkan analytics dengan ID asli, tinjau dua artikel.</td></tr><tr><td>3</td><td>Terbitkan dua artikel, buat dua video demo dengan data contoh, distribusikan melalui status WA / IG.</td></tr><tr><td>4</td><td>Lanjutkan artikel yang telah ditinjau, tindak lanjuti permintaan inbound, evaluasi angka mingguan.</td></tr></tbody></table></div>
    <p class="mb-0">Utamakan konten orisinal dan chat berkualitas. Evaluasi hasil sebelum mempertimbangkan iklan.</p>
</div></section>
@endsection
