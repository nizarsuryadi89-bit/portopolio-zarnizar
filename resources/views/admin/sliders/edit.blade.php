@extends('layouts.admin')

@section('content')
<div class="admin-page-header pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Slide</h1>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Gambar Banner</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if($slider->image)
                    <img src="{{ Storage::url($slider->image) }}" alt="Slider" class="mt-2 rounded" style="max-height: 150px;">
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Judul Utama</label>
                <input type="text" name="title" class="form-control" value="{{ $slider->title }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Subjudul / Deskripsi</label>
                <input type="text" name="subtitle" class="form-control" value="{{ $slider->subtitle }}">
            </div>
            <div class="mb-3">
                <label class="form-label">URL / Tautan</label>
                <input type="url" name="url" class="form-control" value="{{ $slider->url }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="{{ $slider->sort_order }}">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ $slider->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Tampilkan (Aktif)</label>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
