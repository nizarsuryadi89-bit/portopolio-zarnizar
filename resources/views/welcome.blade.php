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
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

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
            box-shadow: 6px 6px 0px 0px rgba(0,0,0,1);
        }

        .brutal-shadow-hover {
            box-shadow: 6px 6px 0px 0px rgba(0,0,0,1);
            transition: all 0.2s ease-in-out;
        }
        
        .brutal-shadow-hover:hover {
            box-shadow: 2px 2px 0px 0px rgba(0,0,0,1);
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
            background: #1e3a8a; color: #fff; /* Navy Blue */
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
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }

                .bg-navy-blue { background-color: #1e3a8a; }
        .text-navy-blue { color: #1e3a8a; }
        .border-navy-blue { border-color: #1e3a8a; }
        .bg-neon-pink { background-color: #ff00ff; }

        .heroSwiper .swiper-pagination-bullet {
            background: #fff !important;
            opacity: 0.7 !important;
            border: 1.5px solid #000 !important;
            width: 10px !important;
            height: 10px !important;
        }
        .heroSwiper .swiper-pagination-bullet-active {
            background: #1e3a8a !important;
            opacity: 1 !important;
            border: 2px solid #fff !important;
            transform: scale(1.25);
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-14 relative">
            <div class="w-full lg:w-7/12 xl:w-3/5 relative z-10">
                <span class="sales-kicker">Media Pembelajaran Informatika · SMAN 4 Tasikmalaya</span>
                <h1 class="sales-title">@if(!empty($settings['sales_home_headline'])){{ $settings['sales_home_headline'] }}@else Ruang Belajar Koding, Logika &amp; <em>Inovasi Teknologi Pendidikan</em>@endif</h1>
                <p class="sales-intro">{{ ($settings['sales_home_intro'] ?? null) ?: 'Platform belajar pemrograman Python interaktif dengan visual tracing, latihan menulis kode langsung di browser, serta dokumentasi inovasi digital pembelajaran karya Nizar Suryadi, S.T. (Guru Informatika SMAN 4 Tasikmalaya / Peserta GTK 2026 Dedikatif).' }}</p>
                <div class="sales-actions">
                    <a href="{{ route('pembelajaran.index') }}" class="sales-button">Mulai Belajar Python (8 JP) <span aria-hidden="true">→</span></a>
                    <a href="{{ route('gtk2026') }}" class="sales-button sales-button-light">Karya Inovasi GTK 2026 <span aria-hidden="true">↗</span></a>
                </div>
                <p class="sales-footnote">Visual Code Tracer · In-Browser Python Playground · Lembar Kerja &amp; Asesmen Mandiri</p>
                <div class="flex flex-wrap items-center gap-3 mt-6">
                    <a href="#about" class="sales-text-link">Kenali Guru Pengampu ({{ $profile->name ?? 'Nizar Suryadi, S.T.' }}) →</a>
                    @if($profile && $profile->cv_file)
                        <a href="{{ Storage::url($profile->cv_file) }}" target="_blank" rel="noopener noreferrer" class="bg-white text-black font-bold uppercase tracking-widest px-4 py-2 text-[10px] brutal-border brutal-shadow-hover transition inline-flex items-center">
                            Download CV <i class="bi bi-download ml-1"></i>
                        </a>
                    @endif
                    
                    <div class="flex gap-2 sm:ml-2">
                        @foreach($socialLinks as $link)
                            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 bg-white brutal-border brutal-shadow-hover flex items-center justify-center text-sm transition text-black" title="{{ $link->name }}">
                                <i class="{{ $link->icon ?? 'bi bi-link-45deg' }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="w-full lg:w-5/12 xl:w-2/5 flex justify-center lg:justify-end relative z-10 mt-8 lg:mt-0">
                <!-- Proportional Neo-Brutalist Frame for Slider -->
                <div class="relative w-full max-w-[340px] sm:max-w-[420px] lg:max-w-[450px] aspect-square mx-auto lg:mr-0" style="width: 100%; max-width: 450px; aspect-ratio: 1/1;">
                    <div class="absolute inset-0 bg-navy-blue text-white brutal-border translate-x-3 translate-y-3 sm:translate-x-4 sm:translate-y-4"></div>
                    @if(isset($sliders) && $sliders->isNotEmpty())
                        <div class="swiper heroSwiper relative w-full h-full brutal-border bg-white" style="overflow: hidden; width: 100%; height: 100%;">
                            <div class="swiper-wrapper">
                                @foreach($sliders as $slider)
                                    <div class="swiper-slide relative w-full h-full bg-black group" style="width: 100%; height: 100%;">
                                        <x-storage-image :path="$slider->image" :loading="$loop->first ? 'eager' : 'lazy'" :fetchpriority="$loop->first ? 'high' : 'auto'" class="w-full h-full object-cover opacity-85 group-hover:opacity-100 transition duration-500" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $slider->title ?: 'Banner portfolio' }}" />
                                        @if($slider->title || $slider->subtitle)
                                            <div class="absolute inset-0 z-20 flex flex-col items-start justify-end p-6 sm:p-8 bg-gradient-to-t from-black/90 via-black/40 to-transparent">
                                                @if($slider->title)
                                                    <h3 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight mb-1 drop-shadow-[2px_2px_0_rgba(0,0,0,1)]">{{ $slider->title }}</h3>
                                                @endif
                                                @if($slider->subtitle)
                                                    <p class="text-xs sm:text-sm font-bold bg-navy-blue text-white px-2.5 py-1 inline-block brutal-border mb-3">{{ $slider->subtitle }}</p>
                                                @endif
                                                @if($slider->url)
                                                    <a href="{{ $slider->url }}" class="bg-white text-black font-bold uppercase tracking-widest px-4 py-2 text-xs brutal-border hover:bg-navy-blue hover:text-white transition inline-block">
                                                        READ MORE →
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next text-white drop-shadow-[2px_2px_0_rgba(0,0,0,1)] after:!text-xl !w-10 !h-10 !right-2"></div>
                            <div class="swiper-button-prev text-white drop-shadow-[2px_2px_0_rgba(0,0,0,1)] after:!text-xl !w-10 !h-10 !left-2"></div>
                            <div class="swiper-pagination !bottom-3"></div>
                        </div>
                    @elseif($profile && $profile->photo)
                        <x-storage-image :path="$profile->photo" alt="Profile Photo" class="relative w-full h-full object-cover brutal-border grayscale hover:grayscale-0 transition duration-500 bg-white" style="width: 100%; height: 100%; object-fit: cover;" />
                    @else
                        <div class="relative w-full h-full bg-white brutal-border flex items-center justify-center text-6xl" style="width: 100%; height: 100%;">
                            🤖
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Marquee -->
    <div class="marquee-container">
        <div class="marquee-content font-bold uppercase tracking-widest text-lg">
            @for($i=0; $i<4; $i++)
                <span class="mx-4">• ZARNIZAR LEARNING</span>
                <span class="mx-4">• INFORMATIKA KELAS XII</span>
                <span class="mx-4">• MEMBACA KODE PYTHON</span>
                <span class="mx-4">• VISUAL CODE TRACER</span>
                <span class="mx-4">• IN-BROWSER PLAYGROUND</span>
                <span class="mx-4">• GTK 2026 DEDIKATIF</span>
                <span class="mx-4">• EKOSISTEM SASTI SMAN 4 TASIKMALAYA</span>
                <span class="mx-4">• ASESMEN INTERAKTIF</span>
            @endfor
        </div>
    </div>
    @endif

    @if($search === null || $search === '')
    <!-- Featured Learning & Innovation Section -->
    <section id="pembelajaran-unggulan" class="sales-section sales-white">
        <div class="sales-shell">
            <p class="sales-eyebrow">Modul &amp; Ruang Pembelajaran</p>
            <h2 class="sales-heading">Belajar Membaca &amp; Menulis Kode<br>dengan Cara yang Nyata &amp; Interaktif.</h2>
            <div class="sales-grid sales-grid-two">
                <article class="sales-card sales-card-blue flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-white text-navy-blue text-xs font-black px-2.5 py-1 brutal-border uppercase tracking-wider mb-4">
                            <i class="bi bi-mortarboard-fill"></i> Modul Unggulan · 8 Jam Pelajaran
                        </div>
                        <h3 style="font-size: 32px; line-height: 1.15;" class="font-black mb-3">Zarnizar Learning: Membaca Kode Python.</h3>
                        <p class="text-white/90 text-sm leading-relaxed mb-4">
                            Modul interaktif 4 pertemuan terstruktur untuk siswa SMA/SMK dan pemula. Memahami alur eksekusi variabel, percabangan IF-ELSE, perulangan FOR/WHILE, hingga fungsi dengan <strong>Visual Code Tracer</strong> dan <strong>In-Browser Python Playground</strong>.
                        </p>
                        <div class="grid grid-cols-2 gap-2 text-xs font-bold text-white mb-6">
                            <div class="bg-black/30 p-2 brutal-border">✓ 4 Modul Interaktif</div>
                            <div class="bg-black/30 p-2 brutal-border">✓ Visual Trace Table</div>
                            <div class="bg-black/30 p-2 brutal-border">✓ Run Python di Browser</div>
                            <div class="bg-black/30 p-2 brutal-border">✓ 10 Soal Asesmen Mandiri</div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('pembelajaran.index') }}" class="sales-button sales-button-lime w-full text-center">Masuk ke Ruang Belajar Python →</a>
                    </div>
                </article>

                <article class="sales-card flex flex-col justify-between bg-[#f4f4f0]">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-navy-blue text-white text-xs font-black px-2.5 py-1 brutal-border uppercase tracking-wider mb-4">
                            <i class="bi bi-award-fill"></i> Inovasi Pembelajaran · GTK 2026
                        </div>
                        <h3 style="font-size: 32px; line-height: 1.15;" class="font-black mb-3">Dari Kebutuhan Menjadi Pengabdian.</h3>
                        <p class="text-gray-700 text-sm leading-relaxed mb-4">
                            Praktik baik dan karya inovasi digital pendidikan: <strong>SASTI (Sistem Administrasi &amp; Sarana Terintegrasi SMAN 4 Tasikmalaya)</strong>. Menjawab kendala nyata di sekolah melalui transformasi digital berbasis kemandirian.
                        </p>
                        <div class="grid grid-cols-2 gap-2 text-xs font-bold text-gray-800 mb-6">
                            <div class="bg-white p-2 brutal-border">📄 Naskah Praktik Baik</div>
                            <div class="bg-white p-2 brutal-border">🎥 Video Dokumentasi</div>
                            <div class="bg-white p-2 brutal-border">📊 Bukti Karya &amp; Dampak</div>
                            <div class="bg-white p-2 brutal-border">💡 Presenter Interaktif</div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('gtk2026') }}" class="sales-button w-full text-center">Pelajari Karya GTK 2026 →</a>
                    </div>
                </article>
            </div>

            <!-- Quick Access Bar to 4 Meetings -->
            <div class="mt-8 bg-[#f4f4f0] brutal-border brutal-shadow p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-2 border-black pb-4 mb-4">
                    <div>
                        <span class="text-xs font-black uppercase tracking-wider bg-black text-white px-2 py-0.5 inline-block">Roadmap Belajar</span>
                        <h4 class="text-xl font-black uppercase mt-1">4 Pertemuan Terstruktur Membaca &amp; Menulis Kode</h4>
                    </div>
                    <a href="{{ route('pembelajaran.asesmen') }}" class="text-xs font-bold uppercase tracking-wider bg-white brutal-border px-3 py-2 hover:bg-navy-blue hover:text-white transition inline-flex items-center gap-1 self-start md:self-auto">
                        <i class="bi bi-check2-circle"></i> Langsung ke Asesmen (10 Soal) →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('pembelajaran.pertemuan', 1) }}" class="bg-white brutal-border p-4 hover:-translate-y-1 hover:shadow-[4px_4px_0_0_#000] transition group flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase bg-navy-blue text-white px-1.5 py-0.5 inline-block mb-2">Pertemuan 1 · 2 JP</span>
                            <div class="font-black text-sm uppercase group-hover:text-navy-blue transition">Variabel &amp; Tipe Data</div>
                            <p class="text-xs text-gray-600 mt-1">Mental model kotak &amp; label, casting data, input/output.</p>
                        </div>
                        <span class="text-xs font-bold text-navy-blue mt-3 inline-block">Buka Modul 01 →</span>
                    </a>
                    <a href="{{ route('pembelajaran.pertemuan', 2) }}" class="bg-white brutal-border p-4 hover:-translate-y-1 hover:shadow-[4px_4px_0_0_#000] transition group flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase bg-navy-blue text-white px-1.5 py-0.5 inline-block mb-2">Pertemuan 2 · 2 JP</span>
                            <div class="font-black text-sm uppercase group-hover:text-navy-blue transition">Percabangan IF-ELSE</div>
                            <p class="text-xs text-gray-600 mt-1">Alur logika percabangan, kondisi jamak, nested decision.</p>
                        </div>
                        <span class="text-xs font-bold text-navy-blue mt-3 inline-block">Buka Modul 02 →</span>
                    </a>
                    <a href="{{ route('pembelajaran.pertemuan', 3) }}" class="bg-white brutal-border p-4 hover:-translate-y-1 hover:shadow-[4px_4px_0_0_#000] transition group flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase bg-navy-blue text-white px-1.5 py-0.5 inline-block mb-2">Pertemuan 3 · 2 JP</span>
                            <div class="font-black text-sm uppercase group-hover:text-navy-blue transition">Perulangan &amp; Tracer</div>
                            <p class="text-xs text-gray-600 mt-1">Iterasi for/while, pelacak nilai (tracer table) step-by-step.</p>
                        </div>
                        <span class="text-xs font-bold text-navy-blue mt-3 inline-block">Buka Modul 03 →</span>
                    </a>
                    <a href="{{ route('pembelajaran.pertemuan', 4) }}" class="bg-white brutal-border p-4 hover:-translate-y-1 hover:shadow-[4px_4px_0_0_#000] transition group flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase bg-navy-blue text-white px-1.5 py-0.5 inline-block mb-2">Pertemuan 4 · 2 JP</span>
                            <div class="font-black text-sm uppercase group-hover:text-navy-blue transition">Fungsi &amp; Modulasi</div>
                            <p class="text-xs text-gray-600 mt-1">Parameter, return value, dan memecah problem kompleks.</p>
                        </div>
                        <span class="text-xs font-bold text-navy-blue mt-3 inline-block">Buka Modul 04 →</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Global Search -->
    <div class="bg-white border-b-[3px] border-black p-4 md:p-8">
        <div class="max-w-4xl mx-auto">
            <form action="{{ url('/') }}" method="GET" class="flex flex-col sm:flex-row w-full brutal-border brutal-shadow">
                <label for="portfolio-search" class="sr-only">Cari portfolio dan artikel</label>
                <input id="portfolio-search" type="search" name="search" maxlength="255" value="{{ $search ?? '' }}" placeholder="CARI PORTFOLIO, ARTIKEL, DLL..." class="w-full bg-white border-b-[3px] sm:border-b-0 sm:border-r-[3px] border-black p-3 md:p-4 text-base md:text-lg font-bold uppercase focus:outline-none focus:bg-[#f4f4f0] transition text-black placeholder-gray-500">
                <button type="submit" class="w-full sm:w-auto whitespace-nowrap bg-navy-blue text-white px-8 py-3 md:py-4 text-base md:text-lg font-black uppercase hover:bg-black transition">SEARCH_</button>
            </form>
            @error('search')
                <p class="mt-4 font-bold text-red-700" role="alert">{{ $message }}</p>
            @enderror
            @if($search !== null && $search !== '')
                <div class="mt-4 font-bold text-sm md:text-base uppercase tracking-widest text-center">
                    SHOWING RESULTS FOR: <span class="bg-black text-white px-2 py-1">{{ $search }}</span> 
                    <a href="{{ url('/') }}" class="ml-3 border-b-2 border-black hover:text-navy-blue hover:border-navy-blue transition">CLEAR SEARCH [X]</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Portfolio & Inovasi Digital -->
    <section class="py-16 md:py-24 border-b-[3px] border-black bg-[#f4f4f0]" id="portfolio">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-black uppercase tracking-wider bg-navy-blue text-white px-2 py-0.5 brutal-border inline-block mb-2">Inovasi Digital &amp; Rekayasa Sistem</span>
                    <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter">Karya &amp; Ekosistem Teknologi Pendidikan</h2>
                </div>
                <p class="text-sm font-medium text-gray-700 max-w-md">Kumpulan aplikasi, sistem manajemen sekolah (SASTI), kasir UMKM, dan platform digital yang dibangun untuk menjawab tantangan operasional nyata.</p>
            </div>
            
            @if($portfolios->isEmpty())
                <div class="bg-white brutal-border p-12 text-center font-bold text-2xl uppercase">AWAITING DEPLOYMENT...</div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($portfolios as $project)
                        <div class="bg-white brutal-border brutal-shadow flex flex-col group">
                            <div class="h-48 border-b-[3px] border-black relative overflow-hidden bg-gray-200">
                                @if($project->image)
                                    <x-storage-image :path="$project->image" loading="lazy" alt="{{ $project->title }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-300" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl">📁</div>
                                @endif
                                @if($project->date)
                                    <div class="absolute top-0 right-0 bg-navy-blue text-white brutal-border-l brutal-border-b font-bold text-xs px-2 py-1">
                                        {{ \Carbon\Carbon::parse($project->date)->format('M Y') }}
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-2xl font-black uppercase tracking-tight mb-2">{{ $project->title }}</h3>
                                <p class="text-sm font-medium mb-6 flex-1 line-clamp-3">{{ Str::limit(strip_tags($project->description), 150) }}</p>
                                @if($project->slug)
                                    <a href="{{ route('portfolio.show', $project->slug) }}" class="w-full text-center bg-black text-white font-bold uppercase py-3 brutal-border hover:bg-navy-blue hover:text-white transition">
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

    @if($search === null || $search === '')
    <!-- About & Skills Layout (Split Grid) -->
    <section class="border-b-[3px] border-black">
        <div class="flex flex-col md:flex-row">
            <!-- About -->
            <div id="about" class="md:w-1/2 p-8 md:p-16 border-b-[3px] md:border-b-0 md:border-r-[3px] border-black bg-white">
                <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tighter mb-4"><span class="bg-black text-white px-2">01</span> PROFIL GURU &amp; DEVELOPER</h2>
                <div class="inline-block bg-navy-blue text-white text-xs font-bold px-3 py-1 brutal-border uppercase mb-6 tracking-wider">
                    Guru Informatika SMAN 4 Tasikmalaya · Peserta GTK 2026 Dedikatif
                </div>
                <div class="text-lg font-medium leading-relaxed whitespace-pre-line mb-8">
                    {{ $profile->description ?? 'Guru Informatika di SMAN 4 Tasikmalaya dengan dedikasi mengintegrasikan teknologi dan rekayasa perangkat lunak dalam ekosistem pembelajaran modern.' }}
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($profile && $profile->location)
                        <div class="bg-[#f4f4f0] brutal-border p-4">
                            <div class="text-xs font-bold uppercase text-gray-500 mb-1">LOCATION</div>
                            <div class="font-bold">{{ $profile->location }}</div>
                        </div>
                    @endif
                    @if($profile && $profile->email)
                        <div class="bg-[#f4f4f0] brutal-border p-4">
                            <div class="text-xs font-bold uppercase text-gray-500 mb-1">EMAIL</div>
                            <div class="font-bold truncate">{{ $profile->email }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Skills -->
            <div id="skills" class="md:w-1/2 p-8 md:p-16 bg-[#f4f4f0]">
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-8"><span class="bg-navy-blue text-white px-2 brutal-border">02</span> TECH STACK</h2>
                
                @if($skills->isEmpty())
                    <div class="bg-white brutal-border p-8 text-center font-bold">NO MODULES LOADED.</div>
                @else
                    <div class="flex flex-wrap gap-4">
                        @foreach($skills as $skill)
                            <div class="bg-white brutal-border brutal-shadow-hover p-4 flex flex-col justify-between" style="width: calc(50% - 8px); min-width: 150px;">
                                <div class="flex justify-between items-start mb-4">
                                    @if($skill->icon)
                                        <i class="{{ $skill->icon }} text-3xl"></i>
                                    @else
                                        <i class="bi bi-cpu text-3xl"></i>
                                    @endif
                                    @if($skill->percentage)
                                        <span class="font-black text-xl">{{ $skill->percentage }}%</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold uppercase tracking-widest text-sm">{{ $skill->name }}</div>
                                    @if($skill->category)
                                        <div class="text-xs bg-black text-white px-1 inline-block mt-1">{{ $skill->category }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Experience & Education -->
    <section class="py-16 md:py-24 border-b-[3px] border-black bg-white" id="experience">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-12 text-center"><span class="bg-navy-blue text-white px-2 brutal-border">03</span> SYSTEM LOGS (EXP)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16">
                <!-- Experience -->
                <div>
                    <h3 class="text-2xl font-black uppercase mb-6 border-b-[3px] border-black pb-2">WORK_HISTORY</h3>
                    @forelse($experiences as $exp)
                        <div class="mb-8 relative pl-6 border-l-[3px] border-black">
                            <div class="absolute w-4 h-4 bg-navy-blue brutal-border -left-[10px] top-1"></div>
                            <div class="text-xs font-bold uppercase bg-black text-white inline-block px-2 py-1 mb-2">
                                {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - 
                                @if($exp->is_current) PRESENT @else {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'PRESENT' }} @endif
                            </div>
                            <h4 class="text-xl font-bold uppercase">{{ $exp->position }}</h4>
                            <div class="font-bold text-navy-blue mb-2">{{ $exp->organization }}</div>
                            <p class="text-sm font-medium">{{ $exp->description }}</p>
                        </div>
                    @empty
                        <p class="font-bold italic">No data found.</p>
                    @endforelse
                </div>

                <!-- Education -->
                <div id="education">
                    <h3 class="text-2xl font-black uppercase mb-6 border-b-[3px] border-black pb-2">TRAINING_DATA</h3>
                    @forelse($educations as $edu)
                        <div class="mb-8 bg-[#f4f4f0] brutal-border p-6 brutal-shadow">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-xl font-bold uppercase w-3/4">{{ $edu->institution }}</h4>
                                <span class="bg-black text-white text-xs font-bold px-2 py-1 brutal-border">
                                    {{ $edu->start_year }} - {{ $edu->end_year ?? 'NOW' }}
                                </span>
                            </div>
                            <div class="font-bold text-lg border-b-2 border-dashed border-black pb-2 mb-2">{{ $edu->degree }} in {{ $edu->field }}</div>
                            <p class="text-sm font-medium">{{ $edu->description }}</p>
                        </div>
                    @empty
                        <p class="font-bold italic">No data found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Blog Section -->
    <section class="scroll-mt-24 py-16 md:py-24 border-b-[3px] border-black bg-white" id="blog">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-4 text-center"><span class="bg-navy-blue text-white px-4 py-1 brutal-border">05</span> ARTIKEL &amp; CATATAN BELAJAR</h2>
            <p class="text-center font-bold text-gray-600 uppercase text-xs tracking-widest mb-12">Materi Informatika · Tips Pemrograman · Dokumentasi Praktik Baik</p>
            
            @if($groupedPosts->isEmpty())
                <div class="bg-[#f4f4f0] brutal-border p-12 text-center font-bold text-2xl uppercase brutal-shadow">NO LOGS FOUND.</div>
            @else
                <div class="space-y-12">
                    @foreach($groupedPosts as $categoryName => $months)
                        <div class="bg-[#f4f4f0] brutal-border brutal-shadow p-6 md:p-10">
                            <h3 class="text-3xl font-black uppercase tracking-tight mb-8 border-b-[3px] border-black pb-4 inline-block">{{ $categoryName }}</h3>
                            
                            <div class="space-y-8">
                                @foreach($months as $monthName => $monthlyPosts)
                                    <div>
                                        <h4 class="text-xl font-bold uppercase bg-black text-white px-3 py-1 inline-block mb-4">{{ $monthName }}</h4>
                                        <ul class="space-y-4">
                                            @foreach($monthlyPosts as $post)
                                                <li class="flex flex-col md:flex-row md:items-center justify-between bg-white brutal-border p-4 hover:bg-navy-blue hover:text-white transition group">
                                                    <div class="flex-1">
                                                        <h5 class="text-lg font-black uppercase leading-tight group-hover:text-white mb-2 md:mb-0">{{ $post->title }}</h5>
                                                        <p class="text-sm font-medium line-clamp-1 group-hover:text-gray-200 text-gray-600">{{ Str::limit(strip_tags($post->content), 80) }}</p>
                                                    </div>
                                                    <div class="mt-4 md:mt-0 flex items-center gap-4 text-xs font-bold uppercase">
                                                        <span><i class="bi bi-eye mr-1"></i> {{ number_format($post->views) }}</span>
                                                        <span>{{ \Carbon\Carbon::parse($post->published_at)->format('d M') }}</span>
                                                        <a href="{{ route('blog.show', $post->slug) }}" class="bg-black text-white px-4 py-2 group-hover:bg-white group-hover:text-black transition">READ -></a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            @if($blogPosts->total() > 0)
                <div class="mt-10 space-y-4">
                    <p class="text-center text-sm font-bold">
                        @if($blogPosts->count() > 0)
                            Menampilkan {{ $blogPosts->firstItem() }}–{{ $blogPosts->lastItem() }} dari {{ $blogPosts->total() }} artikel
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
            <h2 class="text-3xl font-black uppercase tracking-tighter text-stroke-white mb-2">{{ $profile->name ?? 'Nizar Suryadi, S.T.' }}</h2>
            <p class="text-xs uppercase font-bold tracking-widest text-gray-400 mb-6">Guru Informatika SMAN 4 Tasikmalaya · Media Pembelajaran &amp; Inovasi Digital</p>
            
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 mb-8 text-xs font-bold uppercase tracking-wider">
                <a href="{{ route('pembelajaran.index') }}" class="text-gray-300 hover:text-white underline">Pembelajaran Python</a>
                <a href="{{ route('pembelajaran.asesmen') }}" class="text-gray-300 hover:text-white underline">Asesmen 10 Soal</a>
                <a href="{{ route('gtk2026') }}" class="text-gray-300 hover:text-white underline">Apresiasi GTK 2026</a>
                <a href="{{ route('site.home') }}#portfolio" class="text-gray-300 hover:text-white underline">Karya Inovasi</a>
                <a href="{{ route('site.home') }}#blog" class="text-gray-300 hover:text-white underline">Artikel Belajar</a>
                <a href="{{ route('kulapos') }}" class="text-gray-400 hover:text-gray-200">KulaPOS</a>
            </div>

            <div class="flex justify-center gap-4 mb-6">
                @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white text-black brutal-border hover:bg-navy-blue hover:text-white transition flex items-center justify-center text-lg">
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

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if(document.querySelector('.heroSwiper') && typeof Swiper !== 'undefined') {
                const swiper = new Swiper('.heroSwiper', {
                    loop: true,
                    autoplay: { delay: 5000, disableOnInteraction: false },
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    effect: 'fade',
                    fadeEffect: { crossFade: true }
                });
            }

            // Mobile Menu Toggle
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            if(btn && menu) {
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
