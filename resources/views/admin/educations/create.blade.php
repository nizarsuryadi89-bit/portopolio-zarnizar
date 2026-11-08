@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Education</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.educations.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.educations.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Institusi / Universitas <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('institution') is-invalid @enderror" name="institution" value="{{ old('institution') }}" required>
                @error('institution') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gelar (Degree) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('degree') is-invalid @enderror" name="degree" value="{{ old('degree') }}" placeholder="Contoh: S1, SMA" required>
                    @error('degree') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Bidang Studi (Field) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('field') is-invalid @enderror" name="field" value="{{ old('field') }}" placeholder="Contoh: Teknik Informatika" required>
                    @error('field') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Mulai <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('start_year') is-invalid @enderror" name="start_year" value="{{ old('start_year') }}" placeholder="Contoh: 2010" required>
                    @error('start_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Selesai</label>
                    <input type="text" class="form-control @error('end_year') is-invalid @enderror" name="end_year" value="{{ old('end_year') }}" placeholder="Kosongkan jika masih berlangsung">
                    @error('end_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi / Keterangan</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{ old('sort_order', 0) }}" required>
                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Education</button>
            </div>
        </form>
    </div>
</div>
@endsection
