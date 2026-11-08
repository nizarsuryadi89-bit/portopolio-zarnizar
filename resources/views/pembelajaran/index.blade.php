@extends('pembelajaran.layout')

@section('title', 'Zarnizar Learning — Belajar Membaca Kode Python')
@section('meta_description', 'Modul interaktif Informatika Kelas XII SMA Negeri 4 Tasikmalaya: Mengembangkan kemampuan membaca, memahami alur eksekusi, dan memprediksi output kode Python sederhana.')

@section('content')
<div class="zl-home-dashboard py-4 py-lg-5">
    <div class="container">

        <!-- HERO SECTION -->
        <div class="zl-card p-4 p-md-5 mb-5 bg-white position-relative overflow-hidden">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="zl-badge zl-badge-primary">
                            <i class="bi bi-mortarboard-fill"></i> SMAN 4 Tasikmalaya
                        </span>
                        <span class="zl-badge zl-badge-dark">
                            <i class="bi bi-clock-history"></i> 4 Pertemuan • 8 JP
                        </span>
                        <span class="zl-badge zl-badge-warning">
                            <i class="bi bi-eye-fill"></i> Fokus: Code Reading
                        </span>
                    </div>

                    <h1 class="display-5 fw-bold mb-3 text-dark">
                        Belajar Memahami <span class="text-primary text-decoration-underline">Kode Python</span>
                    </h1>

                    <p class="lead text-secondary mb-4">
                        Tingkatkan literasi komputasional Anda melalui seni <strong>membaca program</strong>. Pelajari cara menelusuri eksekusi baris demi baris, memantau perubahan variabel dalam memori, dan memprediksi output program Python tanpa harus menghafal sintaksis rumit.
                    </p>

                    <!-- Guru Pengajar Card -->
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border border-2 border-dark mb-4" style="max-width: 480px;">
                        <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-person-workspace fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Guru Pengampu Informatika</div>
                            <div class="fw-bold text-dark fs-6">{{ $meta['pengajar']['nama'] ?? 'Nizar Suryadi, S.T.' }}</div>
                            <div class="small text-secondary">{{ $meta['pengajar']['sekolah'] ?? 'SMA Negeri 4 Tasikmalaya' }}</div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('pembelajaran.pertemuan', 1) }}" class="zl-btn zl-btn-primary fs-6 px-4 py-3">
                            <i class="bi bi-play-circle-fill"></i> Mulai Belajar (Pertemuan 1)
                        </a>
                        <a href="#materi-list" class="zl-btn zl-btn-outline fs-6 px-4 py-3">
                            <i class="bi bi-grid-3x3-gap"></i> Lihat Daftar Pertemuan
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 text-center">
                    <!-- Progress Card -->
                    <div class="zl-card p-4 bg-light border-2 border-dark text-start">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-uppercase small text-muted">Progres Belajar Anda</span>
                            <span id="zl-progress-text" class="fw-bold text-primary">0% Selesai</span>
                        </div>
                        <div class="progress border border-dark rounded-pill mb-3" style="height: 14px; background: #e2e8f0;">
                            <div id="zl-overall-progress" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <p id="zl-completed-count" class="small text-secondary mb-3">0 dari 4 Pertemuan Selesai</p>

                        <div class="border-top border-2 border-secondary pt-3">
                            <div class="small text-muted mb-1">Status Asesmen Akhir:</div>
                            <div id="zl-home-assess-status" class="zl-badge zl-badge-primary">
                                <i class="bi bi-clock"></i> Belum Dikerjakan
                            </div>
                        </div>

                        <div class="mt-4 pt-2 border-top border-light-subtle">
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none zl-trigger-reset" style="font-size: 0.8rem;">
                                <i class="bi bi-trash"></i> Reset Progres Belajar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 PERTEMUAN MATERI SECTION -->
        <div id="materi-list" class="mb-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <span class="zl-badge zl-badge-primary mb-2">Kurikulum Pembelajaran</span>
                    <h2 class="fw-bold text-dark mb-1">Daftar Materi Pertemuan (8 JP)</h2>
                    <p class="text-secondary mb-0">Setiap pertemuan berdurasi 90 menit (2 JP) dilengkapi Code Viewer, Interactive Tracer, dan Kuis Pemahaman.</p>
                </div>
            </div>

            <div class="row g-4">
                @foreach ($pertemuanList as $item)
                <div class="col-md-6 col-lg-6">
                    <div class="zl-card zl-card-hover p-4 h-100 d-flex flex-column justify-content-between bg-white">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-dark text-white font-mono px-3 py-2 border border-dark rounded-pill fw-bold">
                                    PERTEMUAN {{ $item['nomor'] }}
                                </span>
                                <span id="zl-card-status-{{ $item['id'] }}" class="zl-badge zl-badge-primary">
                                    <i class="bi bi-book"></i> Siap Dipelajari
                                </span>
                            </div>

                            <h3 class="h5 fw-bold text-dark mb-2">
                                {{ $item['judul'] }}
                            </h3>

                            <div class="d-flex align-items-center gap-2 text-muted small mb-3">
                                <i class="bi bi-stopwatch-fill text-warning"></i>
                                <span>Durasi: <strong>{{ $item['durasi'] }}</strong></span>
                            </div>

                            <p class="text-secondary small mb-3">
                                {{ $item['ringkasan'] }}
                            </p>

                            <!-- Key Points -->
                            @if (!empty($item['tujuan']))
                            <div class="bg-light p-3 rounded-3 border border-1 border-secondary-subtle mb-3">
                                <div class="fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.7rem;">Target Kemampuan:</div>
                                <ul class="list-unstyled mb-0 small text-secondary">
                                    @foreach(array_slice($item['tujuan'], 0, 2) as $tujuan)
                                    <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i> {{ $tujuan }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        <div class="pt-3 border-top border-light-subtle d-flex justify-content-between align-items-center">
                            <span class="small text-muted">
                                <i class="bi bi-question-circle"></i> 4 Soal Kuis Interaktif
                            </span>
                            <a href="{{ route('pembelajaran.pertemuan', $item['id']) }}" id="zl-card-btn-{{ $item['id'] }}" class="zl-btn zl-btn-primary btn-sm">
                                Buka Materi <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- ASESMEN AKHIR BANNER -->
        <div class="zl-card p-4 p-md-5 bg-white border-2 border-dark mb-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex gap-2 mb-2">
                        <span class="zl-badge zl-badge-warning">Evaluasi Belajar</span>
                        <span class="zl-badge zl-badge-dark">KKTP: 75 / 100</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-2">Asesmen Akhir: Uji Kemampuan Membaca Kode</h2>
                    <p class="text-secondary mb-3">
                        Uji pemahaman Anda dengan <strong>{{ $asesmenMeta['total_soal'] }} soal pilihan ganda</strong> membaca program Python (tingkat Mudah, Sedang, hingga Menantang). Setiap nomor bernilai 10 poin dengan batas ketuntasan (KKTP) <strong>{{ $asesmenMeta['kktp_threshold'] }} poin</strong>.
                    </p>
                    <div class="d-flex flex-wrap gap-3 small text-muted">
                        <div><i class="bi bi-check-circle-fill text-success"></i> Nilai dihitung otomatis</div>
                        <div><i class="bi bi-file-earmark-text-fill text-primary"></i> Rincian pembahasan lengkap</div>
                        <div><i class="bi bi-printer-fill text-dark"></i> Fitur cetak lembar hasil</div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('pembelajaran.asesmen') }}" class="zl-btn zl-btn-accent fs-6 px-4 py-3 w-100 w-lg-auto">
                        <i class="bi bi-pencil-square"></i> Mulai Asesmen Akhir
                    </a>
                    <div class="small text-muted mt-2 text-center text-lg-end">
                        Sudah mengerjakan? <a href="{{ route('pembelajaran.hasil') }}" class="text-decoration-underline text-dark fw-bold">Lihat Hasil & Pembahasan</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

