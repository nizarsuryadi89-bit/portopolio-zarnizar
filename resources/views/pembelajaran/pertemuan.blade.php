@extends('pembelajaran.layout')

@section('title', 'Pertemuan ' . $pertemuan['nomor'] . ': ' . $pertemuan['judul'] . ' — Zarnizar Learning')
@section('meta_description', $pertemuan['ringkasan'])

@section('content')
<div class="py-4 py-lg-5">
    <div class="container">

        <!-- Breadcrumb & Top Navigation -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('pembelajaran.index') }}" class="text-decoration-none">Beranda</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">Pertemuan {{ $pertemuan['nomor'] }}</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center gap-2">
                @if($prevId)
                    <a href="{{ route('pembelajaran.pertemuan', $prevId) }}" class="btn btn-sm btn-outline-dark border-2">
                        <i class="bi bi-chevron-left"></i> Pertemuan {{ $prevId }}
                    </a>
                @endif

                <button type="button" class="btn btn-sm zl-btn-outline zl-btn-complete-meeting" data-meeting-id="{{ $id }}">
                    <i class="bi bi-check2-circle"></i> Tandai Selesai
                </button>

                @if($nextId)
                    <a href="{{ route('pembelajaran.pertemuan', $nextId) }}" class="btn btn-sm btn-primary border-2 border-dark text-white shadow-sm">
                        Pertemuan {{ $nextId }} <i class="bi bi-chevron-right"></i>
                    </a>
                @else
                    <a href="{{ route('pembelajaran.asesmen') }}" class="btn btn-sm btn-warning border-2 border-dark text-dark fw-bold shadow-sm">
                        Asesmen Akhir <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- Meeting Header Card -->
        <div class="zl-card p-4 p-md-5 mb-5 bg-white">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="zl-badge zl-badge-dark">Pertemuan {{ $pertemuan['nomor'] }}</span>
                <span class="zl-badge zl-badge-warning"><i class="bi bi-stopwatch"></i> {{ $pertemuan['durasi'] }}</span>
                <span class="zl-badge zl-badge-primary">Informatika Kelas XII</span>
            </div>

            <h1 class="h2 fw-bold text-dark mb-3">
                {{ $pertemuan['judul'] }}
            </h1>

            <p class="lead text-secondary mb-4">
                {{ $pertemuan['ringkasan'] }}
            </p>

            <!-- Tujuan Pembelajaran -->
            @if(!empty($pertemuan['tujuan']))
            <div class="p-3 bg-light rounded-3 border border-2 border-secondary-subtle">
                <div class="fw-bold text-dark mb-2"><i class="bi bi-bullseye text-primary"></i> Target Kemampuan (Tujuan Pembelajaran):</div>
                <div class="row g-2">
                    @foreach($pertemuan['tujuan'] as $tujuan)
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2 small text-secondary">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <span>{{ $tujuan }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Section 1: Konsep & Materi Teori -->
        <div class="mb-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="zl-badge zl-badge-primary">Bagian 1</span>
                <h2 class="h4 fw-bold text-dark mb-0">Konsep Membaca Kode</h2>
            </div>

            <div class="row g-4">
                @foreach($pertemuan['konsep'] as $konsep)
                <div class="col-lg-6">
                    <div class="zl-card p-4 h-100 bg-white">
                        <h3 class="h5 fw-bold text-primary mb-2">{{ $konsep['judul'] }}</h3>
                        <p class="text-secondary small mb-3">{{ $konsep['deskripsi'] }}</p>

                        @if(!empty($konsep['poin']))
                        <div class="bg-light p-3 rounded-2 border border-1 border-secondary-subtle">
                            <ul class="mb-0 ps-3 small text-secondary">
                                @foreach($konsep['poin'] as $pt)
                                <li class="mb-1">{{ $pt }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: Code Viewer & Penjelasan Baris demi Baris -->
        @if(!empty($pertemuan['contoh_utama']))
        <div class="mb-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="zl-badge zl-badge-primary">Bagian 2</span>
                <h2 class="h4 fw-bold text-dark mb-0">Code Viewer & Telaah Baris demi Baris</h2>
            </div>

            <div class="row g-4">
                <!-- Left: Code Box -->
                <div class="col-lg-5">
                    <div class="zl-card p-3 bg-white h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="fw-bold text-dark mb-2">{{ $pertemuan['contoh_utama']['judul'] }}</div>
                            <div class="zl-code-block mb-3">
                                <div class="zl-code-header">
                                    <div class="zl-code-dots">
                                        <span class="zl-code-dot zl-dot-red"></span>
                                        <span class="zl-code-dot zl-dot-yellow"></span>
                                        <span class="zl-code-dot zl-dot-green"></span>
                                    </div>
                                    <span class="text-secondary small font-mono">program.py</span>
                                </div>
                                <div class="zl-code-content">
                                    @php
                                        $lines = explode("\n", $pertemuan['contoh_utama']['kode']);
                                    @endphp
                                    @foreach($lines as $i => $ln)
                                    <div class="zl-code-line" data-line="{{ $i + 1 }}">
                                        <span class="zl-line-number">{{ $i + 1 }}</span>
                                        <span class="zl-line-text">{{ $ln }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Expected Output -->
                        <div class="bg-light p-3 rounded-2 border border-2 border-dark">
                            <div class="small fw-bold text-muted mb-1"><i class="bi bi-terminal"></i> Output Hasil Program:</div>
                            <pre class="mb-0 text-success bg-dark p-2 rounded font-mono small"><code>{{ $pertemuan['contoh_utama']['output'] }}</code></pre>
                        </div>
                    </div>
                </div>

                <!-- Right: Line-by-Line Explanation Table -->
                <div class="col-lg-7">
                    <div class="zl-card p-4 bg-white h-100">
                        <h3 class="h6 fw-bold text-dark mb-3"><i class="bi bi-list-check text-primary"></i> Penjelasan Setiap Baris Program:</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 0.875rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 70px;" class="text-center">Baris</th>
                                        <th style="width: 170px;">Kode</th>
                                        <th>Penjelasan Komputasional</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pertemuan['contoh_utama']['penjelasan_baris'] as $pb)
                                    <tr>
                                        <td class="text-center font-mono fw-bold text-primary">{{ $pb['baris'] }}</td>
                                        <td class="font-mono small bg-light"><code>{{ $pb['kode'] ?: '(baris kosong)' }}</code></td>
                                        <td class="text-secondary">{{ $pb['keterangan'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section 3: Interactive Code Tracer Widget (CRITICAL FEATURE) -->
        @if(!empty($pertemuan['trace']))
        <div class="mb-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="zl-badge zl-badge-primary">Bagian 3</span>
                <h2 class="h4 fw-bold text-dark mb-0">Trace Code Interaktif (Langkah demi Langkah)</h2>
            </div>

            @php
                $traceSteps = array_map(function($st) {
                    return [
                        'step' => $st['langkah'],
                        'line' => $st['baris_aktif'],
                        'vars' => $st['variabel'] ?? [],
                        'changed_var' => $st['variabel_berubah'] ?? null,
                        'output' => $st['output'] ?? null,
                        'note' => $st['penjelasan'] ?? '',
                    ];
                }, $pertemuan['trace']['langkah'] ?? []);
                $traceJson = json_encode($traceSteps);
            @endphp

            <div class="zl-tracer-container zl-tracer-widget" data-trace-steps="{{ $traceJson }}">
                <div class="zl-tracer-header">
                    <div>
                        <span class="fw-bold text-dark me-2">{{ $pertemuan['trace']['judul'] }}</span>
                        <span class="badge bg-secondary zl-tracer-step-counter">Langkah 1</span>
                    </div>
                    <!-- Controls -->
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-dark zl-tracer-prev-btn" disabled>
                            <i class="bi bi-chevron-left"></i> Langkah Sebelumnya
                        </button>
                        <button type="button" class="btn btn-sm btn-primary zl-tracer-next-btn text-white fw-bold">
                            Langkah Berikutnya <i class="bi bi-chevron-right"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-light border zl-tracer-reset-btn" title="Kembali ke awal">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </div>

                <div class="zl-tracer-body">
                    <!-- Column 1: Code Viewer with Highlight Line -->
                    <div>
                        <div class="small fw-bold text-muted mb-2">Program yang Ditelaah:</div>
                        <div class="zl-code-block zl-tracer-code-viewer">
                            <div class="zl-code-header">
                                <div class="zl-code-dots">
                                    <span class="zl-code-dot zl-dot-red"></span>
                                    <span class="zl-code-dot zl-dot-yellow"></span>
                                    <span class="zl-code-dot zl-dot-green"></span>
                                </div>
                                <span class="text-secondary small font-mono">tracer.py</span>
                            </div>
                            <div class="zl-code-content">
                                @php
                                    $traceLines = explode("\n", $pertemuan['trace']['kode']);
                                @endphp
                                @foreach($traceLines as $idx => $tLine)
                                <div class="zl-code-line" data-line="{{ $idx + 1 }}">
                                    <span class="zl-line-number">{{ $idx + 1 }}</span>
                                    <span class="zl-line-text">{{ $tLine }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step Note -->
                        <div class="zl-tracer-note mt-3 p-3 bg-light border border-2 border-dark rounded-3">
                            <span class="text-muted">Tekan "Langkah Berikutnya" untuk memulai penelusuran.</span>
                        </div>
                    </div>

                    <!-- Column 2: Memory Watch Table & Terminal -->
                    <div>
                        <!-- Memory Watch -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold text-muted"><i class="bi bi-cpu"></i> Memory Watch (Variabel):</span>
                            </div>
                            <table class="zl-memory-table">
                                <thead>
                                    <tr>
                                        <th style="width: 35%;">Variabel</th>
                                        <th style="width: 35%;">Nilai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="zl-tracer-memory-body">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-2 small">Memuat variabel...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Simulated Terminal Screen -->
                        <div>
                            <div class="small fw-bold text-muted mb-2"><i class="bi bi-terminal-fill"></i> Output Terminal Layar:</div>
                            <div class="zl-terminal-screen zl-tracer-terminal">
                                <span class="text-secondary small italic">// Belum ada output</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section 4: Latihan Prediksi Output -->
        @if(!empty($pertemuan['latihan_prediksi']))
        <div class="mb-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="zl-badge zl-badge-primary">Bagian 4</span>
                <h2 class="h4 fw-bold text-dark mb-0">Latihan Prediksi Output</h2>
            </div>

            <div class="zl-card p-4 bg-white zl-predict-container" 
                 data-expected="{{ $pertemuan['latihan_prediksi']['pilihan'][$pertemuan['latihan_prediksi']['jawaban']] ?? '' }}"
                 data-explanation="{{ $pertemuan['latihan_prediksi']['pembahasan'] ?? '' }}">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <p class="fw-bold text-dark mb-2">{{ $pertemuan['latihan_prediksi']['pertanyaan'] }}</p>
                        <div class="zl-code-block mb-3">
                            <div class="zl-code-content py-2">
                                @foreach(explode("\n", $pertemuan['latihan_prediksi']['kode']) as $li => $tx)
                                <div class="zl-code-line">
                                    <span class="zl-line-number">{{ $li + 1 }}</span>
                                    <span class="zl-line-text">{{ $tx }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-bold text-dark">Pilih atau Ketikkan Tebakan Output Anda:</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control zl-predict-input border-2 border-dark" placeholder="Contoh: Tasikmalaya">
                            <button class="btn btn-primary zl-predict-check-btn border-2 border-dark text-white fw-bold" type="button">
                                Periksa Prediksi
                            </button>
                        </div>
                        <div class="small text-muted mb-3">
                            Pilihan bantuan: 
                            @foreach($pertemuan['latihan_prediksi']['pilihan'] as $opt)
                                <span class="badge text-bg-light border me-1">{{ $opt }}</span>
                            @endforeach
                        </div>
                        <div class="zl-predict-result" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section 5: Kuis Pemahaman Interaktif (4 Soal) -->
        @if(!empty($pertemuan['kuis']))
        <div class="mb-5 zl-meeting-quiz-container" data-meeting-id="{{ $id }}">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="zl-badge zl-badge-warning">Bagian 5</span>
                    <h2 class="h4 fw-bold text-dark mb-0">Kuis Pemahaman Membaca Kode (4 Soal)</h2>
                </div>
                <span class="small text-muted">Jawab semua soal & periksa jawaban</span>
            </div>

            @php
                $hurufOptions = ['A', 'B', 'C', 'D'];
            @endphp

            @foreach($pertemuan['kuis'] as $qIndex => $q)
                @php
                    $correctLetter = $hurufOptions[$q['jawaban']] ?? 'A';
                @endphp
                <div class="zl-quiz-card" data-correct-answer="{{ $correctLetter }}" data-explanation="{{ $q['pembahasan'] }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-dark font-mono text-white">Soal {{ $qIndex + 1 }} dari 4</span>
                        <span class="small text-muted">Pilihan Ganda</span>
                    </div>

                    <p class="fw-bold text-dark mb-2">{{ $q['pertanyaan'] }}</p>

                    @if(!empty($q['kode']))
                    <div class="zl-code-block mb-3" style="max-width: 550px;">
                        <div class="zl-code-content py-2">
                            @foreach(explode("\n", $q['kode']) as $qLineIdx => $qLine)
                            <div class="zl-code-line">
                                <span class="zl-line-number">{{ $qLineIdx + 1 }}</span>
                                <span class="zl-line-text">{{ $qLine }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="zl-options-group">
                        @foreach($q['pilihan'] as $optIndex => $pilihanText)
                            @php
                                $optLetter = $hurufOptions[$optIndex] ?? 'A';
                            @endphp
                            <label class="zl-option-label">
                                <input type="radio" name="kuis_{{ $id }}_{{ $qIndex }}" value="{{ $optLetter }}">
                                <span>{{ $pilihanText }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="zl-quiz-explanation" style="display: none;"></div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="button" class="zl-btn zl-btn-primary px-4 py-2 zl-quiz-submit-btn">
                    <i class="bi bi-check-circle-fill"></i> Periksa Jawaban Kuis
                </button>
            </div>

            <div class="zl-quiz-result-summary" style="display: none;"></div>
        </div>
        @endif

        <!-- Section 6: Playground Latihan Menulis & Menjalankan Kode Python -->
        @if(!empty($pertemuan['latihan_menulis']))
        <div class="mb-5 zl-playground-section">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="zl-badge zl-badge-success">Bagian 6</span>
                    <h2 class="h4 fw-bold text-dark mb-0">{{ $pertemuan['latihan_menulis']['judul'] ?? 'Playground: Latihan Tulis Kode' }}</h2>
                </div>
                <span class="zl-badge zl-badge-dark"><i class="bi bi-cpu-fill"></i> Python 3 In-Browser</span>
            </div>

            <!-- Instruction & Challenge Alert -->
            <div class="zl-card p-4 bg-white mb-3">
                <p class="text-secondary mb-2">{{ $pertemuan['latihan_menulis']['instruksi'] }}</p>
                @if(!empty($pertemuan['latihan_menulis']['tantangan']))
                <div class="p-2 px-3 bg-light rounded-2 border border-2 border-warning d-flex align-items-center gap-2 small">
                    <i class="bi bi-trophy-fill text-warning fs-6"></i>
                    <span class="text-dark fw-semibold">{{ $pertemuan['latihan_menulis']['tantangan'] }}</span>
                </div>
                @endif
            </div>

            <!-- Editor & Terminal Container -->
            <div class="zl-card p-0 overflow-hidden bg-white border-2 border-dark mb-4">
                <!-- Editor Top Bar -->
                <div class="p-2 px-3 bg-dark text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="zl-code-dots">
                            <span class="zl-code-dot zl-dot-red"></span>
                            <span class="zl-code-dot zl-dot-yellow"></span>
                            <span class="zl-code-dot zl-dot-green"></span>
                        </div>
                        <span class="font-mono small text-secondary">playground_pertemuan_{{ $id }}.py</span>
                    </div>

                    <!-- Quick Snippets & Reset -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted me-1 d-none d-md-inline">Sisipkan:</span>
                        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 zl-snippet-btn font-mono" data-snippet="print(&quot;Halo Dunia&quot;)" style="font-size: 0.75rem;">print()</button>
                        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 zl-snippet-btn font-mono" data-snippet="if nilai >= 75:&#10;    print(&quot;Lulus&quot;)&#10;else:&#10;    print(&quot;Remedial&quot;)" style="font-size: 0.75rem;">if/else</button>
                        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 zl-snippet-btn font-mono" data-snippet="for i in range(1, 5):&#10;    print(i)" style="font-size: 0.75rem;">for loop</button>
                        <button type="button" class="btn btn-sm btn-warning py-0 px-2 zl-reset-code-btn fw-bold text-dark" style="font-size: 0.75rem;" title="Kembalikan ke kode awal">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset Kode
                        </button>
                    </div>
                </div>

                <div class="row g-0">
                    <!-- Left: Code Textarea -->
                    <div class="col-lg-7 border-end border-2 border-dark p-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-bold text-muted mb-0"><i class="bi bi-pencil-square"></i> Editor Kode Python (Bisa diedit langsung):</label>
                            <span class="small text-muted font-mono" style="font-size: 0.75rem;">[Tab] = 4 spasi | [Ctrl+Enter] = Run</span>
                        </div>
                        <textarea class="form-control font-mono zl-playground-editor p-3" 
                                  id="zl-playground-editor" 
                                  rows="13" 
                                  spellcheck="false" 
                                  autocapitalize="off" 
                                  autocomplete="off" 
                                  data-default-code="{{ $pertemuan['latihan_menulis']['kode_awal'] }}"
                                  style="background: #0f172a; color: #f8fafc; font-size: 0.95rem; line-height: 1.6; border: 2px solid #0f172a; border-radius: 8px;">{{ $pertemuan['latihan_menulis']['kode_awal'] }}</textarea>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" id="zl-run-code-btn" class="zl-btn zl-btn-accent px-4 py-2">
                                <i class="bi bi-play-fill fs-5"></i> <strong>Jalankan Kode ▶</strong>
                            </button>
                            <span id="zl-run-status" class="small text-muted font-mono">Siap dijalankan</span>
                        </div>
                    </div>

                    <!-- Right: Terminal Output -->
                    <div class="col-lg-5 p-3 d-flex flex-column justify-content-between" style="background: #020617;">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold text-white"><i class="bi bi-terminal-fill text-success"></i> Terminal Output:</span>
                                <button type="button" id="zl-clear-terminal-btn" class="btn btn-sm btn-outline-secondary py-0 px-2 text-white" style="font-size: 0.75rem;">
                                    <i class="bi bi-eraser"></i> Bersihkan
                                </button>
                            </div>
                            <div class="zl-terminal-screen font-mono p-3" id="zl-playground-terminal" style="min-height: 250px; max-height: 350px; background: #000000; border: 1px solid #334155; border-radius: 6px; color: #22c55e;">
                                <div class="text-secondary small italic">// Klik tombol "Jalankan Kode ▶" di sebelah kiri untuk melihat output program...</div>
                            </div>
                        </div>

                        <div class="mt-3 pt-2 border-top border-secondary small text-secondary">
                            <i class="bi bi-shield-check text-success"></i> Dijalankan 100% aman di browser Anda tanpa instalasi software.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Bottom Navigation & Completion -->
        <div class="zl-card p-4 bg-light d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <a href="{{ route('pembelajaran.index') }}" class="btn btn-outline-dark border-2">
                    <i class="bi bi-house"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($prevId)
                    <a href="{{ route('pembelajaran.pertemuan', $prevId) }}" class="zl-btn zl-btn-outline">
                        <i class="bi bi-arrow-left"></i> Pertemuan {{ $prevId }}
                    </a>
                @endif

                @if($nextId)
                    <a href="{{ route('pembelajaran.pertemuan', $nextId) }}" class="zl-btn zl-btn-primary">
                        Pertemuan {{ $nextId }} <i class="bi bi-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('pembelajaran.asesmen') }}" class="zl-btn zl-btn-accent">
                        Lanjut ke Asesmen Akhir <i class="bi bi-trophy-fill"></i>
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

