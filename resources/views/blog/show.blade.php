<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <x-marketing-head 
        :title="$post->title . ' | Zarnizar'" 
        :description="Str::limit(preg_replace('/\s+/', ' ', strip_tags($post->content)), 160)" 
        :canonical="url()->current()" 
        :settings="$settings" 
        :image="$post->image ? url(Storage::url($post->image)) : null" 
    />
    
    @if(!empty($settings['favicon']))
        <link rel="icon" href="{{ Storage::url($settings['favicon']) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Fonts: Space Grotesk & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap 5 CSS Grid / Utilities -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles / Scoped Styles matching Zarnizar Learning -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/pembelajaran.css'])
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

        /* Reading Typography Optimized for Mobile & Desktop */
        .zl-article-content {
            font-size: 1.0625rem;
            line-height: 1.85;
            color: #1e293b;
            word-break: break-word;
            text-align: justify;
            text-justify: inter-word;
        }

        @media (min-width: 768px) {
            .zl-article-content {
                font-size: 1.125rem;
                line-height: 1.9;
            }
        }

        .zl-article-content::after {
            content: "";
            display: table;
            clear: both;
        }

        .zl-article-content p,
        .zl-article-content div:not(.image):not(.raw-html-embed),
        .zl-article-content li {
            margin-bottom: 1.5rem;
            color: #1e293b;
            text-align: justify;
            text-justify: inter-word;
        }

        .zl-article-content strong,
        .zl-article-content b {
            font-weight: 700;
            color: #0f172a;
        }

        .zl-article-content a {
            color: #1e3a8a;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: color 0.15s ease;
        }

        .zl-article-content a:hover {
            color: #3b82f6;
        }

        .zl-article-content h1,
        .zl-article-content h2,
        .zl-article-content h3,
        .zl-article-content h4,
        .zl-article-content h5,
        .zl-article-content h6 {
            text-align: left;
        }

        .zl-article-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #0f172a;
            line-height: 1.35;
        }

        @media (min-width: 768px) {
            .zl-article-content h2 {
                font-size: 1.75rem;
            }
        }

        .zl-article-content h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e3a8a;
            margin-top: 2.25rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        @media (min-width: 768px) {
            .zl-article-content h3 {
                font-size: 1.4rem;
            }
        }

        .zl-article-content h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 1.75rem;
            margin-bottom: 0.75rem;
        }

        .zl-article-content ul {
            list-style-type: square;
            padding-left: 1.75rem;
            margin-bottom: 1.75rem;
        }

        .zl-article-content ol {
            list-style-type: decimal;
            padding-left: 1.75rem;
            margin-bottom: 1.75rem;
        }

        .zl-article-content li {
            margin-bottom: 0.65rem;
            line-height: 1.75;
        }

        .zl-article-content blockquote {
            text-align: justify;
            text-justify: inter-word;
            border-left: 5px solid #1e3a8a;
            background-color: #f8fafc;
            border-top: 2px solid #0f172a;
            border-right: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            border-radius: 8px;
            box-shadow: 3px 3px 0px 0px rgba(15, 23, 42, 1);
            padding: 1.25rem 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            font-weight: 500;
            color: #334155;
        }

        .zl-article-content hr {
            border: 0;
            border-top: 2px dashed #94a3b8;
            margin: 2.5rem 0;
        }

        .zl-article-content pre {
            background-color: #0f172a;
            color: #f8fafc;
            padding: 1.25rem;
            border: 2px solid #0f172a;
            border-radius: 8px;
            box-shadow: 3px 3px 0px 0px rgba(15, 23, 42, 1);
            margin: 2rem 0;
            overflow-x: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .zl-article-content code {
            font-family: 'JetBrains Mono', monospace;
            background-color: #e2e8f0;
            color: #0f172a;
            padding: 0.2rem 0.4rem;
            font-size: 0.875em;
            border-radius: 4px;
        }

        .zl-article-content pre code {
            background-color: transparent;
            padding: 0;
            border: none;
            color: inherit;
            font-size: inherit;
        }

        /* Responsive Article Images (No float crush on mobile) */
        .zl-article-content figure.image {
            margin: 2rem auto;
            max-width: 100%;
            display: table;
            text-align: center;
        }

        .zl-article-content figure.image img,
        .zl-article-content img {
            width: 100%;
            height: auto;
            border: 2px solid #0f172a;
            border-radius: 8px;
            box-shadow: 4px 4px 0px 0px rgba(15, 23, 42, 1);
            display: block;
            margin: 0 auto;
        }

        .zl-article-content figure.image figcaption {
            margin-top: 0.75rem;
            font-size: 0.85rem;
            color: #64748b;
            font-style: italic;
            text-align: center;
        }

        @media (min-width: 768px) {
            .zl-article-content figure.image.image-style-side,
            .zl-article-content figure.image.image-style-align-right {
                float: right;
                margin: 0.5rem 0 1.5rem 1.75rem;
                max-width: 46%;
            }

            .zl-article-content figure.image.image-style-align-left {
                float: left;
                margin: 0.5rem 1.75rem 1.5rem 0;
                max-width: 46%;
            }
        }

        @media (max-width: 767px) {
            .zl-article-content figure.image.image-style-side,
            .zl-article-content figure.image.image-style-align-right,
            .zl-article-content figure.image.image-style-align-left {
                float: none !important;
                margin: 1.5rem auto !important;
                max-width: 100% !important;
            }
        }

        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="zl-app">

    <!-- Main Navigation Bar (General Site Header) -->
    <header class="zl-navbar zl-no-print">
        <div class="container py-3" style="max-width: 960px;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <!-- Brand -->
                <a href="{{ route('site.home') }}" class="d-flex align-items-center text-decoration-none text-dark">
                    <span class="fw-bold fs-4 text-dark" style="letter-spacing: -0.02em;">
                        Z<span class="text-secondary">/</span> ZARNIZAR<span class="text-primary">.</span>
                    </span>
                </a>

                <!-- Nav Links -->
                <nav class="d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('site.home') }}" class="btn btn-sm btn-outline-dark border-2">
                        <i class="bi bi-house-door"></i> Beranda
                    </a>
                    <a href="{{ route('pembelajaran.index') }}" class="btn btn-sm btn-outline-dark border-2">
                        <i class="bi bi-mortarboard"></i> Pembelajaran
                    </a>
                    <a href="{{ route('site.home') }}#portfolio" class="btn btn-sm btn-outline-dark border-2 d-none d-md-inline-flex">
                        <i class="bi bi-grid-fill"></i> Karya Inovasi
                    </a>
                    <a href="{{ route('site.home') }}#blog" class="btn btn-sm btn-primary text-white border-2 border-dark shadow-sm fw-bold">
                        <i class="bi bi-journal-text"></i> Artikel
                    </a>
                    <a href="{{ route('profil') }}" class="btn btn-sm btn-outline-dark border-2 d-none d-sm-inline-flex">
                        <i class="bi bi-person"></i> Profil
                    </a>
                    <a href="{{ route('pembelajaran.index') }}" class="btn btn-sm btn-warning border-2 border-dark text-dark fw-bold shadow-sm">
                        Mulai Belajar <i class="bi bi-arrow-up-right"></i>
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Page Content Container -->
    <main class="flex-grow-1 py-4 py-lg-5">
        <div class="container" style="max-width: 960px;">

            <!-- Breadcrumb & Top Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-decoration-none text-secondary">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('site.home') }}#blog" class="text-decoration-none text-secondary">Artikel</a></li>
                        @if($post->category)
                            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ $post->category->name }}</li>
                        @endif
                    </ol>
                </nav>

                <div class="d-flex align-items-center gap-2">
                    @if($prevPost)
                        <a href="{{ route('blog.show', $prevPost->slug) }}" class="btn btn-sm btn-outline-dark border-2" title="{{ $prevPost->title }}">
                            <i class="bi bi-chevron-left"></i> <span class="d-none d-sm-inline">Sebelumnya</span>
                        </a>
                    @endif

                    <a href="{{ route('site.home') }}#blog" class="btn btn-sm btn-outline-dark border-2">
                        <i class="bi bi-arrow-left"></i> Semua Artikel
                    </a>

                    @if($nextPost)
                        <a href="{{ route('blog.show', $nextPost->slug) }}" class="btn btn-sm btn-primary border-2 border-dark text-white shadow-sm" title="{{ $nextPost->title }}">
                            <span class="d-none d-sm-inline">Selanjutnya</span> <i class="bi bi-chevron-right"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Article Header Card -->
            <article class="zl-card p-4 p-md-5 mb-4 bg-white">
                
                <!-- Category, Date, Read Time Badges -->
                <div class="d-flex flex-wrap items-center gap-2 mb-3">
                    @if($post->category)
                        <span class="zl-badge zl-badge-primary">
                            <i class="bi bi-tag-fill me-1"></i> {{ $post->category->name }}
                        </span>
                    @endif
                    <span class="zl-badge zl-badge-dark">
                        <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($post->published_at ?? $post->created_at)->translatedFormat('d F Y') }}
                    </span>
                    @php
                        $wordCount = str_word_count(strip_tags($post->content));
                        $readingTime = max(1, (int) ceil($wordCount / 200));
                    @endphp
                    <span class="zl-badge zl-badge-warning">
                        <i class="bi bi-clock me-1"></i> ~{{ $readingTime }} mnt baca
                    </span>
                    <span class="zl-badge bg-light text-dark border border-dark">
                        <i class="bi bi-eye me-1"></i> {{ number_format($post->views) }} views
                    </span>
                    <span class="zl-badge bg-light text-dark border border-dark">
                        <i class="bi bi-hand-thumbs-up me-1 text-success"></i> <span id="header-likes-count">{{ number_format($post->likes) }}</span> suka
                    </span>
                </div>

                <!-- Main Title -->
                <h1 class="h2 fw-bold text-dark mb-3 lh-sm" style="letter-spacing: -0.02em;">
                    {{ $post->title }}
                </h1>

                <!-- Author & Share Action Bar -->
                <div class="p-3 bg-light rounded-3 border border-2 border-secondary-subtle d-flex align-items-center justify-content-between flex-wrap gap-3 mt-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px; font-size: 1rem; border: 2px solid #0f172a;">
                            NS
                        </div>
                        <div>
                            <div class="fw-bold text-dark lh-1 mb-1">{{ $profile->name ?? 'Nizar Suryadi, S.T.' }}</div>
                            <div class="text-secondary small">Pendidik &bull; Praktisi Teknologi Digital</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @php
                            $shareUrl = url()->current();
                            $shareText = rawurlencode($post->title . ' — ' . $shareUrl);
                        @endphp
                        <a href="https://api.whatsapp.com/send?text={{ $shareText }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success border-2 border-dark shadow-sm">
                            <i class="bi bi-whatsapp"></i> Bagikan
                        </a>
                        <button type="button" onclick="copyCurrentUrl()" class="btn btn-sm btn-outline-dark border-2">
                            <i class="bi bi-link-45deg"></i> <span id="copy-btn-text">Salin Link</span>
                        </button>
                    </div>
                </div>

            </article>

            <!-- Featured Image (if available) -->
            @if($post->image)
                <div class="zl-card p-2 bg-white mb-4 text-center overflow-hidden">
                    <x-storage-image :path="$post->image" alt="{{ $post->title }}" class="img-fluid rounded-3 w-100" style="max-height: 520px; object-fit: cover;" />
                </div>
            @endif

            <!-- Article Body Content Card -->
            <div class="zl-card p-4 p-md-5 bg-white mb-4">
                <div class="zl-article-content">
                    {!! $post->content_with_auto_links !!}
                </div>

                <!-- Tags / Topics -->
                @if($post->tags && count($post->tags) > 0)
                    <div class="pt-4 mt-5 border-top border-2 border-secondary-subtle d-flex flex-wrap align-items-center gap-2">
                        <span class="small fw-bold text-dark"><i class="bi bi-tags-fill me-1"></i> Topik Terkait:</span>
                        @foreach($post->tags as $tag)
                            <span class="zl-badge bg-light text-dark">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Like & Dislike Reaction Card -->
            <div class="zl-card p-4 bg-white mb-4 border border-2 border-secondary-subtle">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="small text-uppercase fw-bold text-primary mb-1">
                            <i class="bi bi-chat-heart me-1"></i> Tanggapan Pembaca
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-1">Apakah artikel ini bermanfaat?</h3>
                        <p class="text-secondary small mb-0">Beri apresiasi atau tanggapan Anda untuk membantu penulis menyajikan catatan yang lebih baik.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 shrink-0" id="reaction-container">
                        @php
                            $userReaction = session()->get('post_reaction_'.$post->id);
                        @endphp
                        <form method="POST" action="{{ route('blog.react', $post->slug) }}" class="d-inline reaction-form" data-type="like">
                            @csrf
                            <input type="hidden" name="type" value="like">
                            <button type="submit" 
                                id="btn-like" 
                                class="btn {{ $userReaction === 'like' ? 'btn-success text-white shadow-sm' : 'btn-outline-success' }} border-2 d-flex align-items-center gap-2 fw-semibold px-3 py-2"
                                title="Bermanfaat / Suka artikel ini">
                                <i class="bi {{ $userReaction === 'like' ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }} fs-5"></i>
                                <span>Bermanfaat</span>
                                <span class="badge {{ $userReaction === 'like' ? 'bg-white text-success' : 'bg-success text-white' }} rounded-pill ms-1" id="like-count">
                                    {{ number_format($post->likes) }}
                                </span>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('blog.react', $post->slug) }}" class="d-inline reaction-form" data-type="dislike">
                            @csrf
                            <input type="hidden" name="type" value="dislike">
                            <button type="submit" 
                                id="btn-dislike" 
                                class="btn {{ $userReaction === 'dislike' ? 'btn-danger text-white shadow-sm' : 'btn-outline-secondary' }} border-2 d-flex align-items-center gap-2 fw-semibold px-3 py-2"
                                title="Kurang bermanfaat">
                                <i class="bi {{ $userReaction === 'dislike' ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down' }} fs-5"></i>
                                <span>Kurang</span>
                                <span class="badge {{ $userReaction === 'dislike' ? 'bg-white text-danger' : 'bg-secondary text-white' }} rounded-pill ms-1" id="dislike-count">
                                    {{ number_format($post->dislikes) }}
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
                <div id="reaction-feedback" class="small text-success fw-semibold mt-2" style="display: none;"></div>
            </div>

            <!-- Share & Feedback Card -->
            <div class="zl-card p-4 bg-light mb-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div>
                        <div class="small text-uppercase fw-bold text-muted mb-1">Berbagi Pengetahuan</div>
                        <h3 class="h5 fw-bold text-dark mb-0">Bermanfaat? Bagikan Catatan Belajar Ini</h3>
                        <p class="text-secondary small mb-0 mt-1">Bantu rekan guru, siswa, atau pegiat pendidikan menemukan referensi ini.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2 shrink-0">
                        <a href="https://api.whatsapp.com/send?text={{ $shareText }}" target="_blank" rel="noopener noreferrer" class="zl-btn zl-btn-success text-white">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                        <button type="button" onclick="copyCurrentUrl()" class="zl-btn zl-btn-outline">
                            <i class="bi bi-link-45deg"></i> Salin Tautan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Author Profile Card -->
            <div class="zl-card p-4 bg-white mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 64px; height: 64px; font-size: 1.4rem; border: 2px solid #0f172a;">
                            NS
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-uppercase small fw-bold text-primary mb-1">Guru Pengampu &bull; Penulis</div>
                        <h2 class="h5 fw-bold text-dark mb-1">{{ $profile->name ?? 'Nizar Suryadi, S.T.' }}</h2>
                        <p class="text-secondary small mb-2">
                            Guru Informatika di <strong>SMA Negeri 4 Tasikmalaya</strong>. Pengembang platform <em>Zarnizar Learning</em>, inisiator <em>SASTI</em>, dan peserta <strong>Apresiasi GTK 2026 Kategori Dedikatif</strong>.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('profil') }}" class="btn btn-sm btn-outline-dark border-2">
                                <i class="bi bi-person"></i> Profil Lengkap
                            </a>
                            <a href="{{ route('pembelajaran.index') }}" class="btn btn-sm btn-primary border-2 border-dark text-white shadow-sm">
                                <i class="bi bi-mortarboard"></i> Ruang Belajar Python
                            </a>
                            <a href="{{ route('gtk2026') }}" class="btn btn-sm btn-outline-dark border-2">
                                <i class="bi bi-award"></i> Karya GTK 2026
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Previous & Next Article Navigation Cards -->
            <div class="row g-3 mb-5">
                <div class="col-md-6">
                    @if($prevPost)
                        <a href="{{ route('blog.show', $prevPost->slug) }}" class="zl-card zl-card-hover p-3 p-md-4 bg-white h-100 text-decoration-none text-dark d-block">
                            <div class="small text-secondary fw-semibold mb-1">
                                <i class="bi bi-arrow-left"></i> Artikel Sebelumnya
                            </div>
                            <div class="fw-bold text-dark line-clamp-2">
                                {{ $prevPost->title }}
                            </div>
                            <div class="small text-muted mt-2">
                                <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($prevPost->published_at ?? $prevPost->created_at)->translatedFormat('d M Y') }}
                            </div>
                        </a>
                    @else
                        <div class="zl-card p-3 p-md-4 bg-light text-secondary h-100 opacity-75">
                            <div class="small fw-semibold mb-1"><i class="bi bi-arrow-left"></i> Artikel Sebelumnya</div>
                            <div class="small">Ini adalah artikel terawal dalam arsip.</div>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    @if($nextPost)
                        <a href="{{ route('blog.show', $nextPost->slug) }}" class="zl-card zl-card-hover p-3 p-md-4 bg-white h-100 text-decoration-none text-dark d-block text-md-end">
                            <div class="small text-secondary fw-semibold mb-1">
                                Artikel Berikutnya <i class="bi bi-arrow-right"></i>
                            </div>
                            <div class="fw-bold text-dark line-clamp-2">
                                {{ $nextPost->title }}
                            </div>
                            <div class="small text-muted mt-2">
                                <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($nextPost->published_at ?? $nextPost->created_at)->translatedFormat('d M Y') }}
                            </div>
                        </a>
                    @else
                        <div class="zl-card p-3 p-md-4 bg-light text-secondary h-100 text-md-end opacity-75">
                            <div class="small fw-semibold mb-1">Artikel Berikutnya <i class="bi bi-arrow-right"></i></div>
                            <div class="small">Ini adalah artikel terbaru saat ini.</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Articles Recommendations -->
            @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="zl-badge zl-badge-primary">Rekomendasi</span>
                            <h2 class="h4 fw-bold text-dark mb-0">Artikel &amp; Catatan Terkait</h2>
                        </div>
                        <a href="{{ route('site.home') }}#blog" class="small fw-bold text-dark text-decoration-none">
                            Lihat Semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="row g-3">
                        @foreach($relatedPosts as $rel)
                            <div class="col-md-4">
                                <a href="{{ route('blog.show', $rel->slug) }}" class="zl-card zl-card-hover p-4 bg-white h-100 text-decoration-none text-dark d-flex flex-column">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        @if($rel->category)
                                            <span class="zl-badge zl-badge-primary" style="font-size: 0.65rem;">
                                                {{ $rel->category->name }}
                                            </span>
                                        @endif
                                        <span class="text-muted small" style="font-size: 0.75rem;">
                                            {{ \Carbon\Carbon::parse($rel->published_at ?? $rel->created_at)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                    <h3 class="h6 fw-bold text-dark mb-2 line-clamp-2">
                                        {{ $rel->title }}
                                    </h3>
                                    <p class="text-secondary small mb-3 flex-grow-1 line-clamp-2">
                                        {{ Str::limit(strip_tags($rel->content), 90) }}
                                    </p>
                                    <div class="small text-primary fw-bold mt-auto">
                                        Baca Catatan &rarr;
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top border-2 border-dark py-4 mt-5 zl-no-print">
        <div class="container" style="max-width: 960px;">
            <div class="row g-4 align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="fw-bold fs-5 text-dark">Z<span class="text-secondary">/</span> ZARNIZAR<span class="text-primary">.</span></span>
                    </div>
                    <p class="text-muted small mb-1">
                        Publikasi Artikel, Dokumentasi Karya &amp; Ruang Belajar Digital.
                    </p>
                    <p class="text-muted small mb-0">
                        Oleh: <strong>{{ $profile->name ?? 'Nizar Suryadi, S.T.' }}</strong>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end flex-wrap gap-2 mb-2">
                        <a href="{{ route('site.home') }}" class="badge text-bg-light border text-decoration-none">Beranda</a>
                        <a href="{{ route('pembelajaran.index') }}" class="badge text-bg-light border text-decoration-none">Pembelajaran</a>
                        <a href="{{ route('site.home') }}#portfolio" class="badge text-bg-light border text-decoration-none">Karya Inovasi</a>
                        <a href="{{ route('site.home') }}#blog" class="badge text-bg-primary text-decoration-none">Artikel</a>
                        <a href="{{ route('profil') }}" class="badge text-bg-light border text-decoration-none">Profil</a>
                    </div>
                    <div class="small text-secondary">
                        &copy; {{ date('Y') }} zarnizar.com. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast Notification for Copied Link -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        <div id="copyToast" class="toast align-items-center text-bg-dark border-2 border-dark shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <span>Tautan artikel berhasil disalin ke clipboard!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function copyCurrentUrl() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                var toastEl = document.getElementById('copyToast');
                if (toastEl) {
                    var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                    toast.show();
                }
                var btnText = document.getElementById('copy-btn-text');
                if (btnText) {
                    var orig = btnText.innerText;
                    btnText.innerText = 'Tersalin!';
                    setTimeout(function() {
                        btnText.innerText = orig;
                    }, 2000);
                }
            }).catch(function() {
                alert('Tautan disalin: ' + window.location.href);
            });
        }

        // Reaction (Like / Dislike) handler
        document.querySelectorAll('.reaction-form').forEach(function(form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                var type = this.getAttribute('data-type');
                var url = this.getAttribute('action');
                var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    || this.querySelector('input[name="_token"]')?.value;

                var btnLike = document.getElementById('btn-like');
                var btnDislike = document.getElementById('btn-dislike');
                if (btnLike) btnLike.disabled = true;
                if (btnDislike) btnDislike.disabled = true;

                try {
                    var response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({ type: type })
                    });

                    if (!response.ok) {
                        throw new Error('Response error');
                    }

                    var data = await response.json();
                    if (data.success) {
                        var likeCount = document.getElementById('like-count');
                        var dislikeCount = document.getElementById('dislike-count');
                        var headerLike = document.getElementById('header-likes-count');

                        if (likeCount) likeCount.textContent = Number(data.likes).toLocaleString('id-ID');
                        if (dislikeCount) dislikeCount.textContent = Number(data.dislikes).toLocaleString('id-ID');
                        if (headerLike) headerLike.textContent = Number(data.likes).toLocaleString('id-ID');

                        var iconLike = btnLike ? btnLike.querySelector('i') : null;
                        var iconDislike = btnDislike ? btnDislike.querySelector('i') : null;

                        if (data.userReaction === 'like') {
                            if (btnLike) btnLike.className = 'btn btn-success text-white shadow-sm border-2 d-flex align-items-center gap-2 fw-semibold px-3 py-2';
                            if (iconLike) iconLike.className = 'bi bi-hand-thumbs-up-fill fs-5';
                            if (likeCount) likeCount.className = 'badge bg-white text-success rounded-pill ms-1';

                            if (btnDislike) btnDislike.className = 'btn btn-outline-secondary border-2 d-flex align-items-center gap-2 fw-semibold px-3 py-2';
                            if (iconDislike) iconDislike.className = 'bi bi-hand-thumbs-down fs-5';
                            if (dislikeCount) dislikeCount.className = 'badge bg-secondary text-white rounded-pill ms-1';
                        } else if (data.userReaction === 'dislike') {
                            if (btnDislike) btnDislike.className = 'btn btn-danger text-white shadow-sm border-2 d-flex align-items-center gap-2 fw-semibold px-3 py-2';
                            if (iconDislike) iconDislike.className = 'bi bi-hand-thumbs-down-fill fs-5';
                            if (dislikeCount) dislikeCount.className = 'badge bg-white text-danger rounded-pill ms-1';

                            if (btnLike) btnLike.className = 'btn btn-outline-success border-2 d-flex align-items-center gap-2 fw-semibold px-3 py-2';
                            if (iconLike) iconLike.className = 'bi bi-hand-thumbs-up fs-5';
                            if (likeCount) likeCount.className = 'badge bg-success text-white rounded-pill ms-1';
                        } else {
                            if (btnLike) btnLike.className = 'btn btn-outline-success border-2 d-flex align-items-center gap-2 fw-semibold px-3 py-2';
                            if (iconLike) iconLike.className = 'bi bi-hand-thumbs-up fs-5';
                            if (likeCount) likeCount.className = 'badge bg-success text-white rounded-pill ms-1';

                            if (btnDislike) btnDislike.className = 'btn btn-outline-secondary border-2 d-flex align-items-center gap-2 fw-semibold px-3 py-2';
                            if (iconDislike) iconDislike.className = 'bi bi-hand-thumbs-down fs-5';
                            if (dislikeCount) dislikeCount.className = 'badge bg-secondary text-white rounded-pill ms-1';
                        }

                        var feedback = document.getElementById('reaction-feedback');
                        if (feedback) {
                            feedback.style.display = 'block';
                            feedback.textContent = data.userReaction === 'like'
                                ? 'Terima kasih atas apresiasi Anda!'
                                : (data.userReaction === 'dislike' ? 'Terima kasih atas masukannya.' : 'Pilihan Anda telah dibatalkan.');
                            setTimeout(function() { feedback.style.display = 'none'; }, 3000);
                        }
                    }
                } catch (err) {
                    form.submit();
                } finally {
                    if (btnLike) btnLike.disabled = false;
                    if (btnDislike) btnDislike.disabled = false;
                }
            });
        });
    </script>
</body>
</html>
