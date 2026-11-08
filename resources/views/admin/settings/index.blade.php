@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengaturan Website</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="sales_home_title" class="form-label">Judul Website</label>
                <input type="text" class="form-control" id="sales_home_title" name="sales_home_title" value="{{ old('sales_home_title', $settings['sales_home_title'] ?? 'Zarnizar — Developer Aplikasi & Solusi Digital Tasikmalaya') }}">
            </div>

            <div class="mb-3">
                <label for="sales_home_description" class="form-label">Deskripsi Website (SEO)</label>
                <textarea class="form-control" id="sales_home_description" name="sales_home_description" rows="2">{{ old('sales_home_description', $settings['sales_home_description'] ?? 'Zarnizar membantu UMKM dan bisnis dengan aplikasi kasir offline, website usaha, dan sistem informasi sesuai kebutuhan. Berbasis di Tasikmalaya, melayani konsultasi online.') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="sales_home_headline" class="form-label">Headline Penjualan (Halaman Utama)</label>
                <input type="text" class="form-control" id="sales_home_headline" name="sales_home_headline" value="{{ old('sales_home_headline', $settings['sales_home_headline'] ?? 'Aplikasi dan Website yang Membantu Bisnis Bekerja Lebih Rapi') }}">
            </div>

            <div class="mb-3">
                <label for="sales_home_intro" class="form-label">Pengantar Penjualan</label>
                <input type="text" class="form-control" id="sales_home_intro" name="sales_home_intro" value="{{ old('sales_home_intro', $settings['sales_home_intro'] ?? 'Zarnizar membantu UMKM dan bisnis membangun sistem kasir, pengelolaan stok, laporan, serta aplikasi web sesuai kebutuhan. Berbasis di Tasikmalaya, melayani konsultasi online.') }}">
            </div>

            <div class="mb-3">
                <label for="favicon" class="form-label">Favicon (Logo Tab Browser)</label>
                <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                @if(isset($settings['favicon']))
                    <img src="{{ Storage::url($settings['favicon']) }}" alt="Favicon" class="mt-2" style="max-height: 50px;">
                @endif
            </div>

            <hr class="my-4">
            <h2 class="h4">Halaman Penjualan</h2>
            <p>CTA bisnis menggunakan 08999770477. <a href="{{ route('admin.marketing.index') }}">Lihat panduan pemasaran dan draft artikel.</a></p>
            @foreach(['kulapos_screenshot' => 'Screenshot utama KulaPOS', 'kulapos_screenshot_secondary' => 'Screenshot tambahan KulaPOS'] as $field => $label)
                <div class="mb-3">
                    <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                    <input type="file" class="form-control" id="{{ $field }}" name="{{ $field }}" accept="image/jpeg,image/png,image/webp">
                    <p class="form-text">Maksimal 4 MB. Gunakan screenshot asli yang diizinkan, dengan data contoh atau data yang sudah disamarkan.</p>
                    @if(!empty($settings[$field]))
                        <x-storage-image :path="$settings[$field]" :alt="$label" class="img-fluid border" style="max-height: 200px" />
                    @endif
                </div>
            @endforeach
            <div class="mb-3">
                <label for="ga4_measurement_id" class="form-label">ID Pengukuran GA4 (opsional)</label>
                <input type="text" id="ga4_measurement_id" name="ga4_measurement_id" class="form-control" value="{{ old('ga4_measurement_id', $settings['ga4_measurement_id'] ?? '') }}" placeholder="G-…">
                <p class="form-text">Isi hanya dengan ID milik Anda dan pastikan tidak ada tag analytics lain. Kosongkan untuk menonaktifkan tag ini.</p>
            </div>
            <div class="mb-4">
                <label for="google_site_verification" class="form-label">Token Verifikasi Search Console (opsional)</label>
                <input type="text" id="google_site_verification" name="google_site_verification" class="form-control" value="{{ old('google_site_verification', $settings['google_site_verification'] ?? '') }}">
                <p class="form-text">Untuk metode HTML tag pada properti URL-prefix. Masukkan hanya nilai content dari Google, bukan tag HTML. Properti Domain memakai verifikasi DNS.</p>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </form>
    </div>
</div>
@endsection
