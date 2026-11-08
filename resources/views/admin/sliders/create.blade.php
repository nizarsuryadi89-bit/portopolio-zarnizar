@extends('layouts.admin')

@section('content')
<div class="admin-page-header pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Slide</h1>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Gambar Banner <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
                <small class="text-muted">Gunakan gambar dengan rasio lanskap (contoh: 1920x1080) agar terlihat bagus di desktop.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul Utama (Opsional)</label>
                <input type="text" name="title" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Subjudul / Deskripsi (Opsional)</label>
                <input type="text" name="subtitle" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">URL / Tautan (Opsional)</label>
                <input type="url" name="url" class="form-control" placeholder="https://...">
            </div>
            <div class="mb-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                <label class="form-check-label" for="isActive">Tampilkan (Aktif)</label>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
