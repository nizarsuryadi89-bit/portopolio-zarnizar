@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hero Slider</h1>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">Tambah Slide</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table role="table" class="admin-table table table-hover">
                <thead role="rowgroup">
                    <tr role="row">
                        <th scope="col">Gambar</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Urutan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @forelse($sliders as $slider)
                    <tr role="row">
                        <td role="cell" data-label="Gambar">
                            @if($slider->image)
                                <img src="{{ Storage::url($slider->image) }}" alt="Slider" style="height: 60px; border-radius: 8px;">
                            @endif
                        </td>
                        <td role="cell" data-label="Judul">{{ $slider->title ?? '-' }}</td>
                        <td role="cell" data-label="Urutan">{{ $slider->sort_order }}</td>
                        <td role="cell" data-label="Status">
                            @if($slider->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td role="cell" data-label="Aksi">
                            <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus slide ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr role="row">
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada slide. Silakan tambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
