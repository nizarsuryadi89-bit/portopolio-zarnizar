@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kegiatan & Pelatihan</h1>
    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">Tambah Kegiatan & Pelatihan</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table role="table" class="admin-table table table-striped table-sm">
        <thead role="rowgroup">
            <tr role="row">
                <th scope="col">ID</th>
                <th scope="col">Judul/Nama</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody role="rowgroup">
            @forelse($activities as $item)
            <tr role="row">
                <td role="cell" data-label="ID">{{ $item->id }}</td>
                <td role="cell" data-label="Judul/Nama">{{ $item->title ?? $item->name }}</td>
                <td role="cell" data-label="Aksi">
                    <a href="{{ route('admin.activities.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.activities.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr role="row">
                <td colspan="3" class="text-center">Belum ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
