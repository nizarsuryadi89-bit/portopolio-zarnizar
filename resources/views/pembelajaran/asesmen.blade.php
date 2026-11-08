@extends('pembelajaran.layout')

@section('title', 'Asesmen Akhir: Membaca Kode Python — Zarnizar Learning')
@section('meta_description', 'Asesmen Akhir Informatika Kelas XII: Evaluasi 10 soal pilihan ganda pemahaman logika dan membaca kode Python.')

@section('content')
<div class="py-4 py-lg-5">
    <div class="container">

        <!-- Top Header & Breadcrumb -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('pembelajaran.index') }}" class="text-decoration-none">Beranda</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">Asesmen Akhir</li>
                </ol>
            </nav>

            <a href="{{ route('pembelajaran.index') }}" class="btn btn-sm btn-outline-dark border-2">
                <i class="bi bi-x-circle"></i> Batal & Kembali
            </a>
        </div>

        <div class="row g-4">
            <!-- Left Column: Form & 10 Questions -->
            <div class="col-lg-8">
                <!-- Instruction Card -->
                <div class="zl-card p-4 bg-white mb-4">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="zl-badge zl-badge-warning">Evaluasi Akhir</span>
                        <span class="zl-badge zl-badge-dark">10 Soal • Bobot @10 Poin</span>
                        <span class="zl-badge zl-badge-success">KKTP: 75 Poin</span>
                    </div>

                    <h1 class="h3 fw-bold text-dark mb-2">
                        {{ $asesmen['judul'] ?? 'Asesmen Akhir Membaca Kode Python' }}
                    </h1>
                    <p class="text-secondary small mb-3">
                        {{ $asesmen['instruksi'] ?? 'Jawablah seluruh pertanyaan membaca kode di bawah ini. Jawaban akan diperiksa dan dinilai secara otomatis setelah Anda menekan tombol Kirim Jawaban.' }}
                    </p>

                    <!-- Student Identity Input -->
                    <div class="p-3 bg-light rounded-3 border border-2 border-dark">
                        <div class="fw-bold small text-dark mb-2"><i class="bi bi-person-badge"></i> Identitas Siswa (Disimpan di Lembar Hasil):</div>
                        <div class="row g-2">
                            <div class="col-md-7">
                                <label for="zl-student-name" class="form-label small text-muted mb-1">Nama Lengkap Siswa:</label>
                                <input type="text" id="zl-student-name" class="form-control form-control-sm border-2 border-dark" placeholder="Contoh: Ahmad Fauzan" value="">
                            </div>
                            <div class="col-md-5">
                                <label for="zl-student-class" class="form-label small text-muted mb-1">Kelas:</label>
                                <input type="text" id="zl-student-class" class="form-control form-control-sm border-2 border-dark" placeholder="Contoh: XII-MIPA 1" value="">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions Container -->
                <form id="zl-assessment-form">
                    @php
                        $letterOptions = ['A', 'B', 'C', 'D'];
                    @endphp

                    @foreach($soal as $index => $item)
                        @php
                            $qNum = $item['id'] ?? ($index + 1);
                            $correctLetter = $letterOptions[$item['jawaban']] ?? 'A';
                        @endphp
                        <div class="zl-card p-4 bg-white mb-4 zl-assessment-card" id="soal-{{ $qNum }}" data-kunci="{{ $correctLetter }}" data-q="{{ $qNum }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-dark text-white font-mono px-3 py-2">
                                        SOAL #{{ $qNum }}
                                    </span>
                                    <span class="zl-badge {{ $item['tingkat'] === 'Mudah' ? 'zl-badge-success' : ($item['tingkat'] === 'Sedang' ? 'zl-badge-warning' : 'zl-badge-danger') }}">
                                        {{ $item['tingkat'] }}
                                    </span>
                                    <span class="badge text-bg-light border text-muted small d-none d-sm-inline">
                                        {{ $item['topik'] }}
                                    </span>
                                </div>
                                <span class="badge text-bg-secondary font-mono">10 Poin</span>
                            </div>

                            <p class="fw-bold text-dark mb-3">
                                {{ $item['pertanyaan'] }}
                            </p>

                            @if(!empty($item['kode']))
                            <div class="zl-code-block mb-3">
                                <div class="zl-code-header">
                                    <div class="zl-code-dots">
                                        <span class="zl-code-dot zl-dot-red"></span>
                                        <span class="zl-code-dot zl-dot-yellow"></span>
                                        <span class="zl-code-dot zl-dot-green"></span>
                                    </div>
                                    <span class="text-secondary small font-mono">soal_{{ $qNum }}.py</span>
                                </div>
                                <div class="zl-code-content py-2">
                                    @foreach(explode("\n", $item['kode']) as $lIdx => $lTxt)
                                    <div class="zl-code-line">
                                        <span class="zl-line-number">{{ $lIdx + 1 }}</span>
                                        <span class="zl-line-text">{{ $lTxt }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="zl-options-group">
                                @foreach($item['pilihan'] as $optIdx => $optTxt)
                                    @php
                                        $currLetter = $letterOptions[$optIdx] ?? 'A';
                                    @endphp
                                    <label class="zl-option-label">
                                        <input type="radio" name="q{{ $qNum }}" value="{{ $currLetter }}">
                                        <span>{{ $optTxt }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Bottom Submit Button -->
                    <div class="zl-card p-4 bg-light text-center mb-5">
                        <h4 class="fw-bold text-dark mb-2">Sudah Memeriksa Seluruh Jawaban?</h4>
                        <p class="text-secondary small mb-3">Pastikan ke-10 pertanyaan telah Anda jawab dengan teliti sebelum mengirimkan asesmen.</p>
                        <button type="button" id="zl-submit-assessment-btn" class="zl-btn zl-btn-accent fs-5 px-5 py-3">
                            <i class="bi bi-send-check-fill"></i> Kirim Jawaban & Lihat Hasil
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Sticky Question Palette -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 85px; z-index: 10;">
                    <div class="zl-card p-4 bg-white mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-grid-fill text-primary"></i> Navigasi Soal</h5>
                            <span id="zl-answered-count" class="badge bg-primary text-white font-mono">0 / 10 Terjawab</span>
                        </div>

                        <p class="small text-muted mb-3">Klik nomor soal di bawah untuk melompat langsung ke soal tersebut:</p>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @for($i = 1; $i <= 10; $i++)
                                <button type="button" class="zl-palette-btn" data-q="{{ $i }}">
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>

                        <div class="small border-top pt-3">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="zl-palette-btn" style="width: 1.25rem; height: 1.25rem; background: #ffffff;"></span>
                                <span class="text-muted" style="font-size: 0.8rem;">Belum Dijawab</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="zl-palette-btn zl-palette-answered" style="width: 1.25rem; height: 1.25rem;"></span>
                                <span class="text-muted" style="font-size: 0.8rem;">Sudah Dijawab</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Membaca Kode -->
                    <div class="zl-card p-3 bg-light small">
                        <div class="fw-bold text-dark mb-1"><i class="bi bi-lightbulb-fill text-warning"></i> Tips Membaca Kode:</div>
                        <ul class="mb-0 ps-3 text-secondary" style="font-size: 0.8rem;">
                            <li>Ikuti alur sekuensial dari baris teratas.</li>
                            <li>Perhatikan kondisi boolean pada <code>if</code> &amp; <code>elif</code>.</li>
                            <li>Perhatikan batasan fungsi <code>range(start, stop)</code>.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

