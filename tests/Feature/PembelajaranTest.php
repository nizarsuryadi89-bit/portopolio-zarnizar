<?php

test('pembelajaran home renders cleanly without authentication', function (): void {
    $response = $this->get(route('pembelajaran.index'))
        ->assertOk()
        ->assertSeeText('Zarnizar Learning')
        ->assertSeeText('Nizar Suryadi, S.T.')
        ->assertSeeText('SMA Negeri 4 Tasikmalaya')
        ->assertSeeText('Belajar Memahami')
        ->assertSeeText('Kode Python')
        ->assertSeeText('8 JP')
        ->assertSeeText('Mengenal dan Membaca Kode Dasar Python')
        ->assertSeeText('Memahami Operator dan Percabangan')
        ->assertSeeText('Membaca List dan Perulangan')
        ->assertSeeText('Python Code Reading Challenge');

    expect($response->getContent())->toContain('zl-home-dashboard');
});

test('pembelajaran trailing slash URLs work seamlessly', function (): void {
    $this->get('/pembelajaran/')->assertOk()->assertSeeText('Zarnizar Learning');
    $this->get('/pembelajaran/pertemuan/1/')->assertOk()->assertSeeText('Dasar & Variabel Python');
    $this->get('/pembelajaran/asesmen/')->assertOk()->assertSeeText('Asesmen Akhir');
    $this->get('/pembelajaran/hasil/')->assertOk()->assertSeeText('Hasil Asesmen');
});

test('all four meeting pages render their full curriculum and interactive widgets', function (int $id): void {
    $response = $this->get(route('pembelajaran.pertemuan', $id))
        ->assertOk()
        ->assertSeeText('Pertemuan 0'.$id)
        ->assertSeeText('Target Kemampuan')
        ->assertSeeText('Trace Code Interaktif')
        ->assertSeeText('Kuis Pemahaman Membaca Kode (4 Soal)')
        ->assertSeeText('Langkah Berikutnya')
        ->assertSeeText('Periksa Jawaban Kuis')
        ->assertSeeText('Playground Latihan');

    // Confirm code tracer widget data attribute is present
    expect($response->getContent())->toContain('data-trace-steps');
    expect($response->getContent())->toContain('zl-tracer-widget');
    // Confirm playground editor is present
    expect($response->getContent())->toContain('zl-playground-editor');
    expect($response->getContent())->toContain('Jalankan Kode');
})->with([1, 2, 3, 4]);

test('invalid meeting ID returns 404', function (): void {
    $this->get('/pembelajaran/pertemuan/99')->assertNotFound();
    $this->get('/pembelajaran/pertemuan/0')->assertNotFound();
});

test('asesmen page renders 10 questions with KKTP 75 and student identity input', function (): void {
    $response = $this->get(route('pembelajaran.asesmen'))
        ->assertOk()
        ->assertSeeText('Asesmen Akhir')
        ->assertSeeText('KKTP: 75 Poin')
        ->assertSeeText('10 Soal • Bobot @10 Poin')
        ->assertSeeText('Identitas Siswa')
        ->assertSeeText('Navigasi Soal');

    // Check all 10 questions are rendered
    for ($i = 1; $i <= 10; $i++) {
        expect($response->getContent())->toContain('soal-'.$i);
        expect($response->getContent())->toContain('name="q'.$i.'"');
    }
});

test('hasil page renders successfully with question breakdown and print elements', function (): void {
    $this->get(route('pembelajaran.hasil'))
        ->assertOk()
        ->assertSeeText('Hasil Asesmen Membaca Kode Python')
        ->assertSeeText('Standar KKTP: 75')
        ->assertSeeText('Cetak Hasil / Simpan PDF')
        ->assertSeeText('Ulangi Asesmen')
        ->assertSeeText('Rincian Jawaban & Pembahasan Soal');
});

test('existing website routes remain intact and are not disrupted', function (): void {
    $this->get('/')->assertOk();
    $this->get('/gtk2026')->assertOk();
});
