@extends('layouts.admin')

@section('content')
<div class="admin-page-header pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Kegiatan & Pelatihan</h1>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Judul Kegiatan</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                <label class="form-check-label" for="isActive">Aktif</label>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
