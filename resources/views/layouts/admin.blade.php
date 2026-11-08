<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-pwa-head />

    <title>@yield('title', 'Portfolio CMS')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/css/admin-mobile.css', 'resources/js/app.js'])

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --neon-green: #ccff00;
            --navy-blue: #1e3a8a;
            --border-width: 3px;
            --header-height: 64px;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #f4f4f0;
            background-image: radial-gradient(#000 1px, transparent 1px);
            background-size: 20px 20px;
            color: #000;
        }

        .navbar {
            background-color: #ffffff !important;
            border-bottom: var(--border-width) solid #000;
            height: var(--header-height);
            padding: 0.75rem 1rem;
            flex-wrap: nowrap;
            gap: 1rem;
            z-index: 1040;
        }

        .navbar-brand {
            color: #000 !important;
            font-weight: 700;
            font-size: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .navbar-toggler {
            flex-shrink: 0;
            min-height: 44px;
            border: 2px solid #000;
            border-radius: 0;
        }

        .sidebar {
            position: fixed;
            top: var(--header-height);
            bottom: 0;
            left: 0;
            z-index: 1030;
            padding: 0;
            background-color: #ffffff !important;
            width: var(--sidebar-width);
            border-right: var(--border-width) solid #000;
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: 100%;
            max-height: calc(100vh - var(--header-height));
            max-height: calc(100dvh - var(--header-height));
            padding-top: 1rem;
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
        }
        
        .sidebar .nav-link {
            font-weight: 600;
            text-transform: uppercase;
            color: #000;
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: var(--navy-blue);
            border: 2px solid #000;
            box-shadow: 2px 2px 0px 0px #000;
            transform: translate(-2px, -2px);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: var(--navy-blue);
            border: 2px solid #000;
            box-shadow: 2px 2px 0px 0px #000;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            display: inline-block;
        }

        .admin-main {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-width: 0;
            padding: 2rem;
            padding-top: calc(var(--header-height) + 1.5rem);
        }

        .admin-main > .container-fluid {
            padding: 0;
        }

        .admin-page-header {
            gap: 1rem;
        }

        .admin-page-header h1 {
            margin-bottom: 0;
            overflow-wrap: anywhere;
        }

        .admin-main .row > *, .admin-main .card, .admin-main .card-body,
        .admin-main .list-group-item > div, .admin-main .btn-toolbar {
            min-width: 0;
        }

        .admin-main .card-header.d-flex, .admin-main .list-group-item.d-flex {
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .admin-main .list-group-item, .admin-main .card-title {
            overflow-wrap: anywhere;
        }

        .admin-main img {
            max-width: 100%;
            object-fit: cover;
        }

        .admin-main .list-group-item img, .admin-main .icon-box {
            flex-shrink: 0;
        }

        .admin-main .form-control, .admin-main .form-select,
        .admin-main .ck-editor, .admin-main .ck-editor__main,
        .admin-main .ck-editor__editable, .admin-main .ck-toolbar__items {
            min-width: 0;
            max-width: 100%;
        }

        .admin-main .ck-editor__editable {
            overflow-wrap: anywhere;
        }

        .table-responsive {
            width: 100%;
            min-width: 0;
        }

        .admin-table {
            min-width: 640px;
        }

        .admin-table td {
            overflow-wrap: anywhere;
        }

        .admin-table td[data-label="Aksi"], .admin-table td[data-label="Action"] {
            white-space: nowrap;
        }

        .card {
            border: var(--border-width) solid #000;
            border-radius: 0;
            box-shadow: 6px 6px 0px 0px #000;
            background-color: #ffffff;
            margin-bottom: 2rem;
        }

        .card-header {
            background-color: var(--navy-blue);
            color: #fff;
            border-bottom: var(--border-width) solid #000;
            padding: 1.25rem 1.5rem;
            border-radius: 0;
            font-weight: 700;
            text-transform: uppercase;
        }

        .table {
            color: #000;
            margin-bottom: 0;
        }

        .table th {
            font-weight: 700;
            color: #000;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            border-bottom: var(--border-width) solid #000;
            padding: 1rem;
            background-color: #f4f4f0;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #000;
            font-weight: 500;
        }

        .btn {
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 0;
            padding: 0.5rem 1rem;
            border: 2px solid #000;
            box-shadow: 3px 3px 0px 0px #000;
            transition: all 0.1s;
        }

        .btn:hover {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px 0px #000;
        }

        .table td .btn {
            margin-bottom: 0.5rem;
            margin-right: 0.25rem;
        }

        .btn-primary {
            background-color: var(--navy-blue);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: var(--navy-blue);
            color: #fff;
        }
        
        .btn-success {
            background-color: var(--navy-blue);
            color: #fff;
        }
        
        .btn-success:hover {
            background-color: var(--navy-blue);
            color: #fff;
        }

        .btn-danger {
            background-color: #ff0055;
            color: #fff;
        }
        
        .btn-danger:hover {
            background-color: #ff0055;
            color: #fff;
        }
        
        .btn-warning {
            background-color: #ffaa00;
            color: #000;
        }

        .form-control, .form-select {
            border-radius: 0;
            border: 2px solid #000;
            padding: 0.75rem 1rem;
            font-weight: 500;
            box-shadow: inset 2px 2px 0px 0px rgba(0,0,0,0.05);
        }

        .form-control:focus, .form-select:focus {
            border-color: #000;
            box-shadow: inset 2px 2px 0px 0px rgba(0,0,0,0.05), 0 0 0 0.25rem var(--navy-blue);
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: #000;
            text-transform: uppercase;
            letter-spacing: -0.025em;
        }

        .border-bottom {
            border-bottom: var(--border-width) solid #000 !important;
        }

        .bg-light {
            background-color: #ffffff !important;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                top: var(--header-height);
                bottom: auto;
                width: 100%;
                position: fixed;
                padding: 0;
                border-right: none;
                border-bottom: var(--border-width) solid #000;
            }
            .navbar-brand {
                font-size: 1rem;
                letter-spacing: 0.06em;
            }
            .sidebar-sticky {
                height: auto;
                padding-bottom: 1rem;
            }
            .admin-main {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
                padding-top: calc(var(--header-height) + 1rem);
            }
        }

        @media (max-width: 767.98px) {
            .admin-page-header {
                align-items: stretch !important;
                flex-direction: column;
            }
            .admin-page-header > .btn, .admin-page-header .btn-toolbar,
            .admin-page-header .btn-toolbar > .btn {
                width: 100%;
            }
            .admin-page-header h1 {
                font-size: 1.5rem;
            }
            .card-header {
                padding: 1rem;
            }
            .form-control, .form-select {
                font-size: 1rem;
            }
            .btn {
                min-height: 44px;
                min-width: 44px;
                white-space: normal;
            }
            .admin-table, .admin-table tbody {
                display: block;
                width: 100%;
                min-width: 0;
            }
            .admin-table thead {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
            }
            .admin-table tbody tr {
                display: block;
                margin: 0 0 1rem;
                padding: 0.25rem 1rem;
                background-color: #fff;
                border: var(--border-width) solid #000;
            }
            .admin-table tbody td {
                display: block;
                width: 100%;
                padding: 0.75rem 0;
                border-bottom: 1px dashed #bbb;
                background-color: #fff;
                box-shadow: none;
                white-space: normal;
            }
            .admin-table tbody td:last-child {
                border-bottom: 0;
            }
            .admin-table td[data-label]::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.75rem;
                font-weight: 700;
                color: #555;
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }
            .admin-table .text-truncate {
                max-width: 100% !important;
            }
            .admin-table .btn-group {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            .admin-table td .btn {
                margin: 0.25rem 0.5rem 0.25rem 0;
            }
        }
    </style>
</head>
<body class="admin-app">
    <header class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand me-0" href="{{ route('admin.dashboard') }}">Portfolio CMS</a>
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Buka menu admin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav d-none d-lg-flex ms-auto">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </header>

    <nav id="sidebarMenu" class="offcanvas-lg offcanvas-bottom bg-light sidebar" tabindex="-1" aria-labelledby="admin-menu-title">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title h5" id="admin-menu-title">Menu admin</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Tutup menu admin"></button>
        </div>
        <div class="sidebar-sticky offcanvas-body pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/content') ? 'active' : '' }}" href="{{ route('admin.content') }}">
                        <i class="bi bi-grid me-2"></i> Konten
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/sliders*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">
                        <i class="bi bi-images me-2"></i> Hero Slider
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/profile*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">
                        <i class="bi bi-person me-2"></i> Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/portfolios*') ? 'active' : '' }}" href="{{ route('admin.portfolios.index') }}">
                        <i class="bi bi-briefcase me-2"></i> Portfolio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/activities*') ? 'active' : '' }}" href="{{ route('admin.activities.index') }}">
                        <i class="bi bi-calendar-event me-2"></i> Kegiatan & Pelatihan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">
                        <i class="bi bi-file-text me-2"></i> Artikel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags me-2"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/tags*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
                        <i class="bi bi-hash me-2"></i> Tag
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/skills*') ? 'active' : '' }}" href="{{ route('admin.skills.index') }}">
                        <i class="bi bi-star me-2"></i> Skills
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/experiences*') ? 'active' : '' }}" href="{{ route('admin.experiences.index') }}">
                        <i class="bi bi-building me-2"></i> Pengalaman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/educations*') ? 'active' : '' }}" href="{{ route('admin.educations.index') }}">
                        <i class="bi bi-book me-2"></i> Pendidikan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/social-links*') ? 'active' : '' }}" href="{{ route('admin.social-links.index') }}">
                        <i class="bi bi-share me-2"></i> Social Media
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/marketing*') ? 'active' : '' }}" href="{{ route('admin.marketing.index') }}">
                        <i class="bi bi-megaphone me-2"></i> Pemasaran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <i class="bi bi-gear me-2"></i> Pengaturan
                    </a>
                </li>
                <li class="nav-item d-lg-none mt-4 border-top border-dark pt-2">
                    <a class="nav-link text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <main class="admin-main">
        <div class="container-fluid">
            <x-pwa-controls />
            @yield('content')
        </div>
    </main>
    <x-admin-mobile-navigation />
    
    @yield('scripts')
</body>
</html>
