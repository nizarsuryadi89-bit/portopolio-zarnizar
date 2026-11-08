<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-marketing-head :title="($settings['sales_home_title'] ?? null) ?: 'Zarnizar — Media Pembelajaran Informatika & Inovasi Teknologi Pendidikan'" :description="($settings['sales_home_description'] ?? null) ?: 'Platform edukasi Informatika, koding Python interaktif dengan visual tracing, asesmen mandiri, dan karya inovasi pendidikan oleh Nizar Suryadi, S.T. (Guru SMAN 4 Tasikmalaya).'" :canonical="route('site.home', request()->integer('blog_page') > 1 ? ['blog_page' => request()->integer('blog_page')] : [])" :settings="$settings" :robots="$search !== null && $search !== '' ? 'noindex, follow' : 'index, follow'" />
    @if(!empty($settings['favicon']))
        <link rel="icon" href="{{ Storage::url($settings['favicon']) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #f4f4f0;
            background-image: radial-gradient(#000 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .brutal-border {
            border: 3px solid #000;
        }

        .brutal-shadow {
            box-shadow: 6px 6px 0px 0px rgba(0, 0, 0, 1);
        }

        .brutal-shadow-hover {
            box-shadow: 6px 6px 0px 0px rgba(0, 0, 0, 1);
            transition: all 0.2s ease-in-out;
        }

        .brutal-shadow-hover:hover {
            box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 1);
            transform: translate(4px, 4px);
        }

        .text-stroke {
            -webkit-text-stroke: 1.5px #000;
            color: transparent;
        }

        @media (min-width: 768px) {
            .text-stroke {
                -webkit-text-stroke: 2px #000;
            }
        }

        .text-stroke-white {
            -webkit-text-stroke: 1px #000;
            color: #fff;
        }

        /* Marquee Animation */
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            background: #1e3a8a;
            color: #fff;
            /* Navy Blue */
            border-top: 3px solid #000;
            border-bottom: 3px solid #000;
            padding: 10px 0;
            display: flex;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 20s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .bg-navy-blue {
            background-color: #1e3a8a;
        }

        .text-navy-blue {
            color: #1e3a8a;
        }

        .border-navy-blue {
            border-color: #1e3a8a;
        }

        .bg-neon-pink {
            background-color: #ff00ff;
        }
    </style>
</head>

<body class="text-gray-900 antialiased selection:bg-navy-blue selection:text-white">

    <a href="#main-content" class="sales-skip-link">Langsung ke isi</a>
    <x-sales-navigation />
    <main id="main-content">
        @if($search === null || $search === '')
            <!-- Hero Section -->
            <header class="sales-home-hero py-12 lg:py-20 overflow-hidden border-b-[3px] border-black bg-[#f4f4f0]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                    <div class="max-w-4xl relative z-10">
                        <span class="sales-kicker">Ruang Belajar, Teknologi &amp; Inovasi Digital</span>
                        <h1 class="sales-title">
                            @if(!empty($settings['sales_home_headline'])){{ $settings['sales_home_headline'] }}@else Ruang
                            Belajar Koding, Logika &amp; <em>Inovasi Teknologi</em>@endif
                        </h1>
                        <p class="sales-intro">
                            {{ ($settings['sales_home_intro'] ?? null) ?: 'Website personal dan ruang belajar interaktif: eksplorasi pemrograman, catatan teknologi pendidikan, serta dokumentasi karya dan inovasi digital oleh Nizar Suryadi, S.T.' }}
                        </p>
                        <div class="sales-actions">
                            <a href="#blog" class="sales-button">Baca Artikel <span aria-hidden="true">↓</span></a>
                            <a href="#portfolio" class="sales-button sales-button-light">Lihat Portofolio <span
                                    aria-hidden="true">↓</span></a>
                        </div>
                        <p class="sales-footnote">Modul Pembelajaran Interaktif · Dokumentasi Karya · Catatan &amp; Artikel
                            Teknologi</p>
                        <div class="flex flex-wrap items-center gap-3 mt-6">
                            <a href="{{ route('profil') }}" class="sales-text-link">Kenali Profil &amp; Portofolio Pengampu
                                ({{ $profile->name ?? 'Nizar Suryadi, S.T.' }}) →</a>
                            @if($profile && $profile->cv_file)
                                <a href="{{ Storage::url($profile->cv_file) }}" target="_blank" rel="noopener noreferrer"
                                    class="bg-white text-black font-bold uppercase tracking-widest px-4 py-2 text-[10px] brutal-border brutal-shadow-hover transition inline-flex items-center">
                                    Download CV <i class="bi bi-download ml-1"></i>
                                </a>
                            @endif

                            <div class="flex gap-2 sm:ml-2">
                                @foreach($socialLinks as $link)
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                        class="w-7 h-7 bg-white brutal-border brutal-shadow-hover flex items-center justify-center text-sm transition text-black"
                                        title="{{ $link->name }}">
                                        <i class="{{ $link->icon ?? 'bi bi-link-45deg' }}"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Marquee -->
            <!-- <div class="marquee-container">
                    <div class="marquee-content font-bold uppercase tracking-widest text-lg">
                        @for($i = 0; $i < 4; $i++)
                            <span class="mx-4">• ZARNIZAR.COM</span>
                            <span class="mx-4">• EDUKASI &amp; TEKNOLOGI</span>
                            <span class="mx-4">• RUANG BELAJAR PYTHON</span>
                            <span class="mx-4">• REKAYASA PERANGKAT LUNAK</span>
                            <span class="mx-4">• INOVASI DIGITAL</span>
                            <span class="mx-4">• CATATAN &amp; ARTIKEL</span>
                            <span class="mx-4">• EKOSISTEM SASTI</span>
                            <span class="mx-4">• APRESIASI GTK 2026</span>
                        @endfor
                    </div>
                </div> -->
        @endif

        @if($search === null || $search === '')
            <!-- Featured Learning & Innovation Section -->
            <section id="pembelajaran-unggulan" class="sales-section sales-white">
                <div class="sales-shell">
                    <p class="sales-eyebrow">Shortcut &amp; Akses Cepat</p>
                    <h2 class="sales-heading">Media Pembelajaran &amp; Karya Inovasi Digital.</h2>
                    <div class="sales-grid sales-grid-two">
                        <article class="sales-card sales-card-blue flex flex-col justify-between">
                            <div>
                                <div
                                    class="inline-flex items-center gap-2 bg-white text-navy-blue text-xs font-black px-2.5 py-1 brutal-border uppercase tracking-wider mb-4">
                                    <i class="bi bi-mortarboard-fill"></i> Modul Unggulan · 8 Jam Pelajaran
                                </div>
                                <h3 style="font-size: 32px; line-height: 1.15;" class="font-black mb-3">Zarnizar Learning:
                                    Membaca Kode Python.</h3>
                                <p class="text-white/90 text-sm leading-relaxed mb-4">
                                    Modul interaktif 4 pertemuan terstruktur untuk siswa SMA/SMK dan pemula. Memahami alur
                                    eksekusi variabel, percabangan IF-ELSE, perulangan FOR/WHILE, hingga fungsi dengan
                                    <strong>Visual Code Tracer</strong> dan <strong>In-Browser Python Playground</strong>.
                                </p>
                                <div class="grid grid-cols-2 gap-2 text-xs font-bold text-white mb-6">
                                    <div class="bg-black/30 p-2 brutal-border">✓ 4 Modul Interaktif</div>
                                    <div class="bg-black/30 p-2 brutal-border">✓ Visual Trace Table</div>
                                    <div class="bg-black/30 p-2 brutal-border">✓ Run Python di Browser</div>
                                    <div class="bg-black/30 p-2 brutal-border">✓ 10 Soal Asesmen Mandiri</div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('pembelajaran.index') }}"
                                    class="sales-button sales-button-lime w-full text-center">Masuk ke Ruang Belajar Python
                                    →</a>
                            </div>
                        </article>

                        <article class="sales-card flex flex-col justify-between bg-[#f4f4f0]">
                            <div>
                                <div
                                    class="inline-flex items-center gap-2 bg-navy-blue text-white text-xs font-black px-2.5 py-1 brutal-border uppercase tracking-wider mb-4">
                                    <i class="bi bi-award-fill"></i> Inovasi Pembelajaran · GTK 2026
                                </div>
                                <h3 style="font-size: 32px; line-height: 1.15;" class="font-black mb-3">Dari Kebutuhan
                                    Menjadi Pengabdian.</h3>
                                <p class="text-gray-700 text-sm leading-relaxed mb-4">
                                    Praktik baik dan karya inovasi digital pendidikan: <strong>SASTI (Sistem Administrasi
                                        &amp; Sarana Terintegrasi SMAN 4 Tasikmalaya)</strong>. Menjawab kendala nyata di
                                    sekolah melalui transformasi digital berbasis kemandirian.
                                </p>
                                <div class="grid grid-cols-2 gap-2 text-xs font-bold text-gray-800 mb-6">
                                    <div class="bg-white p-2 brutal-border">📄 Naskah Praktik Baik</div>
                                    <div class="bg-white p-2 brutal-border">🎥 Video Dokumentasi</div>
                                    <div class="bg-white p-2 brutal-border">📊 Bukti Karya &amp; Dampak</div>
                                    <div class="bg-white p-2 brutal-border">💡 Presenter Interaktif</div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('gtk2026') }}" class="sales-button w-full text-center">Pelajari Karya GTK
                                    2026 →</a>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        @endif

        <!-- Global Search -->
        <div class="bg-white border-b-[3px] border-black p-4 md:p-8">
            <div class="max-w-4xl mx-auto">
                <form action="{{ url('/') }}" method="GET"
                    class="flex flex-col sm:flex-row w-full brutal-border brutal-shadow">
                    <label for="portfolio-search" class="sr-only">Cari portfolio dan artikel</label>
                    <input id="portfolio-search" type="search" name="search" maxlength="255" value="{{ $search ?? '' }}"
                        placeholder="CARI PORTFOLIO, ARTIKEL, DLL..."
                        class="w-full bg-white border-b-[3px] sm:border-b-0 sm:border-r-[3px] border-black p-3 md:p-4 text-base md:text-lg font-bold uppercase focus:outline-none focus:bg-[#f4f4f0] transition text-black placeholder-gray-500">
                    <button type="submit"
                        class="w-full sm:w-auto whitespace-nowrap bg-navy-blue text-white px-8 py-3 md:py-4 text-base md:text-lg font-black uppercase hover:bg-black transition">SEARCH_</button>
                </form>
                @error('search')
                    <p class="mt-4 font-bold text-red-700" role="alert">{{ $message }}</p>
                @enderror
                @if($search !== null && $search !== '')
                    <div class="mt-4 font-bold text-sm md:text-base uppercase tracking-widest text-center">
                        SHOWING RESULTS FOR: <span class="bg-black text-white px-2 py-1">{{ $search }}</span>
                        <a href="{{ url('/') }}"
                            class="ml-3 border-b-2 border-black hover:text-navy-blue hover:border-navy-blue transition">CLEAR
                            SEARCH [X]</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Portfolio & Inovasi Digital -->
        <section class="py-16 md:py-24 border-b-[3px] border-black bg-[#f4f4f0]" id="portfolio">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div>
                        <span
                            class="text-xs font-black uppercase tracking-wider bg-navy-blue text-white px-2 py-0.5 brutal-border inline-block mb-2">Inovasi
                            Digital &amp; Rekayasa Sistem</span>
                        <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter">Karya &amp; Ekosistem
                            Teknologi Pendidikan</h2>
                    </div>
                    <p class="text-sm font-medium text-gray-700 max-w-md">Kumpulan aplikasi, sistem manajemen sekolah
                        (SASTI), kasir UMKM, dan platform digital yang dibangun untuk menjawab tantangan operasional
                        nyata.</p>
                </div>

                @if($portfolios->isEmpty())
                    <div class="bg-white brutal-border p-12 text-center font-bold text-2xl uppercase">AWAITING DEPLOYMENT...
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($portfolios as $project)
                            <div class="bg-white brutal-border brutal-shadow flex flex-col group">
                                <div class="h-48 border-b-[3px] border-black relative overflow-hidden bg-gray-200">
                                    @if($project->image)
                                        <x-storage-image :path="$project->image" loading="lazy" alt="{{ $project->title }}"
                                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-300" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-4xl">📁</div>
                                    @endif
                                    @if($project->date)
                                        <div
                                            class="absolute top-0 right-0 bg-navy-blue text-white brutal-border-l brutal-border-b font-bold text-xs px-2 py-1">
                                            {{ \Carbon\Carbon::parse($project->date)->format('M Y') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <h3 class="text-2xl font-black uppercase tracking-tight mb-2">{{ $project->title }}</h3>
                                    <p class="text-sm font-medium mb-6 flex-1 line-clamp-3">
                                        {{ Str::limit(strip_tags($project->description), 150) }}
                                    </p>
                                    @if($project->slug)
                                        <a href="{{ route('portfolio.show', $project->slug) }}"
                                            class="w-full text-center bg-black text-white font-bold uppercase py-3 brutal-border hover:bg-navy-blue hover:text-white transition">
                                            VIEW DETAILS
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <!-- Blog Section -->
        <section class="scroll-mt-24 py-16 md:py-24 border-b-[3px] border-black bg-white" id="blog">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-4 text-center">
                    ARTIKEL &amp; CATATAN
                </h2>
                <p class="text-center font-bold text-gray-600 uppercase text-xs tracking-widest mb-12">Materi
                    Informatika · Tips Pemrograman · Dokumentasi Praktik Baik</p>

                @if($blogPosts->isEmpty())
                    @php $noLogsFound = 'NO LOGS FOUND.'; @endphp
                    <div class="bg-[#f4f4f0] brutal-border p-12 text-center font-bold text-2xl uppercase brutal-shadow">{{ $noLogsFound }}</div>
                @else
                    <div class="space-y-8 max-w-5xl mx-auto">
                        @foreach($blogPosts as $post)
                            <article
                                class="bg-white brutal-border brutal-shadow hover:-translate-y-1 hover:shadow-[6px_6px_0px_#000] transition flex flex-col md:flex-row overflow-hidden group">
                                @if($post->image)
                                    <div
                                        class="md:w-72 lg:w-80 h-48 md:h-auto border-b-[3px] md:border-b-0 md:border-r-[3px] border-black bg-gray-100 overflow-hidden relative shrink-0">
                                        <x-storage-image :path="$post->image" loading="lazy" alt="{{ $post->title }}"
                                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-300" />
                                    </div>
                                @else
                                    <div
                                        class="md:w-72 lg:w-80 h-48 md:h-auto border-b-[3px] md:border-b-0 md:border-r-[3px] border-black bg-[#f4f4f0] flex items-center justify-center text-5xl shrink-0">
                                        📰
                                    </div>
                                @endif

                                <div class="p-6 md:p-8 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2 mb-3 text-xs font-bold uppercase">
                                            <span class="bg-navy-blue text-white px-2.5 py-0.5 brutal-border">
                                                {{ $post->category ? $post->category->name : 'Uncategorized' }}
                                            </span>
                                            <span class="text-gray-400 font-semibold">•</span>
                                            <span class="text-gray-700">
                                                {{ ($post->published_at ?? $post->created_at)->format('d F Y') }}
                                            </span>
                                            <span class="text-gray-400 font-semibold">•</span>
                                            <span class="text-gray-700"><i
                                                    class="bi bi-eye me-1"></i>{{ number_format($post->views) }} views</span>
                                        </div>

                                        <h3
                                            class="text-xl md:text-2xl font-black uppercase tracking-tight group-hover:text-navy-blue transition mb-3">
                                            <a href="{{ route('blog.show', $post->slug) }}"
                                                class="text-black group-hover:text-navy-blue transition">
                                                {{ $post->title }}
                                            </a>
                                        </h3>

                                        <p
                                            class="text-sm font-medium text-gray-700 line-clamp-2 md:line-clamp-3 mb-4 leading-relaxed">
                                            {{ Str::limit(strip_tags($post->content), 180) }}
                                        </p>
                                    </div>

                                    <div
                                        class="pt-4 border-t-2 border-dashed border-gray-200 flex items-center justify-between">
                                        <a href="{{ route('blog.show', $post->slug) }}"
                                            class="inline-flex items-center gap-1.5 font-bold uppercase text-xs tracking-wider text-black group-hover:text-navy-blue transition bg-white px-3 py-1.5 brutal-border brutal-shadow-hover">
                                            BACA ARTIKEL <span aria-hidden="true">→</span>
                                        </a>
                                        @if($post->published_at)
                                            <span class="text-xs font-bold uppercase text-gray-500">
                                                {{ $post->published_at->format('F Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
                @if($blogPosts->total() > 0)
                    <div class="mt-10 space-y-4">
                        <p class="text-center text-sm font-bold">
                            @if($blogPosts->count() > 0)
                                @php $pageSummary = "Menampilkan {$blogPosts->firstItem()}–{$blogPosts->lastItem()} dari {$blogPosts->total()} artikel"; @endphp
                                {{ $pageSummary }}
                            @else
                                {{ $blogPosts->total() }} artikel tersedia. Pilih halaman lain.
                            @endif
                        </p>
                        {{ $blogPosts->onEachSide(1)->links('pagination.databanks') }}
                    </div>
                @endif
            </div>
        </section>

        <x-sales-contact source="home" />
    </main>
    <!-- Footer -->
    <footer class="bg-black text-white py-12 border-t-[4px] border-navy-blue">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-stroke-white mb-2">
                {{ $profile->name ?? 'Nizar Suryadi, S.T.' }}
            </h2>
            <p class="text-xs uppercase font-bold tracking-widest text-gray-400 mb-6">Guru Informatika SMAN 4
                Tasikmalaya · Media Pembelajaran &amp; Inovasi Digital</p>

            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 mb-8 text-xs font-bold uppercase tracking-wider">
                <a href="{{ route('pembelajaran.index') }}"
                    class="text-gray-300 hover:text-white underline">Pembelajaran Python</a>
                <a href="{{ route('pembelajaran.asesmen') }}" class="text-gray-300 hover:text-white underline">Asesmen
                    10 Soal</a>
                <a href="{{ route('gtk2026') }}" class="text-gray-300 hover:text-white underline">Apresiasi GTK 2026</a>
                <a href="{{ route('profil') }}" class="text-gray-300 hover:text-white underline">Profil &amp;
                    Portofolio</a>
                <a href="{{ route('site.home') }}#portfolio" class="text-gray-300 hover:text-white underline">Karya
                    Inovasi</a>
                <a href="{{ route('site.home') }}#blog" class="text-gray-300 hover:text-white underline">Artikel
                    Belajar</a>
                <a href="{{ route('kulapos') }}" class="text-gray-400 hover:text-gray-200">KulaPOS</a>
            </div>

            <div class="flex justify-center gap-4 mb-6">
                @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-white text-black brutal-border hover:bg-navy-blue hover:text-white transition flex items-center justify-center text-lg">
                        <i class="{{ $link->icon ?? 'bi bi-link-45deg' }}"></i>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('login') }}" class="inline-block text-xs text-gray-400 underline mb-4">Login admin</a>
            <div class="font-bold uppercase tracking-widest text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ $profile->name ?? 'Nizar Suryadi, S.T.' }}. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile Menu Toggle
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            if (btn && menu) {
                btn.addEventListener('click', () => {
                    const isClosed = menu.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', String(!isClosed));
                });

                // Close menu when clicking a link
                menu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        menu.classList.add('hidden');
                        btn.setAttribute('aria-expanded', 'false');
                    });
                });
            }
        });
    </script>
</body>

</html>