@extends('pembelajaran.layout')

@section('title', 'Hasil Asesmen: Membaca Kode Python — Zarnizar Learning')
@section('meta_description', 'Lembar hasil asesmen membaca kode Python Informatika Kelas XII SMA Negeri 4 Tasikmalaya.')

@section('content')
<div class="py-4 py-lg-5 zl-result-page">
    <div class="container">

        <!-- Top Breadcrumb & Action -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 zl-no-print">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('pembelajaran.index') }}" class="text-decoration-none">Beranda</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">Hasil Asesmen</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center gap-2">
                <button type="button" id="zl-print-btn" class="zl-btn zl-btn-outline btn-sm">
                    <i class="bi bi-printer-fill"></i> Cetak Hasil / Simpan PDF
                </button>
                <a href="{{ route('pembelajaran.asesmen') }}" class="zl-btn zl-btn-accent btn-sm">
                    <i class="bi bi-arrow-repeat"></i> Ulangi Asesmen
                </a>
            </div>
        </div>

        <!-- Empty State (When no assessment found in localStorage) -->
        <div id="zl-result-empty" class="zl-card p-5 bg-white text-center my-4" style="display: none;">
            <div class="mb-3">
                <i class="bi bi-clipboard-x text-muted" style="font-size: 3.5rem;"></i>
            </div>
            <h2 class="h4 fw-bold text-dark mb-2">Belum Ada Hasil Asesmen</h2>
            <p class="text-secondary mb-4" style="max-width: 500px; margin: 0 auto;">
                Anda belum menyelesaikan asesmen akhir membaca kode Python pada peramban/perangkat ini. Silakan kerjakan 10 soal asesmen terlebih dahulu untuk melihat skor dan pembahasannya.
            </p>
            <a href="{{ route('pembelajaran.asesmen') }}" class="zl-btn zl-btn-primary px-4 py-2">
                <i class="bi bi-pencil-square"></i> Buka Halaman Asesmen
            </a>
        </div>

        <!-- Result Content (Populated by JavaScript) -->
        <div id="zl-result-content">

            <!-- Formal Print Header (Only visible on print) -->
            <div class="zl-print-header d-none">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <div>
                        <h1 class="h4 fw-bold mb-0">LAPORAN HASIL ASESMEN FORMATIF INFORMATIKA</h1>
                        <div class="text-uppercase small">Materi: Membaca dan Memahami Kode Python (Code Reading)</div>
                        <div class="small text-muted">SMA Negeri 4 Tasikmalaya • Pengajar: {{ $meta['pengajar']['nama'] ?? 'Nizar Suryadi, S.T.' }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5">Zarnizar Learning</div>
                        <div class="small text-muted">zarnizar.com</div>
                    </div>
                </div>
            </div>

            <!-- Score Summary Card -->
            <div class="zl-card p-4 p-md-5 bg-white mb-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="zl-badge zl-badge-primary">Hasil Evaluasi Siswa</span>
                            <span class="zl-badge zl-badge-dark">Standar KKTP: 75</span>
                        </div>

                        <h1 class="h3 fw-bold text-dark mb-3">
                            Hasil Asesmen Membaca Kode Python
                        </h1>

                        <!-- Student Details -->
                        <div class="row g-2 mb-4">
                            <div class="col-sm-6">
                                <div class="p-2 px-3 bg-light rounded-2 border">
                                    <div class="small text-muted">Nama Siswa:</div>
                                    <div class="fw-bold text-dark fs-6" id="zl-res-name">-</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="p-2 px-3 bg-light rounded-2 border">
                                    <div class="small text-muted">Kelas:</div>
                                    <div class="fw-bold text-dark fs-6" id="zl-res-class">-</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="p-2 px-3 bg-light rounded-2 border">
                                    <div class="small text-muted">Waktu Selesai:</div>
                                    <div class="fw-bold text-dark fs-6" id="zl-res-date" style="font-size: 0.85rem;">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Motivation Message -->
                        <div class="alert alert-light border border-2 border-dark mb-0">
                            <div id="zl-res-motivation" class="text-secondary small">
                                Memuat catatan guru...
                            </div>
                        </div>
                    </div>

                    <!-- Score Badge Block -->
                    <div class="col-lg-4 text-center">
                        <div class="zl-card p-4 bg-light border-2 border-dark">
                            <div class="small fw-bold text-uppercase text-muted mb-1">Nilai Akhir Siswa</div>
                            <div class="display-3 fw-bold text-primary font-mono lh-1 mb-2">
                                <span id="zl-res-score">0</span><span class="fs-4 text-muted">/100</span>
                            </div>

                            <div class="mb-3">
                                <span id="zl-res-kktp-badge" class="zl-badge zl-badge-success fs-6 py-2 px-3">
                                    Memeriksa...
                                </span>
                            </div>

                            <div class="d-flex justify-content-center gap-3 small border-top pt-2">
                                <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> <span id="zl-res-correct">0 Benar</span></span>
                                <span class="text-muted">|</span>
                                <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> <span id="zl-res-wrong">0 Salah</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question Review Breakdown -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h4 fw-bold text-dark mb-1">Rincian Jawaban &amp; Pembahasan Soal</h2>
                        <p class="text-secondary small mb-0">Telaah kembali setiap soal untuk memahami logika penelusuran program yang benar.</p>
                    </div>
                </div>

                @php
                    $letterOptions = ['A', 'B', 'C', 'D'];
                @endphp

                @foreach($soal as $sIndex => $sItem)
                    @php
                        $qNum = $sItem['id'] ?? ($sIndex + 1);
                        $kunci = $letterOptions[$sItem['jawaban']] ?? 'A';
                    @endphp

                    <div class="zl-quiz-card zl-review-card mb-4" data-q="{{ $qNum }}" data-kunci="{{ $kunci }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-dark font-mono text-white">Soal #{{ $qNum }}</span>
                                <span class="badge text-bg-light border text-muted small">{{ $sItem['topik'] }}</span>
                            </div>
                            <span class="zl-review-status zl-badge zl-badge-primary">Memeriksa</span>
                        </div>

                        <p class="fw-bold text-dark mb-2">{{ $sItem['pertanyaan'] }}</p>

                        @if(!empty($sItem['kode']))
                        <div class="zl-code-block mb-3" style="max-width: 580px;">
                            <div class="zl-code-content py-2">
                                @foreach(explode("\n", $sItem['kode']) as $lIdx => $lTxt)
                                <div class="zl-code-line">
                                    <span class="zl-line-number">{{ $lIdx + 1 }}</span>
                                    <span class="zl-line-text">{{ $lTxt }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Student vs Key Comparison -->
                        <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded-2 border mb-3">
                            <div>
                                <span class="small text-muted">Jawaban Anda:</span>
                                <strong class="ms-1 font-mono fs-6 zl-review-student-ans">-</strong>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <span class="small text-muted">Kunci Jawaban:</span>
                                <strong class="ms-1 font-mono fs-6 text-success zl-review-correct-ans">{{ $kunci }}</strong>
                            </div>
                        </div>

                        <!-- Full Explanation -->
                        <div class="p-3 bg-light-subtle rounded-2 border border-1 border-secondary-subtle">
                            <div class="fw-bold text-dark small mb-1"><i class="bi bi-info-circle-fill text-primary"></i> Penjelasan Logika:</div>
                            <div class="small text-secondary" style="white-space: pre-line;">{{ $sItem['pembahasan'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bottom Actions -->
            <div class="zl-card p-4 bg-light d-flex flex-wrap justify-content-between align-items-center gap-3 zl-no-print">
                <a href="{{ route('pembelajaran.index') }}" class="zl-btn zl-btn-outline">
                    <i class="bi bi-house-door"></i> Kembali ke Beranda Pembelajaran
                </a>

                <div class="d-flex gap-2">
                    <button type="button" onclick="window.print()" class="zl-btn zl-btn-outline">
                        <i class="bi bi-printer"></i> Cetak Lembar Hasil
                    </button>
                    <a href="{{ route('pembelajaran.asesmen') }}" class="zl-btn zl-btn-primary">
                        <i class="bi bi-arrow-repeat"></i> Ulangi Asesmen
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

