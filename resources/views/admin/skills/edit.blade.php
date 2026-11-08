@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Skill</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.skills.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.skills.update', $skill) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Nama Skill <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $skill->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ old('category', $skill->category) }}" placeholder="Contoh: Programming, Design, dll">
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Percentage (0-100)</label>
                <input type="number" class="form-control @error('percentage') is-invalid @enderror" name="percentage" value="{{ old('percentage', $skill->percentage) }}" min="0" max="100">
                <div class="form-text">Kosongkan jika tidak ingin menampilkan progress bar.</div>
                @error('percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Icon Class (Bootstrap Icons / Font Awesome)</label>
                <input type="text" class="form-control @error('icon') is-invalid @enderror" name="icon" value="{{ old('icon', $skill->icon) }}" placeholder="Contoh: bi bi-code-slash">
                @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{ old('sort_order', $skill->sort_order) }}" required>
                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $skill->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Tampilkan di Frontend)</label>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
