<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#091a30">
    <meta name="description" content="{{ $gtk['description'] }}">
    @if($private)<meta name="robots" content="noindex, nofollow">@endif
    <title>{{ $gtk['title'] }} | Nizar Suryadi</title>
    <link rel="canonical" href="{{ route('gtk2026') }}">
    @vite(['resources/css/gtk2026.css', 'resources/js/gtk2026.js'])
</head>
<body class="gtk-page" data-gtk data-worker="{{ $private || Vite::isRunningHot() ? '' : route('gtk2026.worker') }}" data-scope="{{ route('gtk2026', [], false) }}">
    <a class="gtk-skip" href="#slides">Langsung ke presentasi</a>
    <header class="gtk-header" aria-label="Identitas presentasi">
        <a class="gtk-brand" href="{{ route('site.home') }}">ZARNIZAR<span>.</span></a>
        <div class="gtk-event">
            @if($gtk['assets']['school_logo'])
                <img class="gtk-school-logo" src="{{ $gtk['assets']['school_logo'] }}" alt="Logo SMA Negeri 4 Tasikmalaya" width="44" height="44" decoding="async">
            @else
                <span class="gtk-dot"></span>
            @endif
            {{ $gtk['event'] }}<span class="gtk-event-category"> / {{ $gtk['category'] }}</span>
        </div>
        <button class="gtk-button gtk-button-small gtk-js-only" type="button" data-evidence-open><x-gtk-icon name="folder" /> Bukti <kbd>B</kbd></button>
    </header>
    <main id="slides" class="gtk-deck" aria-label="Presentasi delapan slide">
        @foreach($gtk['slides'] as $slide)
            @php($documentation = $slideEvidence[$slide['id']] ?? [])
            <section id="slide-{{ $loop->iteration }}" class="gtk-slide gtk-slide-{{ $slide['id'] }} {{ in_array($slide['id'], ['need', 'journey', 'impact']) ? 'gtk-light' : '' }} {{ $documentation ? 'gtk-has-documentation' : '' }}" data-slide data-label="{{ $slide['label'] }}" aria-labelledby="title-{{ $loop->iteration }}" tabindex="-1">
                <div class="gtk-slide-inner">
                    <p class="gtk-eyebrow"><span></span>{{ $slide['eyebrow'] }}</p>
                    @if($slide['id'] === 'opening')
                        <div class="gtk-opening-grid">
                            <div class="gtk-opening-copy">
                                <h1 id="title-{{ $loop->iteration }}">{{ Str::before($slide['title'], 'Menjadi') }}<br><span>Menjadi<br>{{ Str::after($slide['title'], 'Menjadi ') }}.</span></h1>
                                <p class="gtk-subtitle">{{ $gtk['subtitle'] }}</p>
                                <div class="gtk-presenter">
                                    @if($gtk['assets']['profile'])<img src="{{ $gtk['assets']['profile'] }}" alt="Nizar Suryadi" width="52" height="52" fetchpriority="high">@else<span class="gtk-avatar" aria-hidden="true">NS</span>@endif
                                    <div><strong>{{ $gtk['presenter']['name'] }}</strong><span>{{ $gtk['presenter']['role'] }} · {{ $gtk['presenter']['school'] }}</span></div>
                                </div>
                                <div class="gtk-opening-actions gtk-js-only">
                                    <button class="gtk-button gtk-primary" type="button" data-start>Mulai Presentasi <x-gtk-icon name="arrow-right" /></button>
                                    <button class="gtk-text-button" type="button" data-fullscreen><x-gtk-icon name="expand" /><span>Masuk Fullscreen</span></button>
                                </div>
                            </div>
                            @if($gtk['assets']['profile'])
                                <div class="gtk-portrait">
                                    <span class="gtk-portrait-year">2022 — 2026</span>
                                    <img src="{{ $gtk['assets']['profile'] }}" alt="Potret Nizar Suryadi, Guru Informatika SMA Negeri 4 Tasikmalaya" width="512" height="768" fetchpriority="high" decoding="async">
                                    <div class="gtk-portrait-caption"><strong>SASTI<span>•</span></strong><span>Berawal dari kebutuhan.<br>Bertumbuh untuk pendidikan.</span></div>
                                </div>
                            @else
                            <div class="gtk-orbit" aria-label="SASTI: menghubungkan siswa, guru, dan orang tua">
                                <div class="gtk-orbit-ring gtk-ring-one"></div><div class="gtk-orbit-ring gtk-ring-two"></div>
                                <div class="gtk-orbit-center">@if($gtk['assets']['logo'])<img src="{{ $gtk['assets']['logo'] }}" alt="Logo SASTI" width="170" height="80">@else<strong>SASTI<span>•</span></strong>@endif<span>Berawal dari kebutuhan.<br>Bertumbuh untuk pendidikan.</span></div>
                                <div class="gtk-orbit-node gtk-node-top"><x-gtk-icon name="users" /> Siswa</div>
                                <div class="gtk-orbit-node gtk-node-right"><x-gtk-icon name="book" /> Guru</div>
                                <div class="gtk-orbit-node gtk-node-bottom"><x-gtk-icon name="heart" /> Orang tua</div>
                                <div class="gtk-orbit-caption"><span class="gtk-dot"></span> SATU EKOSISTEM. BANYAK MANFAAT.</div>
                                <span class="gtk-orbit-year">2022 — 2026</span>
                            </div>
                            @endif
                        </div>
                    @else
                        <h2 id="title-{{ $loop->iteration }}">{{ $slide['title'] }}</h2>
                        @switch($slide['id'])
                            @case('need')
                                <p class="gtk-intro">{{ $slide['text'] }}</p>
                                <div class="gtk-flow-caption">JULI 2022 <span>ALUR PENCATATAN MANUAL</span></div>
                                <ol class="gtk-manual-flow">@foreach($gtk['manual_flow'] as $step)<li><span class="gtk-step-number">0{{ $loop->iteration }}</span><strong>{{ $step }}</strong>@unless($loop->last)<x-gtk-icon name="arrow-right" />@endunless</li>@endforeach</ol>
                                <blockquote class="gtk-quote">“{{ $slide['quote'] }}”</blockquote>
                                @break
                            @case('action')
                                <div class="gtk-action-grid"><div><p class="gtk-intro">{{ $slide['text'] }}</p><blockquote class="gtk-quote">“{{ $slide['quote'] }}”</blockquote>
                                    @if($documentation)<ul class="gtk-dedication-compact">@foreach($gtk['dedication'] as $item)<li><x-gtk-icon name="check" />{{ $item }}</li>@endforeach</ul>@endif
                                </div>
                                @if($documentation)
                                    @include('gtk2026.slide-gallery')
                                @else
                                <div class="gtk-commitment">
                                    @if($gtk['assets']['process'])<img class="gtk-process-photo" src="{{ $gtk['assets']['process'] }}" alt="Proses pengembangan SASTI oleh Nizar Suryadi">@endif
                                    <span class="gtk-card-label">DARI KEMAMPUAN, UNTUK KEBERMANFAATAN</span><x-gtk-icon name="code" class="gtk-code-icon" /><ul>@foreach($gtk['dedication'] as $item)<li><x-gtk-icon name="check" />{{ $item }}</li>@endforeach</ul><span class="gtk-card-foot">Satu langkah kecil. Komitmen yang panjang.</span></div>
                                @endif</div>
                                @break
                            @case('journey')
                                <div class="gtk-journey-content">
                                <ol class="gtk-timeline">@foreach($gtk['timeline'] as $point)<li><span class="gtk-timeline-year">{{ $point['year'] }}</span><span class="gtk-timeline-point"></span><h3>{{ $point['title'] }}</h3><p>{{ $point['text'] }}</p></li>@endforeach</ol>
                                @if($documentation)@include('gtk2026.slide-gallery')@endif
                                </div>
                                <blockquote class="gtk-quote gtk-quote-small">“{{ $slide['quote'] }}”</blockquote>
                                @break
                            @case('ecosystem')
                                <aside class="gtk-website-integration" aria-label="Perkembangan terbaru SASTI">
                                    <span>{{ $gtk['website_integration']['date'] }}</span>
                                    <strong>{{ $gtk['website_integration']['text'] }}</strong>
                                    <a href="{{ $gtk['website_integration']['url'] }}" target="_blank" rel="noopener noreferrer">Buka website resmi ↗</a>
                                </aside>
                                @include('gtk2026.slide-media', ['videoEvidence' => 'sastiprofile', 'documentationView' => 'gtk2026.ecosystem-content'])
                                @break
                            @case('impact')
                                @include('gtk2026.slide-media', ['videoEvidence' => 'testimonials', 'documentationView' => 'gtk2026.impact-content'])
                                @break
                            @case('family')
                                <div class="gtk-family-grid">
                                    <div class="gtk-family-visual {{ $gtk['assets']['family'] ? 'gtk-family-collage' : '' }}">
                                        @if($gtk['assets']['family'])
                                            <figure class="gtk-family-photo gtk-family-photo-main"><img src="{{ $gtk['assets']['family'] }}" alt="Nizar bersama istri dan anak-anak"></figure>
                                            @if($gtk['assets']['family_secondary'])
                                                <figure class="gtk-family-photo"><img src="{{ $gtk['assets']['family_secondary'] }}" alt="Nizar bersama istri"></figure>
                                            @endif
                                            @if($gtk['assets']['family_extended'])
                                                <figure class="gtk-family-photo"><img src="{{ $gtk['assets']['family_extended'] }}" alt="Kebersamaan Nizar dan keluarga besar"></figure>
                                            @endif
                                        @else
                                            <x-gtk-icon name="heart" /><p>Ruang.<br>Pengertian.<br>Doa & <em>dukungan.</em></p><span>Foto keluarga belum ditambahkan</span>
                                        @endif
                                    </div>
                                    <div><p class="gtk-intro">{{ $slide['text'] }}</p><blockquote class="gtk-family-quote">“Sebentar, Ayah harus memperbaiki dulu.”</blockquote><p class="gtk-muted">Dan kata ‘sebentar’ itu terkadang tidak benar-benar sebentar.</p><p class="gtk-family-closing">{{ $slide['quote'] }}</p></div>
                                </div>
                                @break
                            @case('closing')
                                <div class="gtk-closing-grid"><div><blockquote class="gtk-closing-quote">“{{ $slide['quote'] }}”</blockquote><p class="gtk-intro">{{ $slide['text'] }}</p><p class="gtk-closing-highlight">{{ $slide['highlight'] }}</p><p class="gtk-final-word">“{{ $slide['closing'] }}”</p><div class="gtk-signature"><strong>{{ $gtk['presenter']['name'] }}</strong><span>{{ $gtk['presenter']['school'] }}</span></div>
                                <div class="gtk-closing-evidence-links" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px dashed var(--gtk-line, rgba(255,255,255,0.2));">
                                    <span style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--gtk-gold, #f59e0b); margin-bottom: 8px;">
                                        Dokumentasi &amp; Bukti Nyata:
                                    </span>
                                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px; font-size: 13px;">
                                        <li>
                                            <a href="https://zarnizar.com/gtk2026#slide-1" target="_blank" rel="noopener noreferrer" style="color: #93c5fd; text-decoration: underline; text-underline-offset: 3px; display: inline-flex; align-items: center; gap: 6px;">
                                                <x-gtk-icon name="file" /> <strong>zarnizar.com/gtk2026#slide-1</strong> <span style="font-size: 11px; opacity: 0.85;">(Slide Presentasi)</span> ↗
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://sman4-tsm.sch.id/" target="_blank" rel="noopener noreferrer" style="color: #93c5fd; text-decoration: underline; text-underline-offset: 3px; display: inline-flex; align-items: center; gap: 6px;">
                                                <x-gtk-icon name="grid" /> <strong>sman4-tsm.sch.id</strong> <span style="font-size: 11px; opacity: 0.85;">(Website SMA Negeri 4 Tasikmalaya)</span> ↗
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://siaga.sman4-tsm.sch.id/" target="_blank" rel="noopener noreferrer" style="color: #93c5fd; text-decoration: underline; text-underline-offset: 3px; display: inline-flex; align-items: center; gap: 6px;">
                                                <x-gtk-icon name="code" /> <strong>siaga.sman4-tsm.sch.id</strong> <span style="font-size: 11px; opacity: 0.85;">(Sistem SIAGA &amp; SASTI Patas)</span> ↗
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                </div><div class="gtk-closing-visual">@if($gtk['assets']['school'])<img src="{{ $gtk['assets']['school'] }}" alt="Nizar Suryadi di lingkungan SMA Negeri 4 Tasikmalaya">@elseif($gtk['assets']['profile'])<img class="gtk-closing-portrait" src="{{ $gtk['assets']['profile'] }}" alt="Potret Nizar Suryadi" width="512" height="768"><span>UNTUK SEKOLAH. UNTUK PENDIDIKAN.</span>@else<span class="gtk-closing-mark">Terus<br><em>bermanfaat.</em></span><span>UNTUK SEKOLAH. UNTUK PENDIDIKAN.</span>@endif</div></div>
                                @break
                        @endswitch
                    @endif
                    <aside class="gtk-notes" data-notes hidden><strong>Catatan presenter · {{ $slide['seconds'] }} detik</strong><p>{{ $slide['note'] }}</p></aside>
                </div>
                <span class="gtk-slide-number" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
            </section>
        @endforeach
    </main>
    <footer class="gtk-controls gtk-js-only" aria-label="Kontrol presentasi">
        <div class="gtk-control-meta"><span class="gtk-control-label" data-slide-label>Pembuka</span><span class="gtk-offline" data-offline-status role="status"></span></div>
        <nav class="gtk-pagination" aria-label="Navigasi slide"><button type="button" class="gtk-icon-button" data-previous aria-label="Slide sebelumnya"><x-gtk-icon name="arrow-left" /></button><span class="gtk-counter"><strong data-slide-current>1</strong><span>/ {{ count($gtk['slides']) }}</span></span><button type="button" class="gtk-icon-button" data-next aria-label="Slide berikutnya"><x-gtk-icon name="arrow-right" /></button></nav>
        <div class="gtk-tools"><button class="gtk-text-button" type="button" data-timer title="Klik untuk mulai atau jeda; klik dua kali untuk reset">Mulai 6 Menit</button><button class="gtk-icon-button gtk-print-button" type="button" data-print aria-label="Cetak atau simpan PDF"><x-gtk-icon name="print" /></button><button class="gtk-icon-button" type="button" data-fullscreen aria-label="Masuk fullscreen"><x-gtk-icon name="expand" /></button></div>
    </footer>
    <div class="gtk-progress gtk-js-only" role="progressbar" aria-label="Kemajuan presentasi" aria-valuemin="1" aria-valuemax="8" aria-valuenow="1"><span data-progress></span></div>
    <p class="gtk-sr-only" data-announcement aria-live="polite"></p>
    <p class="gtk-toast" data-toast role="status" hidden></p>
    @include('gtk2026.evidence-panel')
    <noscript><div class="gtk-noscript">JavaScript tidak aktif. Seluruh slide dapat dibaca dengan menggulir halaman atau dicetak dari menu browser.</div></noscript>
</body>
</html>
