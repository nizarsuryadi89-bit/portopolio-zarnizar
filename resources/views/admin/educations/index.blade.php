@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Educations</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.educations.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Education
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table role="table" class="admin-table table table-hover align-middle">
                <thead role="rowgroup">
                    <tr role="row">
                        <th scope="col" width="5%">No</th>
                        <th scope="col" width="25%">Institution</th>
                        <th scope="col" width="15%">Degree</th>
                        <th scope="col" width="25%">Field</th>
                        <th scope="col" width="20%">Period</th>
                        <th scope="col" width="10%">Action</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @forelse($educations as $index => $education)
                    <tr role="row">
                        <td role="cell" data-label="No">{{ $index + 1 }}</td>
                        <td role="cell" data-label="Institution">{{ $education->institution }}</td>
                        <td role="cell" data-label="Degree">{{ $education->degree }}</td>
                        <td role="cell" data-label="Field">{{ $education->field }}</td>
                        <td role="cell" data-label="Period">{{ $education->start_year }} - {{ $education->end_year ?? 'Sekarang' }}</td>
                        <td role="cell" data-label="Action">
                            <div class="btn-group">
                                <a href="{{ route('admin.educations.edit', $education) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.educations.destroy', $education) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendidikan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr role="row">
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data pendidikan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
