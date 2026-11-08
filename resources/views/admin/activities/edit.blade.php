@extends('layouts.admin')

@section('content')
<div class="admin-page-header pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Kegiatan & Pelatihan</h1>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Judul Kegiatan</label>
                <input type="text" name="title" class="form-control" value="{{ $activity->title }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ $activity->description }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ $activity->date }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if($activity->image)
                    <img src="{{ Storage::url($activity->image) }}" alt="Image" class="mt-2" style="max-height:100px;">
                @endif
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ $activity->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Aktif</label>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
