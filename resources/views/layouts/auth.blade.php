<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-pwa-head />
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Login') — {{ $settings['site_title'] ?? ($profile->name ?? 'Portfolio') }}</title>

    @if(!empty($settings['favicon']))
        <link rel="icon" href="{{ Storage::url($settings['favicon']) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #f4f4f0;
            background-image: radial-gradient(#000 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .brutal-border { border: 3px solid #000; }
        .brutal-shadow { box-shadow: 6px 6px 0 #000; }
        .brutal-shadow-hover {
            box-shadow: 6px 6px 0 #000;
            transition: box-shadow 0.2s ease-in-out, transform 0.2s ease-in-out;
        }
        .brutal-shadow-hover:hover {
            box-shadow: 2px 2px 0 #000;
            transform: translate(4px, 4px);
        }

        .bg-navy-blue { background-color: #1e3a8a; }
        .text-navy-blue { color: #1e3a8a; }

        @media (prefers-reduced-motion: reduce) {
            .brutal-shadow-hover { transition: none; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-gray-900 antialiased selection:bg-blue-900 selection:text-white">
    <nav aria-label="Navigasi utama" class="bg-white brutal-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center gap-4 min-h-16 py-3">
            <a href="{{ url('/') }}" class="min-w-0 flex items-center gap-2 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue-900">
                @if(!empty($settings['favicon']))
                    <x-storage-image :path="$settings['favicon']" alt="" width="32" height="32" class="w-8 h-8 shrink-0 rounded-full brutal-border object-cover bg-white" />
                @else
                    <span class="w-8 h-8 shrink-0 bg-navy-blue brutal-border rounded-full flex items-center justify-center text-white" aria-hidden="true">
                        <i class="bi bi-robot text-sm"></i>
                    </span>
                @endif
                <span class="truncate font-bold text-lg sm:text-xl uppercase tracking-tighter">{{ $profile->name ?? 'Portfolio' }}</span>
            </a>

            <a href="{{ url('/') }}" class="shrink-0 inline-flex items-center gap-2 bg-white text-black brutal-border brutal-shadow-hover px-3 py-2 sm:px-4 font-bold text-xs uppercase tracking-wider focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue-900">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                <span class="sm:hidden">Kembali</span>
                <span class="hidden sm:inline">Kembali ke portfolio</span>
            </a>
        </div>
    </nav>

    <main class="flex-1 flex items-center justify-center px-4 py-10 sm:px-6 sm:py-16">
        <div class="w-full max-w-4xl">
            <x-pwa-controls />
            @yield('content')
        </div>
    </main>

    <footer class="bg-black text-white border-t-[3px] border-blue-900 px-4 py-5 text-center font-bold text-xs uppercase tracking-widest">
        &copy; {{ date('Y') }} {{ $profile->name ?? 'Portfolio' }}
    </footer>
</body>
</html>
