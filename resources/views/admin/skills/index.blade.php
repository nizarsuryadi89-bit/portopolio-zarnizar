@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Skills</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.skills.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Skill
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
                        <th scope="col" width="30%">Skill</th>
                        <th scope="col" width="20%">Category</th>
                        <th scope="col" width="15%">Percentage</th>
                        <th scope="col" width="10%">Status</th>
                        <th scope="col" width="10%">Sort</th>
                        <th scope="col" width="10%">Action</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @forelse($skills as $index => $skill)
                    <tr role="row">
                        <td role="cell" data-label="No">{{ $index + 1 }}</td>
                        <td role="cell" data-label="Skill">
                            @if($skill->icon)
                                <i class="{{ $skill->icon }} me-2"></i>
                            @else
                                <i class="bi bi-check-circle me-2 text-muted"></i>
                            @endif
                            {{ $skill->name }}
                        </td>
                        <td role="cell" data-label="Category">{{ $skill->category ?? '-' }}</td>
                        <td role="cell" data-label="Percentage">
                            @if($skill->percentage !== null)
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $skill->percentage }}%;" aria-valuenow="{{ $skill->percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $skill->percentage }}%</div>
                                </div>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td role="cell" data-label="Status">
                            @if($skill->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td role="cell" data-label="Sort">{{ $skill->sort_order }}</td>
                        <td role="cell" data-label="Action">
                            <div class="btn-group">
                                <a href="{{ route('admin.skills.edit', $skill) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus skill ini?');">
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
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data skill.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
