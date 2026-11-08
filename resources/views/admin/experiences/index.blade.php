@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Experiences</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.experiences.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Experience
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
                        <th scope="col" width="25%">Position</th>
                        <th scope="col" width="25%">Organization</th>
                        <th scope="col" width="25%">Period</th>
                        <th scope="col" width="10%">Status</th>
                        <th scope="col" width="10%">Action</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @forelse($experiences as $index => $experience)
                    <tr role="row">
                        <td role="cell" data-label="No">{{ $index + 1 }}</td>
                        <td role="cell" data-label="Position">{{ $experience->position }}</td>
                        <td role="cell" data-label="Organization">{{ $experience->organization }}</td>
                        <td role="cell" data-label="Period">
                            {{ \Carbon\Carbon::parse($experience->start_date)->format('M Y') }} - 
                            @if($experience->is_current)
                                Sekarang
                            @else
                                {{ $experience->end_date ? \Carbon\Carbon::parse($experience->end_date)->format('M Y') : 'N/A' }}
                            @endif
                        </td>
                        <td role="cell" data-label="Status">
                            @if($experience->is_current)
                                <span class="badge bg-primary">Current</span>
                            @else
                                <span class="badge bg-secondary">Past</span>
                            @endif
                        </td>
                        <td role="cell" data-label="Action">
                            <div class="btn-group">
                                <a href="{{ route('admin.experiences.edit', $experience) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.experiences.destroy', $experience) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengalaman ini?');">
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
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data pengalaman kerja.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
