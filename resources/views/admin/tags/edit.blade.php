@extends('layouts.admin')

@section('content')
<div class="admin-page-header pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Tag</h1>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ $tag->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
