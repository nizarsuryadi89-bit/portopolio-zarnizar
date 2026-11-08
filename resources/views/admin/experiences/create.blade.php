@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Experience</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.experiences.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.experiences.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Posisi / Jabatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ old('position') }}" required>
                @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Organisasi / Perusahaan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('organization') is-invalid @enderror" name="organization" value="{{ old('organization') }}" required>
                @error('organization') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">Mulai <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label">Selesai</label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}">
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="is_current" name="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_current">Sekarang</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Pekerjaan</label>
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
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Experience</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isCurrentCheckbox = document.getElementById('is_current');
        const endDateInput = document.querySelector('input[name="end_date"]');

        function toggleEndDate() {
            if (isCurrentCheckbox.checked) {
                endDateInput.value = '';
                endDateInput.setAttribute('disabled', 'disabled');
            } else {
                endDateInput.removeAttribute('disabled');
            }
        }

        isCurrentCheckbox.addEventListener('change', toggleEndDate);
        toggleEndDate(); // Run on load
    });
</script>
@endsection
