# GTK 2026 PRESENTATION MICROSITE

## 1. Overview

Presentasi delapan slide untuk Nizar Suryadi, S.T., Guru Informatika SMA Negeri 4 Tasikmalaya, kategori Guru Dedikatif pada Apresiasi GTK 2026. Alur bicara berjumlah 360 detik. Konten mengikuti brief peserta; angka dan narasi bukan hasil verifikasi database SASTI.

Implementasi terpisah dari halaman beranda, blog, portofolio, dan admin. Tidak ada dependency atau tabel database baru. Foto Nizar, logo SMA Negeri 4 Tasikmalaya, dan tiga foto keluarga sudah dipasang dari berkas peserta. Sepuluh berkas tersedia pada bukti privat: lima screenshot SASTI, sampul publikasi, serta empat foto kegiatan PILKETOS dan kelas. Bukti lain yang belum tersedia tetap diberi label dan tidak diganti dengan bukti rekaan.

## 2. URL

- Route utama: `/gtk2026`, nama `gtk2026`.
- Mode presentasi: `/gtk2026?present=1`.
- Catatan presenter: `/gtk2026?present=1&notes=1`.
- Slide tertentu: tambahkan `#slide-1` sampai `#slide-8`.
- Bukti privat: `/gtk2026/evidence`, menggunakan login admin existing.

Boost `get-absolute-url` pada environment ini menghasilkan `http://localhost:8000/gtk2026`. Jalankan `php artisan serve --no-interaction` untuk server lokal. Target setelah deployment pada domain existing: `https://zarnizar.com/gtk2026`. Deployment produksi tidak dilakukan dalam pekerjaan ini.

## 3. Architecture

### Audit sebelum implementasi

| Area | Kondisi existing dan keputusan |
| --- | --- |
| Backend | PHP 8.4, Laravel 13.31.0, Laravel UI 4.6.3. Versi diperiksa melalui Composer. |
| Routing | `routes/web.php`; halaman pemasaran memakai `LandingPageController`, admin memakai middleware `auth`. GTK menambahkan controller tersendiri dan enam route. |
| Template | Blade; layout marketing menggunakan navigasi, footer, serta font Space Grotesk. GTK memiliki dokumen Blade mandiri agar widget dan tampilan blog tidak ikut presentasi. |
| Assets | Vite 8.3.0 terpasang, Laravel Vite Plugin 3; input existing dipertahankan dan dua input GTK ditambahkan. |
| CSS | Tailwind 4 dan Bootstrap 5 existing; CSS GTK diberi prefix `gtk-` dan dimuat hanya pada route GTK. |
| JavaScript | JavaScript native, Bootstrap Offcanvas, modul PWA dan marketing. GTK memakai modul native tersendiri tanpa framework/dependency baru. |
| Font | Stack Space Grotesk existing dengan fallback Avenir Next/system. GTK tidak mengambil font dari internet; tanpa font lokal, system font langsung digunakan. |
| Public assets | `public/build`, icon/PWA, serta gambar di `storage/app/public`. Symlink `public/storage` tidak ada saat audit; GTK tidak bergantung padanya. |
| Authentication | Login Laravel UI, registrasi nonaktif, admin memakai `auth`. Perlindungan bukti privat mengikuti boundary admin tersebut. |
| Responsive | Existing menggunakan Tailwind/Bootstrap dan CSS media query. GTK memakai layout viewport, grid, dan media query tersendiri. Mobile dan layar sangat pendek boleh scroll di dalam slide. |
| Offline | Existing memiliki service worker admin di root. Worker GTK menggunakan scope `/gtk2026` dan cache prefix tersendiri; worker existing tidak diubah. |
| Deployment | Aplikasi Laravel dengan document root `public`, asset Vite hasil build, environment/DB remote. Tidak ditemukan metadata Git pada checkout ini; tidak ada commit atau deployment yang dilakukan. |

Graph codebase bertanggal 30 Oktober 2026 memiliki bagian yang belum terindeks atau berubah. Klaim audit dikonfirmasi dengan file sumber, daftar route, versi paket, dan inventaris aset lokal. Tidak ada `.ai/rules` pada checkout ini. Boost `search-docs` dan database remote tidak dapat menjangkau host dari sandbox; API yang dipakai dikonfirmasi melalui source framework terpasang dan feature tests. Tidak ada perubahan `.env` atau akses tulis database produksi.

### File dibuat

- `app/Http/Controllers/Gtk2026Controller.php`: rendering, penyaringan metadata bukti, media allowlist, worker.
- `config/gtk2026.php`: identitas, slide, durasi/catatan, statistik, timeline, modul, dampak, dan indeks bukti.
- `resources/views/gtk2026/presentation.blade.php`: delapan slide dan kontrol.
- `resources/views/gtk2026/evidence-panel.blade.php`: dialog pencarian bukti.
- `resources/views/gtk2026/evidence.blade.php`: indeks bukti privat.
- `resources/views/gtk2026/slide-media.blade.php`: pilihan video/dokumentasi di slide 5 dan 6.
- `resources/views/gtk2026/video-player.blade.php`: pemutar YouTube yang dimuat setelah klik.
- `resources/views/gtk2026/ecosystem-content.blade.php` dan `impact-content.blade.php`: konten data dan gambar untuk pilihan dokumentasi.
- `resources/views/components/gtk-icon.blade.php`: icon SVG inline tanpa request eksternal.
- `resources/css/gtk2026.css`: tampilan, responsive, reduced motion, dan print.
- `resources/js/gtk2026.js`: navigasi, pencarian, fullscreen, swipe, timer, dan registrasi worker.
- `resources/js/gtk2026-worker.js`: sumber worker yang disajikan controller dengan daftar asset Vite aktual.
- `tests/Feature/Gtk2026Test.php`: pengujian HTTP dan batas keamanan.
- `tests/Unit/Gtk2026Client.test.js`: keyboard, input, timer, pemutaran video, dan pergantian media.
- `tests/Unit/Gtk2026Worker.test.js`: cache offline dan isolasi worker.
- `docs/GTK2026_PRESENTATION.md`: panduan ini.

### File diubah

- `routes/web.php`: enam route GTK dan import controller.
- `vite.config.js`: dua entry asset GTK.
- `public/build/manifest.json` dan bundle dalam `public/build/assets/`: hasil `npm run build`. Vite membangun ulang asset existing; sumber halaman existing tidak diubah.

## 4. Routes

| Method | Path | Access |
| --- | --- | --- |
| GET | `/gtk2026` | Publik, seluruh narasi dapat dibaca tanpa JavaScript |
| GET | `/gtk2026/worker.js` | Publik, JavaScript worker; tidak tersedia ketika Vite hot server aktif |
| GET | `/gtk2026/media/{evidence}` | Publik hanya untuk ID konfigurasi dengan berkas valid dan `public_approved: true` |
| GET | `/gtk2026/evidence` | Login admin existing |
| GET | `/gtk2026/evidence/{evidence}/media` | Login admin existing, berkas valid sesuai allowlist |

Parameter evidence hanya huruf kecil dan bukan path file. ID tidak dikenal menghasilkan 404. Halaman/media privat memiliki `no-store` dan `noindex,nofollow`.

## 5. Slides

| Slide | Isi | Target |
| --- | --- | --- |
| 1 | Dari Kebutuhan Menjadi Pengabdian; identitas dan opening SASTI | 35 detik |
| 2 | Kebutuhan awal; enam langkah pencatatan manual | 40 detik |
| 3 | Memilih bertindak; pengembangan dan pemeliharaan mandiri | 40 detik |
| 4 | Timeline 2022–2026, termasuk SIAGA Patas dan publikasi praktik baik | 50 detik |
| 5 | Ekosistem, 1.425 siswa aktif, 89 guru/staf per 7 November 2026, modul utama | 45 detik |
| 6 | BK, komunikasi, guru, pembelajaran, PILKETOS 1.338 suara | 60 detik |
| 7 | Keluarga sebagai dukungan positif | 45 detik |
| 8 | Keberlanjutan dan komitmen kebermanfaatan | 45 detik |

Total 360 detik. Tidak ada persentase partisipasi PILKETOS. Publikasi diposisikan sebagai dokumentasi praktik baik, bukan penghargaan.

## 6. Evidence System

Perkembangan November 2026 ditambahkan ke timeline slide 4 dan banner slide 5: SASTI terhubung langsung dengan website resmi SMA Negeri 4 Tasikmalaya. Banner memuat tautan `https://sman4-tsm.sch.id/web`. Keterangan tanggal mengikuti informasi peserta; screenshot chat memperlihatkan tombol **SASTI** dan **Akses SASTI**. Penyebutan ini menjelaskan akses langsung dari website, tanpa menyimpulkan SSO atau sinkronisasi database. Berkas screenshot lokal masih menunggu `website-sekolah.png`; pemeriksaan langsung situs melalui alat penelusuran mengalami timeout.

ID, metadata, dan pemetaan slide bukti dikelola di `config/gtk2026.php`. Kategori `testimonials` menampung video testimoni guru/siswa dan pimpinan.

Video profil SASTI oleh Nizar tampil langsung pada slide 5 melalui pemutar **Profil SASTI** dan kategori **Profil SASTI** (`sastiprofile`) pada panel bukti. Video `hszFbQBkFjM` dimulai pada **00:07**.

Video testimoni tampil langsung sebagai dua pemutar pada slide 6, serta melalui kategori **Suara dari sekolah** pada panel bukti:

| Video | Tautan | Mulai |
| --- | --- | --- |
| Guru dan siswa | `https://www.youtube.com/watch?v=xXtA_eOoEZo&t=16s` | 00:16 |
| Pimpinan | `https://www.youtube.com/watch?v=kdJpUBJoAtg&t=8s` | 00:08 |

Jenis `youtube` memakai `videos` berisi ID video, judul, dan `start_seconds`. URL dibentuk hanya pada host HTTPS YouTube dari ID sebelas karakter yang valid dan waktu mulai integer nonnegatif. Flag `public_approved` berlaku untuk tautan ini; konfigurasi saat ini mengizinkan ketiga tautan yang diberikan peserta tampil pada versi publik dan privat. Gambar bukti privat lainnya tetap memerlukan login.

Video menggunakan iframe `youtube-nocookie.com` dengan `start`, `rel=0`, dan `playsinline=1`, mengikuti [dokumentasi YouTube](https://developers.google.com/youtube/player_parameters). Iframe belum memiliki `src` saat halaman dibuka; klik **Putar di slide** baru memasang sumber dan memulai pemutaran. Referrer policy `strict-origin-when-cross-origin` menjaga referer yang diperlukan pemutar. Tidak ada pemutaran otomatis saat membuka slide. Tautan **Buka di YouTube** tetap tersedia sebagai cadangan dengan `noopener noreferrer`.

Pilihan **Dokumentasi & data** pada slide 5 dan 6 menampilkan kembali seluruh modul, kartu dampak, foto, dan screenshot. Video menjadi tampilan awal saat JavaScript aktif; tanpa JavaScript, dokumentasi tetap terbaca dan tautan YouTube tersedia di panel bukti. Berpindah slide, mengganti pilihan media/detail bukti, menutup panel, mencetak, atau meninggalkan halaman akan melepas sumber iframe agar suara tidak berjalan di latar. Memutar video lain juga menghentikan video sebelumnya.

Pemutaran memerlukan internet; worker tidak mengambil atau menyimpan video YouTube. Saat cetak, iframe dan kontrol disembunyikan, dokumentasi ditampilkan, dan tautan ketiga video dicantumkan pada slide terkait. Tidak dibuat unduhan video, transkrip, atau kutipan testimoni. Label mengikuti keterangan peserta. Pemutaran profil SASTI di dalam slide berhasil diperiksa melalui Chrome pada server lokal; tampilan kedua testimoni dan pergantian ke dokumentasi juga diperiksa. Verifikasi pemutaran kedua video testimoni belum selesai karena pengguna sedang berinteraksi dengan browser.

Enam screenshot awal telah diperlihatkan di chat. Berkas yang kemudian ditempatkan peserta di folder evidence memiliki isi yang berbeda sebagian. Lima screenshot SASTI telah diperiksa dan dihubungkan dengan salinan identik; berkas sumber dengan nama timestamp tetap dipertahankan. Satu screenshot ERP TGM yang tidak terkait GTK tidak dihubungkan ke aplikasi. Semua screenshot aktif tetap privat (`public_approved: false`), terutama yang memuat nama, foto, atau nomor siswa.

Berkas aktif dalam `storage/app/private/gtk2026/evidence/`:

| Screenshot | Nama berkas | Sumber peserta |
| --- | --- | --- |
| Hasil PILKETOS | `pilketos.png` | `Screenshot 2026-11-06 at 22.13.20.png` |
| Dashboard akademik | `akademik.png` | `Screenshot 2026-11-06 at 22.13.41.png` |
| Asmaul Husna / Jurnal PPP | `asmaul-husna.png` | `Screenshot 2026-11-06 at 22.14.03.png` |
| Tadarus / Jurnal PPP | `tadarus.png` | `Screenshot 2026-11-06 at 22.14.11.png` |
| E-Alumni & Tracer Study | `alumni.png` | `Screenshot 2026-11-06 at 22.14.30.png` |
| Sampul publikasi Kemendikdasmen | `publikasi-sampul.png` | `Screenshot 2026-09-29 at 19.07.30.png` |
| Pemaparan layanan PILKETOS | `pilketos-pemaparan.jpeg` | `WhatsApp Image 2026-10-30 at 22.01.29.jpeg` |
| Kegiatan PILKETOS | `pilketos-kegiatan.jpeg` | `WhatsApp Image 2026-10-30 at 22.04.27.jpeg` |
| Penggunaan perangkat di kelas | `penggunaan-perangkat-kelas.jpeg` | `WhatsApp Image 2026-10-30 at 22.08.20.jpeg` |
| Pendampingan di ruang kelas | `pendampingan-kelas.jpeg` | `WhatsApp Image 2026-10-30 at 22.08.20 (1).jpeg` |

Kelima tambahan terakhir disalin identik dengan berkas sumber. Foto kegiatan tetap privat karena memuat peserta didik dan/atau data pada layar. Berkas publikasi hanya menampilkan sampul bertahun 2024; halaman artikel belum tersedia. Tanggal pada nama berkas menunjukkan penamaan sumber dan tidak dipakai sebagai kepastian tanggal kegiatan. Foto di kelas tidak dijadikan bukti hasil kuis atau analisis ketuntasan. Seluruh gambar menggunakan pemuatan lazy dari sistem bukti existing.

Website utama, halaman masuk, modal pengingat, dan dashboard utama masih menunggu berkas lokal. Nama berkas yang disiapkan untuk screenshot awal:

| Screenshot | Nama berkas |
| --- | --- |
| Website sekolah / banner TKA | `website-sekolah.png` |
| Halaman masuk SASTI | `portal-sasti.png` |
| Modal pengingat dan petugas | `pengingat-harian.png` |
| Dashboard utama | `dashboard.png` |
| Dashboard akademik | `akademik.png` |
| Hasil PILKETOS | `pilketos.png` |

Penambahan file dengan nama tersebut otomatis mengaktifkan tampilannya pada halaman bukti privat. Tidak dibuat gambar pengganti atau rekonstruksi screenshot. File yang belum ada tetap tampil sebagai **Belum tersedia**.

Dashboard terbaru yang diberikan melalui chat bertanggal 7 November 2026 menampilkan 1.425 siswa dan 89 guru/staf. Statistik slide utama, tanggal acuan yang terlihat, dan catatan presenter kini mengikuti dokumentasi tersebut; brief awal mencatat 1.424 siswa. Berkas gambar dashboard masih menunggu disimpan sebagai `storage/app/private/gtk2026/evidence/dashboard.png`; pemetaannya sudah siap dan akan tampil otomatis pada slide 5 setelah tersedia. Website sekolah menampilkan 82 guru/tenaga kependidikan sehingga angka website tidak digabungkan dengan dashboard. Total 1.338 suara PILKETOS konsisten dengan brief; persentase partisipasi yang terlihat pada screenshot tidak dipakai sebagai klaim presentasi. Screenshot akademik membuktikan tampilan kelas dan pendamping, bukan hasil ujian/analisis per soal.

Panel mempertahankan posisi slide, menyediakan pencarian langsung, menahan fokus di dialog, dan mengembalikan fokus setelah ditutup. Timeline tersedia sebagai narasi terstruktur. Bukti lain menampilkan **Belum tersedia** sampai ada berkas yang sah. Foto bukti baru dimuat setelah detail dipilih. Video memakai kontrol dan `preload="none"`, tanpa autoplay; pergantian detail/penutupan panel menghentikan video.

Cara menambahkan bukti, misalnya dashboard:

1. Bersihkan data pribadi pada salinan berkas terlebih dahulu.
2. Letakkan `dashboard.webp` dalam `storage/app/private/gtk2026/evidence/` pada disk `local`, bukan di folder `public`.
3. Isi `evidence.dashboard.file` dengan `dashboard.webp` di `config/gtk2026.php`.
4. Biarkan `public_approved` bernilai `false` untuk akses admin saja.
5. Setelah memeriksa keamanan isi berkas, ubah menjadi `true` bila memang boleh diakses publik.
6. Deploy konfigurasi dan berkas secara bersamaan; bangun ulang cache konfigurasi jika deployment memakainya.

Ekstensi yang diterima: WebP, PNG, JPG/JPEG, PDF, MP4. MIME juga diperiksa. HTML/SVG, path traversal, file hilang, dan symlink keluar root ditolak. Bukti source code sebaiknya berupa screenshot atau PDF yang telah diperiksa; jangan mengunggah source mentah berisi credential. Saat ini satu berkas per kategori; PDF dapat berisi beberapa halaman.

Tidak dibuat CMS baru. Konfigurasi mengikuti pola file konten `config/marketing.php` existing; integrasi upload ke admin akan membutuhkan alur review privasi tambahan dan berada di luar scope ini.

## 7. Keyboard Shortcuts

| Tombol | Fungsi |
| --- | --- |
| Arrow Right / Page Down | Slide berikutnya |
| Arrow Left / Page Up | Slide sebelumnya |
| Home / End | Slide pertama / terakhir |
| Ctrl / Cmd + K | Buka pencarian bukti |
| B | Panel bukti |
| T / D / K / W / P | Timeline / Dashboard / BK / WhatsApp / PILKETOS |
| Esc | Tutup dialog; browser menangani keluar fullscreen |
| Tab / Shift + Tab | Navigasi kontrol dan dialog |

Shortcut presentasi tidak mengambil input ketika mengetik di input, textarea, select, atau contenteditable. Dalam pencarian bukti, Arrow Down atau Enter memindahkan fokus ke hasil pertama. Swipe horizontal minimal 65 px dan lebih dominan dari gerakan vertikal mengganti slide; gerakan pada kontrol, link, input, video, dan dialog tidak mengganti slide.

## 8. Presenter Mode

Versi lengkap dengan dokumentasi langsung pada slide tersedia di `/gtk2026/presenter?present=1` (route `gtk2026.presenter`). Gunakan login admin existing. Tautan **Presentasi lengkap** juga tersedia di bagian bawah panel bukti. Jumlah slide tetap delapan dan panduan durasi tetap enam menit.

| Slide | Dokumentasi langsung |
| --- | --- |
| 3 — Langkah awal | SASTI Patas dan SIAGA Patas versi 2024, pendampingan guru, penggunaan perangkat di kelas |
| 4 — Perjalanan | Sampul publikasi, foto presentasi di Kementerian, sertifikat kontributor |
| 5 — Ekosistem | Dashboard terkini (menunggu berkas), aplikasi mobile, akademik, Asmaul Husna, Tadarus, E-Alumni |
| 6 — Dampak | Hasil PILKETOS 2026, arsip hasil 2025, pemaparan layanan, foto kegiatan |

Dokumentasi ditempatkan melalui `slide_evidence` pada konfigurasi. Klik gambar untuk membuka detail ukuran besar pada panel bukti; gambar hilang atau bukti non-gambar tidak membuat slot kosong di slide. Semua foto ditampilkan utuh dengan `object-fit: contain`. Jumlah kolom galeri ekosistem mengikuti jumlah gambar yang tersedia, maksimal enam gambar dalam konfigurasi saat ini.

Tampilan mobile disalin identik dari `WhatsApp Image 2026-11-04 at 09.34.42.jpeg` di Downloads ke `storage/app/private/gtk2026/evidence/sasti-mobile.jpeg`. Screenshot menampilkan akun Nizar, akses peran guru/wali kelas, pencarian siswa, jadwal, mapel, materi, dan kuis. Jenis implementasi native/PWA, ketersediaan APK, dan efektivitas pembelajaran tidak disimpulkan dari gambar tersebut. Akses mengikuti bukti privat existing.

Enam arsip tambahan disalin identik dengan nama yang dapat diterima route media:

| Berkas sumber | Nama arsip |
| --- | --- |
| `Screenshot 2024-10-27 at 18.50.51.png` | `sasti-awal-2024.png` |
| `Screenshot 2024-10-27 at 18.49.53.png` | `siaga-patas-v2-2024.png` |
| `Screenshot 2024-09-18 100805.png` | `siaga-patas-beranda-2024.png` |
| `Screenshot 2024-10-27 at 18.32.01.png` | `siaga-patas-guru-2024.png` |
| `Screenshot 2025-10-10 at 08.23.00.png` | `pilketos-arsip-2025.png` |
| `SELAMAT DAN SUKSES KEPADA - 4.png` | `tata-tertib-siaga-patas.png` |

Dua tampilan utama 2024 masuk langsung pada slide 3. Cuplikan beranda tambahan, daftar staf, dan poster tata tertib tersedia pada panel bukti privat. Nama berkas poster tidak dijadikan keterangan isi; gambar tersebut berisi tata tertib dan seragam. Arsip 2025 menampilkan 1.209 suara, sedangkan hasil 2026 tetap 1.338 suara. Keduanya ditandai terpisah pada slide 6. Tampilan 2024 tidak dijadikan bukti screenshot dari tahun 2022. Seluruh arsip tambahan tetap privat.

Foto Kementerian disalin identik dari `Demo Siaga SMAN 4 Tasikmalaya for MPLS New Students.png` ke `storage/app/private/gtk2026/evidence/presentasi-kementerian.png`. Keterangan lokasi mengikuti informasi peserta; tanggal kegiatan tidak diasumsikan dan tidak dikaitkan dengan tahun sampul publikasi.

Sertifikat disalin identik dari `Nizar Suryadi – Sertifikat Praktik Digitalisasi Pembelajaran dan Layanan Pendidikan jenjang SMA.pdf.png` ke `storage/app/private/gtk2026/evidence/sertifikat-kontributor.png`. Peran yang tercantum adalah **Kontributor** penyusunan naskah, diterbitkan Direktorat SMA di Jakarta pada **19 Desember 2024**, nomor **2670/C5/DM.00.02/2024**. Narasi timeline 2024 dan catatan presenter telah disesuaikan. Sertifikat ini tidak dianggap sebagai halaman artikel atau bukti tanggal foto presentasi.

Versi lengkap menggunakan `private, no-store`, `noindex, nofollow`, tanpa marker shell publik dan tanpa pendaftaran worker. Seluruh gambar tetap melalui route media berautentikasi. Worker existing melewatkan halaman presenter dan media privat. Tidak ada janji reload offline untuk versi ini; gunakan cetak PDF sebagai cadangan setelah seluruh gambar dimuat. Versi publik `/gtk2026` tetap menyaring bukti berdasarkan `public_approved`.

Pemeriksaan penambahan dokumentasi: 25 tes fitur GTK (117 assertions) dan 12 tes JavaScript, formatter, dan build Vite lulus. Penempatan slide dan batas akses privat diuji dengan storage serta database pengujian terisolasi. Slide langkah awal, perjalanan (termasuk foto Kementerian dan sertifikat), ekosistem, serta dampak diperiksa di Chrome melalui render lokal. Pratinjau cetak tetap menampilkan delapan halaman. Tidak dibuat akun atau route debug publik untuk pemeriksaan tersebut; artefak pratinjau sementara telah dibersihkan.

`?present=1` menyembunyikan tautan brand website. Navbar/footer blog tidak pernah dimuat pada layout GTK. `?notes=1` menampilkan catatan dan target durasi pada slide aktif; catatan ini bukan rahasia dan query parameter bukan autentikasi.

Fullscreen menggunakan Fullscreen API; kegagalan/dukungan yang tidak tersedia menghasilkan petunjuk memakai menu browser. Tekan Esc untuk keluar fullscreen sesuai perilaku browser.

Timer dimulai manual dengan **Mulai 6 Menit**. Klik untuk jeda/lanjutkan, klik dua kali untuk reset. Warna berubah saat waktu berjalan mencapai 5:00, 5:30, dan 6:00. Penghitungan menggunakan waktu monotonic, bukan jumlah tick interval. Timer tidak otomatis berjalan ketika membuka halaman. Pada desktop, tinggi konten menyesuaikan ruang antara header dan kontrol agar catatan tetap muat. Skala ini dinonaktifkan saat cetak dan pada mobile.

## 9. Assets

Folder yang disiapkan: `public/assets/gtk2026/` dengan subfolder `profile`, `sasti`, `family`, `evidence`, `publication`, `screenshots`, `video`. Folder publik hanya untuk materi yang telah disetujui untuk publik. **Semua bukti dikelola dari folder privat pada bagian 6**, termasuk bukti yang kelak diizinkan tampil publik, supaya pencabutan flag langsung menutup route media.

Pengaturan foto/logo di `assets` menerima path relatif, contoh `assets/gtk2026/profile/nizar.webp`. Kunci: `profile`, `school_logo`, `school`, `family`, `family_secondary`, `family_extended`, `process`, `logo`. `school_logo` khusus logo sekolah; `logo` tetap untuk logo SASTI. PNG/JPEG/WebP didukung; aset hilang menghasilkan visual tipografi fallback. Foto profil dimuat prioritas tinggi, foto lain lazy.

Aset peserta yang sudah terpasang:

- `public/assets/gtk2026/profile/nizar.webp`: potret pada opening, identitas, dan closing.
- `public/assets/gtk2026/profile/logo-sman4.png`: logo sekolah pada header.
- `public/assets/gtk2026/family/keluarga.webp`: foto utama kolase slide 7.
- `public/assets/gtk2026/family/bersama-istri.webp`: foto kedua kolase.
- `public/assets/gtk2026/family/keluarga-besar.webp`: foto ketiga kolase.

Empat foto dikompresi menjadi WebP (total sekitar 325 KiB), dengan transparansi potret dipertahankan. Logo menggunakan PNG asli. Berkas sumber di Downloads tidak diubah. Foto keluarga memakai `object-fit: contain` agar seluruh anggota keluarga tetap terlihat. Kelima aset otomatis termasuk cache inti offline melalui konfigurasi existing.

Setelah pemasangan foto, 16 tes fitur GTK (57 assertions), build Vite, dan formatter konfigurasi lulus. Opening, closing, logo header, serta kolase keluarga diperiksa di Chrome desktop dan emulasi Pixel 9. Reload setelah server lokal dimatikan tetap menampilkan presentasi dan potret dari cache.

## 10. Privacy

Jangan unggah nomor WhatsApp, NIK/NISN, alamat, credential/token, data konseling, atau identitas/keluarga siswa yang tidak diperlukan. Redaksi harus melekat pada piksel atau dokumen; blur CSS saja tidak melindungi sumber file. Periksa juga metadata dan layer PDF. `public_approved` hanya kontrol akses, bukan pemeriksa otomatis isi gambar.

Berkas disimpan pada disk privat `local`. Pastikan document root produksi menunjuk `public/` dan jangan membuat symlink dari folder bukti privat ke public. Login admin existing memperlakukan akun terautentikasi sebagai admin; tidak dibuat sistem role baru. Jangan menaruh materi rahasia di judul/summary konfigurasi karena metadata kategori tersebut sengaja tampil di indeks publik.

## 11. Offline/Print Support

Worker terpisah didaftarkan pada HTTPS atau localhost, scope `/gtk2026`. Worker tidak diaktifkan ketika Vite hot server digunakan. Untuk uji offline, gunakan hasil `npm run build`, buka halaman online, lalu tunggu **Offline siap** sebelum memutus koneksi.

Cache mencakup HTML publik, dua bundle GTK, dan foto/logo yang dikonfigurasi. Query mode dibaca JavaScript sehingga reload offline tetap mendukung presentasi/catatan. Request halaman memakai network dengan batas tunggu 2,5 detik, lalu fallback ke cache. Bukti, login, admin, dan situs lain tidak disimpan/intersepsi worker GTK. Cache admin existing tidak dihapus. Saat bundle berubah, worker membersihkan hanya cache GTK lama.

Bukti tidak dijanjikan tersedia offline. Browser dapat menghapus cache; kunjungan pertama memerlukan koneksi. Simpan PDF sebelum wawancara sebagai cadangan independen.

Tombol cetak atau menu Print browser menampilkan seluruh delapan slide, satu slide per halaman A4 landscape. Navigasi, timer, dialog bukti, dan catatan presenter disembunyikan. Tanpa JavaScript seluruh slide terbaca vertikal dan tetap dapat dicetak.

## 12. Testing

Perintah yang digunakan:

```sh
php artisan test --compact tests/Feature/Gtk2026Test.php tests/Feature/PublicPagesTest.php tests/Feature/PwaTest.php tests/Feature/LoginPageTest.php
node --test tests/Unit/Gtk2026Client.test.js tests/Unit/Gtk2026Worker.test.js
npm run build
vendor/bin/pint --format agent app/Http/Controllers/Gtk2026Controller.php config/gtk2026.php routes/web.php tests/Feature/Gtk2026Test.php
```

Hasil: 39 feature tests (150 assertions) dan 10 JavaScript tests lulus. Build berhasil; Sass Bootstrap existing mengeluarkan deprecation warnings. Perintah Pint `--dirty` dicoba sesuai instruksi proyek, tetapi checkout tidak memiliki metadata Git; formatter kemudian berhasil dijalankan pada empat file PHP yang dibuat/diubah.

Pemeriksaan Chrome dilakukan pada 1366×768, 1920×1080, responsive portrait 393×851 dan emulasi Pixel 9 portrait. Opening, timeline, statistik/modul, kartu dampak, bagian keluarga, closing, panel bukti, pencarian PILKETOS, navigasi, serta fullscreen diperiksa melalui UI. Pratinjau Print memuat delapan halaman. Console yang diperiksa tidak memperlihatkan error aplikasi; Boost browser-logs belum memiliki file log. Simulasi kualitas screen sharing dilakukan dengan memeriksa keterbacaan ukuran laptop, tanpa sesi Zoom nyata.

Feature tests mencakup render/mode query, placeholder, akses publik berdasarkan approval, autentikasi privat, cache/privacy headers, file hilang, MIME aktif, traversal, symlink, dan escaping. JavaScript tests mencakup boundary keyboard/timer, input, serta pemasangan/fallback/isolasi worker. Pengujian perangkat Android fisik, Safari/Firefox, dan Zoom nyata belum dilakukan.

Untuk suite PHP lengkap, jalankan `php artisan test --compact` sebelum deployment.

## 13. Deployment Notes

1. Deploy source Laravel dan hasil `npm run build`, termasuk manifest dan seluruh bundle. Tidak ada migrasi atau dependency baru.
2. Jalankan cache/optimasi Laravel sesuai prosedur deployment existing. Konfigurasi GTK harus ikut cache terbaru.
3. Pastikan HTTPS, `APP_URL`, document root `public/`, dan penulisan storage/session benar. Untuk offline, bundle/foto inti harus berasal dari origin situs yang sama.
4. Isi foto/bukti hanya setelah review data pribadi. Jangan deploy `.env`, file backup credential, data pengujian, atau artefak debug.
5. Buka `/gtk2026?present=1` online hingga cache siap; uji kembali navigasi, fullscreen, Print, dan akses privat sebagai guest/admin.
6. Simpan PDF cadangan dan latihan durasi enam menit sebelum wawancara.

## 14. Known Limitations

- Foto khusus lingkungan sekolah, logo resmi SASTI saat ini, screenshot dashboard utama/BK/WhatsApp saat ini, halaman artikel publikasi, source history, dan berkas video testimoni belum tersedia secara lokal. Dua tautan YouTube testimoni dan satu video profil SASTI sudah terpasang. Potret, logo sekolah, kolase keluarga, serta sembilan belas berkas bukti privat sudah tersedia; kriteria bukti lainnya masih menunggu dokumen asli.
- Tidak ada klaim angka live, testimoni rekaan, screenshot buatan, atau persentase partisipasi.
- Narasi 2022–2026, angka siswa/staf/suara, dan penyebutan publikasi mengikuti brief peserta.
- Tidak ada admin editor/upload GTK; pengelolaan menggunakan config dan file privat.
- Offline hanya untuk inti presentasi setelah cache berhasil terpasang; bukan PWA baru dan bukan pengganti cadangan PDF.
- Mobile/layar pendek menggunakan scroll dalam slide agar teks tidak terpotong. Desktop target memakai satu viewport per slide.
- Browser Fullscreen API/dialog memerlukan browser modern. Tanpa JavaScript tersedia alur baca vertikal.
- Database remote dan halaman produksi belum diperiksa secara langsung; regresi halaman existing diuji menggunakan database pengujian terisolasi.
- Tidak dilakukan deployment produksi, perubahan DNS, atau refactor halaman existing.
