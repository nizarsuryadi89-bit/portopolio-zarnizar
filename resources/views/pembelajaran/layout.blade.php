<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Zarnizar Learning — Belajar Membaca Kode Python')</title>
    <meta name="description" content="@yield('meta_description', 'Microsite pembelajaran interaktif Informatika Kelas XII SMA Negeri 4 Tasikmalaya: Membaca dan Memahami Kode Python (Code Reading & Logic Tracing).')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts: Space Grotesk & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap 5 CSS Grid / Utilities (CDN for isolation safety) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Skulpt In-Browser Python 3 Interpreter & Standard Library -->
    <script src="https://cdn.jsdelivr.net/npm/skulpt@1.2.0/dist/skulpt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/skulpt@1.2.0/dist/skulpt-stdlib.js"></script>

    <!-- Custom Scoped Styles for Zarnizar Learning -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/pembelajaran.css', 'resources/js/pembelajaran.js'])
    @else
        <link rel="stylesheet" href="{{ asset('build/assets/pembelajaran.css') }}">
    @endif

    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
        code, pre, .font-mono {
            font-family: 'JetBrains Mono', monospace !important;
        }
    </style>
    @stack('styles')
</head>
<body class="zl-app">

    <!-- Top LocalStorage Notification & Reset Banner -->
    <div class="bg-dark text-white py-2 px-3 zl-no-print border-bottom border-black">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2 small">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-device-ssd text-warning"></i>
                <span><strong>Pemberitahuan:</strong> Progres & nilai kuis Anda tersimpan otomatis di browser perangkat ini.</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 zl-trigger-reset" style="font-size: 0.75rem;">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Progres Belajar
                </button>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="zl-navbar zl-no-print">
        <div class="container py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <!-- Brand -->
                <a href="{{ route('pembelajaran.index') }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                    <div class="bg-primary text-white p-2 rounded-3 border border-2 border-dark shadow-sm">
                        <i class="bi bi-terminal-fill fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5 lh-1 text-dark">Zarnizar Learning</div>
                        <div class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">INFORMATIKA KELAS XII • SMAN 4 TASIKMALAYA</div>
                    </div>
                </a>

                <!-- Nav Links -->
                <nav class="d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('pembelajaran.index') }}" class="btn btn-sm {{ request()->routeIs('pembelajaran.index') ? 'btn-primary text-white border-2 border-dark shadow-sm fw-bold' : 'btn-outline-dark border-2' }}">
                        <i class="bi bi-house-door"></i> Beranda
                    </a>

                    <!-- Pertemuan Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark border-2 dropdown-toggle {{ request()->routeIs('pembelajaran.pertemuan') ? 'active fw-bold' : '' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-journal-code"></i> Pertemuan (1-4)
                        </button>
                        <ul class="dropdown-menu border-2 border-dark shadow">
                            <li><h6 class="dropdown-header text-uppercase fw-bold" style="font-size: 0.7rem;">Materi 8 JP</h6></li>
                            <li><a class="dropdown-item py-2" href="{{ route('pembelajaran.pertemuan', 1) }}"><strong>01.</strong> Dasar & Variabel Python</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('pembelajaran.pertemuan', 2) }}"><strong>02.</strong> Operator & Percabangan</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('pembelajaran.pertemuan', 3) }}"><strong>03.</strong> List & Perulangan (Trace)</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('pembelajaran.pertemuan', 4) }}"><strong>04.</strong> Code Reading Challenge</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('pembelajaran.asesmen') }}" class="btn btn-sm {{ request()->routeIs('pembelajaran.asesmen') ? 'btn-warning text-dark border-2 border-dark shadow-sm fw-bold' : 'btn-outline-dark border-2' }}">
                        <i class="bi bi-clipboard-check"></i> Asesmen Akhir
                    </a>

                    <a href="{{ route('pembelajaran.hasil') }}" class="btn btn-sm {{ request()->routeIs('pembelajaran.hasil') ? 'btn-success text-white border-2 border-dark shadow-sm fw-bold' : 'btn-outline-dark border-2' }}">
                        <i class="bi bi-trophy"></i> Hasil
                    </a>

                    <div class="vr mx-1 d-none d-md-block"></div>

                    <a href="{{ url('/') }}" class="btn btn-sm btn-light border border-2 border-dark text-secondary fw-semibold">
                        <i class="bi bi-arrow-left"></i> zarnizar.com
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Page Content Slot -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top border-2 border-dark py-4 mt-5 zl-no-print">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
                        <span class="fw-bold fs-5">Zarnizar Learning</span>
                    </div>
                    <p class="text-muted small mb-1">
                        Modul Pembelajaran Informatika Kelas XII — <strong>Membaca dan Memahami Kode Python</strong>.
                    </p>
                    <p class="text-muted small mb-0">
                        SMA Negeri 4 Tasikmalaya • Guru Pengampu: <strong>{{ config('pembelajaran.meta.pengajar.nama', 'Nizar Suryadi, S.T.') }}</strong>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2 mb-2">
                        <a href="{{ route('pembelajaran.index') }}" class="badge text-bg-light border text-decoration-none">Beranda</a>
                        <a href="{{ route('pembelajaran.asesmen') }}" class="badge text-bg-light border text-decoration-none">Asesmen Akhir</a>
                        <a href="{{ route('pembelajaran.hasil') }}" class="badge text-bg-light border text-decoration-none">Hasil Belajar</a>
                        <a href="{{ url('/') }}" class="badge text-bg-primary text-decoration-none">Website Utama</a>
                    </div>
                    <div class="small text-secondary">
                        &copy; {{ date('Y') }} zarnizar.com. Dirancang untuk literasi komputasional siswa SMA.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle (for dropdown & modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>

