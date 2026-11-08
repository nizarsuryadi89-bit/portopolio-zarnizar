@extends('layouts.admin')

@section('content')
<div class="admin-page-header d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Profile</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                @if($profile && $profile->photo)
                    <img src="{{ Storage::url($profile->photo) }}" class="rounded-circle mb-3 object-fit-cover" width="150" height="150" alt="Profile Photo">
                @else
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px;">
                        <i class="bi bi-person text-white fs-1"></i>
                    </div>
                @endif
                <h4 class="card-title">{{ $profile->name ?? 'Belum ada nama' }}</h4>
                <p class="text-muted mb-1">{{ $profile->title ?? 'Belum ada title' }}</p>
                <p class="text-muted small mb-3"><i class="bi bi-geo-alt"></i> {{ $profile->location ?? 'Lokasi belum diisi' }}</p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($profile && $profile->email)
                        <a href="mailto:{{ $profile->email }}" class="btn btn-sm btn-outline-secondary" title="{{ $profile->email }}"><i class="bi bi-envelope"></i></a>
                    @endif
                    @if($profile && $profile->whatsapp)
                        <a href="https://wa.me/{{ $profile->whatsapp }}" target="_blank" class="btn btn-sm btn-outline-success" title="{{ $profile->whatsapp }}"><i class="bi bi-whatsapp"></i></a>
                    @endif
                </div>

                @if($profile && $profile->cv_file)
                    <div class="d-grid gap-2">
                        <a href="{{ Storage::url($profile->cv_file) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i> View CV</a>
                        <a href="{{ Storage::url($profile->cv_file) }}" download class="btn btn-primary btn-sm"><i class="bi bi-download"></i> Download CV</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Informasi Utama</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $profile->name ?? '') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul / Profesi</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $profile->title ?? '') }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tagline</label>
                        <input type="text" class="form-control @error('tagline') is-invalid @enderror" name="tagline" value="{{ old('tagline', $profile->tagline ?? '') }}" placeholder="Motto atau kalimat singkat">
                        @error('tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Tentang Saya</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Bio Singkat</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" rows="3">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Lengkap</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="5">{{ old('description', $profile->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Kontak & Lokasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $profile->email ?? '') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $profile->phone ?? '') }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">WhatsApp (Gunakan format kode negara, cth: 628...)</label>
                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" name="whatsapp" value="{{ old('whatsapp', $profile->whatsapp ?? '') }}">
                            @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location', $profile->location ?? '') }}" placeholder="Kota, Negara">
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Foto & CV</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Foto Profil (JPG/PNG/WEBP, Maks 2MB)</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/jpeg,image/png,image/webp">
                        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Abaikan jika tidak ingin mengganti foto.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File CV (PDF, Maks 5MB)</label>
                        <input type="file" class="form-control @error('cv_file') is-invalid @enderror" name="cv_file" accept=".pdf">
                        @error('cv_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Abaikan jika tidak ingin mengganti CV.</div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $profile ? 'Simpan Perubahan' : 'Buat Profil' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
