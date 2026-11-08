@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Social Media Links</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.social-links.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Social Link
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
                        <th scope="col" width="30%">Platform Name</th>
                        <th scope="col" width="35%">URL</th>
                        <th scope="col" width="10%">Status</th>
                        <th scope="col" width="10%">Sort</th>
                        <th scope="col" width="10%">Action</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @forelse($socialLinks as $index => $link)
                    <tr role="row">
                        <td role="cell" data-label="No">{{ $index + 1 }}</td>
                        <td role="cell" data-label="Platform Name">
                            @if($link->icon)
                                <i class="{{ $link->icon }} me-2"></i>
                            @else
                                <i class="bi bi-link-45deg me-2 text-muted"></i>
                            @endif
                            {{ $link->name }}
                        </td>
                        <td role="cell" data-label="URL">
                            <a href="{{ $link->url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 250px;">
                                {{ $link->url }}
                            </a>
                        </td>
                        <td role="cell" data-label="Status">
                            @if($link->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td role="cell" data-label="Sort">{{ $link->sort_order }}</td>
                        <td role="cell" data-label="Action">
                            <div class="btn-group">
                                <a href="{{ route('admin.social-links.edit', $link) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.social-links.destroy', $link) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus social link ini?');">
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
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data social link.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
