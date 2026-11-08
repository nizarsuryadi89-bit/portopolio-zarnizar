<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PembelajaranController extends Controller
{
    /**
     * Tampilkan beranda microsite Zarnizar Learning.
     */
    public function index(): View
    {
        $meta = config('pembelajaran.meta', []);
        $pertemuanList = config('pembelajaran.pertemuan', []);
        $asesmenMeta = [
            'total_soal' => config('pembelajaran.asesmen.total_soal', 10),
            'waktu_menit' => config('pembelajaran.asesmen.waktu_menit', 30),
            'kktp_threshold' => config('pembelajaran.asesmen.kktp_threshold', 75),
        ];

        return view('pembelajaran.index', [
            'meta' => $meta,
            'pertemuanList' => $pertemuanList,
            'asesmenMeta' => $asesmenMeta,
        ]);
    }

    /**
     * Tampilkan halaman materi pertemuan tertentu (1 s.d. 4).
     */
    public function pertemuan(int $id): View
    {
        $pertemuan = config('pembelajaran.pertemuan.'.$id);
        abort_unless(is_array($pertemuan), 404, 'Materi pertemuan tidak ditemukan.');

        $meta = config('pembelajaran.meta', []);
        $prevId = $id > 1 ? $id - 1 : null;
        $nextId = $id < 4 ? $id + 1 : null;

        return view('pembelajaran.pertemuan', [
            'meta' => $meta,
            'pertemuan' => $pertemuan,
            'id' => $id,
            'prevId' => $prevId,
            'nextId' => $nextId,
        ]);
    }

    /**
     * Tampilkan lembar asesmen akhir (10 soal membaca kode).
     */
    public function asesmen(): View
    {
        $meta = config('pembelajaran.meta', []);
        $asesmen = config('pembelajaran.asesmen', []);

        return view('pembelajaran.asesmen', [
            'meta' => $meta,
            'asesmen' => $asesmen,
            'soal' => $asesmen['soal'] ?? [],
        ]);
    }

    /**
     * Tampilkan hasil asesmen siswa (dinamis via localStorage / fallback).
     */
    public function hasil(): View
    {
        $meta = config('pembelajaran.meta', []);
        $asesmen = config('pembelajaran.asesmen', []);

        return view('pembelajaran.hasil', [
            'meta' => $meta,
            'asesmen' => $asesmen,
            'soal' => $asesmen['soal'] ?? [],
        ]);
    }
}
