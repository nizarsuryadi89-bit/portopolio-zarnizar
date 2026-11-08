# Zarnizar.com — Web Platform & Portofolio Digital

Repositori resmi website **[zarnizar.com](https://zarnizar.com)** yang dibangun menggunakan **Laravel**, PHP 8.4, dan Vite. Platform ini mencakup portofolio profesional, layanan digital UMKM, showcase inovasi pendidikan (GTK 2026), serta microsite edukasi interaktif **Zarnizar Learning**.

---

## 🌟 Modul & Fitur Utama

### 1. Website Utama & Layanan Digital (`/`)
* **Profil & Portofolio:** Showcase karya pengembangan aplikasi web & mobile oleh Nizar Suryadi.
* **Layanan Solusi Digital (`/jasa-aplikasi`):** Informasi konsultasi pembuatan website, sistem kasir, dan aplikasi kustom.
* **KulaPOS (`/kulapos`):** Halaman produk aplikasi kasir offline berbasis Android untuk UMKM.
* **Blog & Artikel Teknis (`/blog/{slug}`):** Tulisan seputar rekayasa perangkat lunak, teknologi, dan pendidikan.

### 2. Showcase Apresiasi GTK 2026 (`/gtk2026`)
* Presentasi interaktif 8 slide dedikasi digital pendidikan (*"Dari Kebutuhan Menjadi Pengabdian"*).
* Ekosistem SASTI (Sistem Administrasi Terpadu Informatika) SMA Negeri 4 Tasikmalaya.
* Modal arsip bukti pendukung tanpa login (`/gtk2026/evidence`).

### 3. Zarnizar Learning — Pembelajaran Informatika XII (`/pembelajaran`)
Microsite pembelajaran mandiri dan terpandu dengan fokus **Code Reading & Logic Tracing** kode program Python (4 Pertemuan / 8 JP):
* **Pertemuan 1 (`/pembelajaran/pertemuan/1`):** Mengenal dan Membaca Kode Dasar Python (Variabel, tipe data, `print()`, alur sekuensial).
* **Pertemuan 2 (`/pembelajaran/pertemuan/2`):** Memahami Operator dan Percabangan (`if`, `elif`, `else`, indentasi).
* **Pertemuan 3 (`/pembelajaran/pertemuan/3`):** Membaca List dan Perulangan (Struktur list, perulangan `for`, akumulator nilai).
* **Pertemuan 4 (`/pembelajaran/pertemuan/4`):** Python Code Reading Challenge (Tantangan logika komposit).
* **Interactive Code Tracer:** Penelusuran eksekusi program baris demi baris lengkap dengan tabel *Memory Watch* dan simulasi layar terminal.
* **In-Browser Python Playground:** Editor kode interaktif berbasis Skulpt yang aman di sisi klien (Client-Side) tanpa beban server.
* **Asesmen Akhir (`/pembelajaran/asesmen`):** 10 soal membaca kode berbobot 100 poin dengan standar ketuntasan (KKTP) 75.
* **Laporan Hasil Belajar (`/pembelajaran/hasil`):** Kartu capaian nilai, analisis benar/salah, pembahasan tiap nomor, dan fitur cetak/simpan PDF (`@media print`).

### 4. Admin Dashboard (`/admin/dashboard`)
* Pengelolaan portofolio, artikel, kegiatan, skills, profil, sliders, dan pengaturan situs.
* Kartu pintasan akses langsung ke microsite pembelajaran.

---

## 🛠️ Tech Stack & Ekosistem

* **Framework Backend:** Laravel 11.x / 13.x (PHP 8.4 via Laravel Herd)
* **Frontend Pipeline:** Vite 8, Sass, Tailwind CSS, Bootstrap 5
* **In-Browser Python Engine:** Skulpt (Python 3 client-side execution)
* **Database:** MySQL / SQLite
* **Testing:** Pest PHP (Feature & Unit Tests)
* **Code Styler:** Laravel Pint

---

## 💻 Panduan Menjalankan di Lokal

### 1. Kloning Repositori & Instalasi Dependensi
```bash
git clone https://github.com/nizarsuryadi89-bit/portopolio-zarnizar.git
cd portopolio-zarnizar

# Install dependensi PHP
composer install

# Install dependensi Node.js
npm install
```

### 2. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi database di file `.env`.

### 3. Migrasi Database & Seed
```bash
php artisan migrate --seed
```

### 4. Kompilasi Asset & Menjalankan Server
```bash
# Terminal 1: Kompilasi asset Vite
npm run dev
# Atau untuk build production:
npm run build

# Terminal 2: Jalankan web server Laravel (jika tidak memakai Laravel Herd)
php artisan serve
```

---

## 🧪 Menjalankan Pengujian (Tests)

Proyek ini dilengkapi suite pengujian otomatis menggunakan **Pest PHP**:

```bash
# Menjalankan seluruh pengujian modul Pembelajaran Python
vendor/bin/pest --filter=pembelajaran

# Menjalankan seluruh test suite proyek
vendor/bin/pest
```

---

## 📝 Lisensi

Hak cipta dilindungi undang-undang &copy; 2026 [Zarnizar.com](https://zarnizar.com) — Nizar Suryadi, S.T.
