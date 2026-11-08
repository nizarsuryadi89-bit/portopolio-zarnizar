@extends('layouts.admin')

@section('title', 'Konten — Portfolio CMS')

@section('content')
<div class="admin-page-header pt-3 pb-2 mb-4">
    <h1 class="h2">Konten Anda</h1>
    <p class="text-muted mt-2 mb-0">Pilih konten yang ingin Anda kelola.</p>
</div>
<div class="admin-content-grid">
    @foreach([
        ['route' => 'admin.portfolios.index', 'icon' => 'bi-briefcase', 'title' => 'Portfolio', 'description' => 'Tampilkan proyek dan karya terbaik.'],
        ['route' => 'admin.posts.index', 'icon' => 'bi-file-text', 'title' => 'Artikel', 'description' => 'Tulis dan publikasikan cerita Anda.'],
        ['route' => 'admin.activities.index', 'icon' => 'bi-calendar-event', 'title' => 'Kegiatan', 'description' => 'Catat kegiatan dan pelatihan terbaru.'],
        ['route' => 'admin.sliders.index', 'icon' => 'bi-images', 'title' => 'Hero Slider', 'description' => 'Atur gambar pada halaman utama.'],
        ['route' => 'admin.categories.index', 'icon' => 'bi-tags', 'title' => 'Kategori', 'description' => 'Kelompokkan artikel berdasarkan topik.'],
        ['route' => 'admin.tags.index', 'icon' => 'bi-hash', 'title' => 'Tag', 'description' => 'Tambahkan penanda untuk artikel.'],
    ] as $module)
        <a href="{{ route($module['route']) }}" class="admin-content-tile">
            <span class="admin-content-icon" aria-hidden="true"><i class="bi {{ $module['icon'] }}"></i></span>
            <span><strong>{{ $module['title'] }}</strong><small>{{ $module['description'] }}</small></span>
            <i class="bi bi-chevron-right" aria-hidden="true"></i>
        </a>
    @endforeach
</div>
@endsection
