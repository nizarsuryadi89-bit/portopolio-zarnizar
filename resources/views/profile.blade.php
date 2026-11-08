<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <x-marketing-head 
        :title="'Profil & Rekam Jejak — ' . ($profile->name ?? 'Nizar Suryadi, S.T.') . ' | Zarnizar'" 
        :description="'Profil lengkap, keahlian teknis (tech stack), riwayat pengalaman mengajar & rekayasa perangkat lunak, serta karya inovasi digital oleh Nizar Suryadi, S.T. (Guru Informatika SMAN 4 Tasikmalaya).'" 
        :canonical="url()->current()" 
        :settings="$settings" 
    />

    @if(!empty($settings['favicon']))
        <link rel="icon" href="{{ Storage::url($settings['favicon']) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

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

        .brutal-shadow-sm {
            box-shadow: 3px 3px 0px 0px rgba(0, 0, 0, 1);
        }

        .brutal-shadow-hover {
            box-shadow: 6px 6px 0px 0px rgba(0, 0, 0, 1);
            transition: all 0.2s ease-in-out;
        }

        .brutal-shadow-hover:hover {
            box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 1);
            transform: translate(4px, 4px);
        }

        .text-navy-blue {
            color: #1e3a8a;
        }

        .bg-navy-blue {
            background-color: #1e3a8a;
        }

        .border-navy-blue {
            border-color: #1e3a8a;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace !important;
        }
    </style>
</head>

<body class="text-gray-900 antialiased selection:bg-navy-blue selection:text-white">

    <a href="#main-content" class="sales-skip-link">Langsung ke isi</a>
    <x-sales-navigation />

    <main id="main-content">

        <!-- Breadcrumb & Top Header -->
        <header class="py-10 md:py-16 border-b-[3px] border-black bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Breadcrumb -->
                <div class="mb-6">
                    <a href="{{ route('site.home') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-black bg-[#f4f4f0] px-3.5 py-2 brutal-border brutal-shadow-hover transition">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>

                <div class="max-w-4xl">
                    <div class="inline-flex items-center gap-2 bg-navy-blue text-white text-xs font-bold px-3 py-1 brutal-border uppercase tracking-widest mb-4">
                        <i class="bi bi-person-badge"></i> PROFIL &amp; REKAM JEJAK
                    </div>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-black uppercase tracking-tight text-black leading-tight mb-4">
                        {{ $profile->name ?? 'Nizar Suryadi, S.T.' }}
                    </h1>
                    <p class="text-lg md:text-xl font-medium text-gray-700 leading-relaxed mb-6">
                        Guru Informatika SMA Negeri 4 Tasikmalaya · Praktisi Rekayasa Perangkat Lunak · Pengembang Ekosistem Digital Pendidikan (SASTI &amp; Zarnizar Learning) · Peserta Apresiasi GTK 2026 Kategori Dedikatif.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        @if($profile && $profile->cv_file)
                            <a href="{{ Storage::url($profile->cv_file) }}" target="_blank" rel="noopener noreferrer" class="bg-black text-white px-5 py-3 font-bold uppercase tracking-wider text-xs brutal-border hover:bg-navy-blue transition inline-flex items-center gap-2 brutal-shadow-hover">
                                <i class="bi bi-file-earmark-pdf"></i> Unduh CV (PDF)
                            </a>
                        @endif

                        <a href="https://wa.me/628999770477" target="_blank" rel="noopener noreferrer" class="bg-white text-black px-5 py-3 font-bold uppercase tracking-wider text-xs brutal-border hover:bg-green-600 hover:text-white transition inline-flex items-center gap-2 brutal-shadow-hover">
                            <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                        </a>

                        <div class="flex items-center gap-2">
                            @foreach($socialLinks as $link)
                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white text-black brutal-border hover:bg-navy-blue hover:text-white transition flex items-center justify-center text-base" title="{{ $link->platform ?? 'Social' }}">
                                    <i class="{{ $link->icon ?? 'bi bi-link-45deg' }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- Section 01 & 02: About & Skills Grid -->
        <section class="border-b-[3px] border-black">
            <div class="flex flex-col md:flex-row">
                
                <!-- About Column -->
                <div id="about" class="md:w-1/2 p-8 md:p-16 border-b-[3px] md:border-b-0 md:border-r-[3px] border-black bg-white">
                    <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tighter mb-4">
                        <span class="bg-black text-white px-2">01</span> PROFIL GURU &amp; DEVELOPER
                    </h2>
                    <div class="inline-block bg-navy-blue text-white text-xs font-bold px-3 py-1 brutal-border uppercase mb-6 tracking-wider">
                        Guru Informatika SMAN 4 Tasikmalaya · Peserta GTK 2026 Dedikatif
                    </div>
                    
                    <div class="text-base sm:text-lg font-medium leading-relaxed whitespace-pre-line text-gray-800 mb-8 text-justify">
                        {{ $profile->description ?? 'Guru Informatika di SMAN 4 Tasikmalaya dengan dedikasi mengintegrasikan teknologi dan rekayasa perangkat lunak dalam ekosistem pembelajaran modern. Berpengalaman dalam merancang sistem informasi manajemen sekolah, media pembelajaran koding interaktif, dan transformasi digital yang berdampak nyata bagi guru dan siswa.' }}
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($profile && $profile->location)
                            <div class="bg-[#f4f4f0] brutal-border p-4">
                                <div class="text-xs font-bold uppercase text-gray-500 mb-1">LOCATION</div>
                                <div class="font-bold text-black">{{ $profile->location }}</div>
                            </div>
                        @endif
                        @if($profile && $profile->email)
                            <div class="bg-[#f4f4f0] brutal-border p-4">
                                <div class="text-xs font-bold uppercase text-gray-500 mb-1">EMAIL</div>
                                <div class="font-bold truncate text-black">{{ $profile->email }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tech Stack Column -->
                <div id="skills" class="md:w-1/2 p-8 md:p-16 bg-[#f4f4f0]">
                    <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tighter mb-8">
                        <span class="bg-navy-blue text-white px-2 brutal-border">02</span> TECH STACK
                    </h2>

                    @if($skills->isEmpty())
                        <div class="bg-white brutal-border p-8 text-center font-bold">NO MODULES LOADED.</div>
                    @else
                        <div class="flex flex-wrap gap-4">
                            @foreach($skills as $skill)
                                <div class="bg-white brutal-border brutal-shadow-hover p-4 flex flex-col justify-between" style="width: calc(50% - 8px); min-width: 140px;">
                                    <div class="flex justify-between items-start mb-4">
                                        @if($skill->icon)
                                            <i class="{{ $skill->icon }} text-3xl text-black"></i>
                                        @else
                                            <i class="bi bi-cpu text-3xl text-black"></i>
                                        @endif
                                        @if($skill->percentage)
                                            <span class="font-black text-xl text-black">{{ $skill->percentage }}%</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold uppercase tracking-widest text-sm text-black">{{ $skill->name }}</div>
                                        @if($skill->category)
                                            <div class="text-[10px] bg-black text-white px-1.5 py-0.5 inline-block mt-1 font-bold uppercase">{{ $skill->category }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </section>

        <!-- Section 03: Experience & Education -->
        <section class="py-16 md:py-24 border-b-[3px] border-black bg-white" id="experience">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter mb-12 text-center">
                    <span class="bg-navy-blue text-white px-2 brutal-border">03</span> SYSTEM LOGS (EXP)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16">
                    <!-- Experience -->
                    <div>
                        <h3 class="text-2xl font-black uppercase mb-6 border-b-[3px] border-black pb-2 flex items-center gap-2">
                            <i class="bi bi-briefcase-fill text-navy-blue"></i> WORK_HISTORY
                        </h3>
                        @forelse($experiences as $exp)
                            <div class="mb-8 relative pl-6 border-l-[3px] border-black">
                                <div class="absolute w-4 h-4 bg-navy-blue brutal-border -left-[10px] top-1"></div>
                                <div class="text-xs font-bold uppercase bg-black text-white inline-block px-2 py-1 mb-2">
                                    {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                                    @if($exp->is_current) PRESENT @else
                                        {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'PRESENT' }}
                                    @endif
                                </div>
                                <h4 class="text-xl font-bold uppercase text-black">{{ $exp->position }}</h4>
                                <div class="font-bold text-navy-blue mb-2">{{ $exp->organization }}</div>
                                <p class="text-sm font-medium text-gray-700 leading-relaxed text-justify">{{ $exp->description }}</p>
                            </div>
                        @empty
                            <p class="font-bold italic text-gray-500">No data found.</p>
                        @endforelse
                    </div>

                    <!-- Education -->
                    <div id="education">
                        <h3 class="text-2xl font-black uppercase mb-6 border-b-[3px] border-black pb-2 flex items-center gap-2">
                            <i class="bi bi-mortarboard-fill text-navy-blue"></i> TRAINING_DATA
                        </h3>
                        @forelse($educations as $edu)
                            <div class="mb-8 bg-[#f4f4f0] brutal-border p-6 brutal-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="text-xl font-bold uppercase w-3/4 text-black">{{ $edu->institution }}</h4>
                                    <span class="bg-black text-white text-xs font-bold px-2 py-1 brutal-border shrink-0">
                                        {{ $edu->start_year }} - {{ $edu->end_year ?? 'NOW' }}
                                    </span>
                                </div>
                                <div class="font-bold text-base border-b-2 border-dashed border-black pb-2 mb-2 text-navy-blue">
                                    {{ $edu->degree }} in {{ $edu->field }}
                                </div>
                                <p class="text-sm font-medium text-gray-700 leading-relaxed text-justify">{{ $edu->description }}</p>
                            </div>
                        @empty
                            <p class="font-bold italic text-gray-500">No data found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 04: Portofolio & Rekam Jejak Karya Digital -->
        <section class="py-16 md:py-24 border-b-[3px] border-black bg-[#f4f4f0]" id="portofolio-karya">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="bg-black text-white px-3 py-1 font-bold text-xs uppercase tracking-widest brutal-border mb-3 inline-block">
                        REKAM JEJAK DIGITAL
                    </span>
                    <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tighter text-black">
                        Portofolio &amp; Karya Inovasi
                    </h2>
                    <p class="text-gray-600 font-medium mt-3">
                        Kumpulan sistem informasi manajemen sekolah, media pembelajaran interaktif, serta aplikasi yang telah dibangun dan diimplementasikan.
                    </p>
                </div>

                <!-- Showcase Unggulan: SASTI, Zarnizar Learning, GTK 2026 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    
                    <!-- Card 1: SASTI -->
                    <div class="bg-white brutal-border brutal-shadow p-6 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-black uppercase bg-navy-blue text-white px-2 py-1 brutal-border inline-block mb-3">
                                EKOSISTEM SEKOLAH
                            </div>
                            <h3 class="text-2xl font-black uppercase text-black mb-2">SASTI Patas</h3>
                            <p class="text-sm text-gray-700 font-medium leading-relaxed mb-4">
                                Sistem Administrasi &amp; Sarana Terintegrasi SMAN 4 Tasikmalaya. Mengelola digitalisasi persuratan, inventaris, presensi, dan layanan sekolah terpadu.
                            </p>
                        </div>
                        <a href="{{ route('site.home') }}#portfolio" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase text-navy-blue underline hover:text-black">
                            Lihat Dokumentasi Proyek &rarr;
                        </a>
                    </div>

                    <!-- Card 2: Zarnizar Learning -->
                    <div class="bg-white brutal-border brutal-shadow p-6 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-black uppercase bg-black text-white px-2 py-1 brutal-border inline-block mb-3">
                                MEDIA PEMBELAJARAN
                            </div>
                            <h3 class="text-2xl font-black uppercase text-black mb-2">Zarnizar Learning</h3>
                            <p class="text-sm text-gray-700 font-medium leading-relaxed mb-4">
                                Platform interaktif 4 pertemuan (8 JP) untuk siswa memahami eksekusi kode Python dengan Visual Code Tracer dan In-Browser Playground.
                            </p>
                        </div>
                        <a href="{{ route('pembelajaran.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase text-navy-blue underline hover:text-black">
                            Buka Ruang Belajar &rarr;
                        </a>
                    </div>

                    <!-- Card 3: GTK 2026 -->
                    <div class="bg-white brutal-border brutal-shadow p-6 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-black uppercase bg-amber-500 text-black px-2 py-1 brutal-border inline-block mb-3">
                                PRAKTIK BAIK
                            </div>
                            <h3 class="text-2xl font-black uppercase text-black mb-2">Apresiasi GTK 2026</h3>
                            <p class="text-sm text-gray-700 font-medium leading-relaxed mb-4">
                                Karya inovasi dan pengabdian guru Informatika dalam transformasi digital sekolah berjudul <em>"Dari Kebutuhan Menjadi Pengabdian"</em>.
                            </p>
                        </div>
                        <a href="{{ route('gtk2026') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase text-navy-blue underline hover:text-black">
                            Buka Presentasi Slide &rarr;
                        </a>
                    </div>

                </div>

                <!-- Portfolio Projects Grid -->
                @if(isset($portfolios) && $portfolios->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($portfolios as $item)
                            <article class="bg-white brutal-border brutal-shadow hover:-translate-y-1 hover:shadow-[6px_6px_0px_#000] transition flex flex-col overflow-hidden">
                                @if($item->image)
                                    <div class="h-48 border-b-[3px] border-black bg-gray-100 overflow-hidden relative">
                                        <x-storage-image :path="$item->image" loading="lazy" alt="{{ $item->title }}" class="w-full h-full object-cover" />
                                    </div>
                                @endif
                                <div class="p-6 flex-grow flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between text-xs font-bold text-gray-500 mb-2">
                                            <span class="bg-[#f4f4f0] px-2 py-0.5 brutal-border text-black uppercase">
                                                {{ $item->category ?? 'PORTFOLIO' }}
                                            </span>
                                            @if($item->date)
                                                <span>{{ \Carbon\Carbon::parse($item->date)->format('M Y') }}</span>
                                            @endif
                                        </div>
                                        <h3 class="text-xl font-bold uppercase text-black mb-2">
                                            <a href="{{ route('portfolio.show', $item->slug) }}" class="hover:text-navy-blue transition">
                                                {{ $item->title }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-600 font-medium line-clamp-3 mb-4 text-justify">
                                            {{ Str::limit(strip_tags($item->description), 110) }}
                                        </p>
                                    </div>
                                    <a href="{{ route('portfolio.show', $item->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase text-navy-blue underline hover:text-black mt-auto">
                                        Detail Karya &rarr;
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

            </div>
        </section>

        <!-- Discussion & Contact Section -->
        <x-sales-contact source="profile" />

    </main>

    <!-- Footer -->
    <footer class="bg-black text-white py-12 border-t-[4px] border-navy-blue">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-stroke-white mb-2">
                {{ $profile->name ?? 'Nizar Suryadi, S.T.' }}
            </h2>
            <p class="text-xs uppercase font-bold tracking-widest text-gray-400 mb-6">
                Guru Informatika SMAN 4 Tasikmalaya · Media Pembelajaran &amp; Inovasi Digital
            </p>

            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 mb-8 text-xs font-bold uppercase tracking-wider">
                <a href="{{ route('site.home') }}" class="text-gray-300 hover:text-white underline">Beranda</a>
                <a href="{{ route('pembelajaran.index') }}" class="text-gray-300 hover:text-white underline">Pembelajaran Python</a>
                <a href="{{ route('gtk2026') }}" class="text-gray-300 hover:text-white underline">Apresiasi GTK 2026</a>
                <a href="{{ route('profil') }}" class="text-white font-black underline">Profil &amp; Portofolio</a>
                <a href="{{ route('site.home') }}#blog" class="text-gray-300 hover:text-white underline">Artikel</a>
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

</body>
</html>
