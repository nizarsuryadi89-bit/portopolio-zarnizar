@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-4 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold text-gray-900 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Selamat datang kembali, {{ auth()->user()->name }}!</p>
    </div>
</div>

<style>
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 1rem;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
    }
</style>

<div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: #ffffff; border-radius: 1rem;">
    <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 bg-white text-primary p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                <i class="bi bi-mortarboard-fill fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1 text-white">Zarnizar Learning — Belajar Membaca Kode Python</h5>
                <p class="text-white-50 mb-0 small">Modul Informatika Kelas XII SMAN 4 Tasikmalaya (4 Pertemuan • 8 JP • In-Browser Playground)</p>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('pembelajaran.index') }}" target="_blank" class="btn btn-warning text-dark fw-bold">
                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Microsite
            </a>
            <a href="{{ route('pembelajaran.asesmen') }}" target="_blank" class="btn btn-outline-light">
                <i class="bi bi-clipboard-check me-1"></i> Asesmen Akhir
            </a>
        </div>
    </div>
</div>

<div class="dashboard-stat-grid row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-5">
    <div class="col">
        <a href="{{ route('admin.portfolios.index') }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="icon-box" style="background-color: #eef2ff; color: #4f46e5;">
                            <i class="bi bi-briefcase fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-semibold mb-1">Total Portfolio</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $portfolioCount }}</h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.posts.index') }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="icon-box" style="background-color: #f0fdf4; color: #16a34a;">
                            <i class="bi bi-file-text fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-semibold mb-1">Total Artikel</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $postCount }}</h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.activities.index') }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="icon-box" style="background-color: #fffbeb; color: #d97706;">
                            <i class="bi bi-calendar-event fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-semibold mb-1">Total Kegiatan</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $activityCount }}</h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.skills.index') }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="icon-box" style="background-color: #fef2f2; color: #dc2626;">
                            <i class="bi bi-star fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-semibold mb-1">Total Skills</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $skillCount }}</h2>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold">Artikel Terbaru</h5>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($latestPosts->isEmpty())
                    <p class="text-muted text-center my-5">Belum ada artikel.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($latestPosts as $post)
                            <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 text-dark fw-semibold">{{ $post->title }}</h6>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $post->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="badge {{ $post->is_published ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                    {{ $post->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold">Portfolio Terbaru</h5>
                <a href="{{ route('admin.portfolios.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($latestPortfolios->isEmpty())
                    <p class="text-muted text-center my-5">Belum ada portfolio.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($latestPortfolios as $project)
                            <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if($project->image)
                                        <img src="{{ Storage::url($project->image) }}" alt="img" class="rounded me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <div class="rounded me-3 d-flex align-items-center justify-content-center bg-light text-muted" style="width: 48px; height: 48px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1 text-dark fw-semibold">{{ $project->title }}</h6>
                                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $project->date ? \Carbon\Carbon::parse($project->date)->format('M Y') : '-' }}</small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
