@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Social Link</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.social-links.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.social-links.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Platform Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Contoh: GitHub, LinkedIn" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">URL Profile <span class="text-danger">*</span></label>
                <input type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" placeholder="https://..." required>
                @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Icon Class (Bootstrap Icons / Font Awesome)</label>
                <input type="text" class="form-control @error('icon') is-invalid @enderror" name="icon" value="{{ old('icon') }}" placeholder="Contoh: bi bi-github">
                <div class="form-text">Cari ikon di <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>.</div>
                @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{ old('sort_order', 0) }}" required>
                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Tampilkan di Frontend)</label>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Social Link</button>
            </div>
        </form>
    </div>
</div>
@endsection
