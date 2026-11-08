@extends('layouts.auth')

@section('title', 'Login Admin')

@section('content')
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 bg-white brutal-border brutal-shadow">
        <aside class="bg-navy-blue text-white p-6 sm:p-10 md:p-12 flex flex-col justify-between gap-8 border-b-[3px] md:border-b-0 md:border-r-[3px] border-black">
            <div>
                <span class="inline-flex items-center gap-2 bg-white text-black brutal-border px-3 py-1 font-bold text-xs uppercase tracking-widest">
                    <i class="bi bi-person-lock" aria-hidden="true"></i> Area Admin
                </span>
                <h2 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-bold uppercase tracking-tighter leading-tight">
                    Kelola<br class="hidden md:block"> karya Anda.
                </h2>
                <p class="mt-4 text-sm sm:text-base leading-relaxed text-blue-100 max-w-sm">
                    Satu tempat untuk memperbarui profil, menampilkan proyek, dan membagikan cerita terbaru.
                </p>
            </div>

            <div class="hidden md:block border-t-[3px] border-white/30 pt-6">
                <div class="w-14 h-14 bg-white text-black brutal-border flex items-center justify-center text-2xl mb-4" aria-hidden="true">
                    <i class="bi bi-grid-1x2"></i>
                </div>
                <p class="font-bold uppercase tracking-widest text-xs">{{ $profile->name ?? 'Portfolio' }}</p>
                <p class="mt-2 text-sm text-blue-100">Ruang di balik setiap karya.</p>
            </div>
        </aside>

        <section aria-labelledby="login-heading" class="p-6 sm:p-10 md:p-12">
            <div class="mb-8">
                <p class="font-bold uppercase text-xs tracking-widest text-navy-blue mb-3">Selamat datang kembali</p>
                <h1 id="login-heading" class="text-3xl sm:text-4xl font-bold uppercase tracking-tighter leading-tight">Masuk ke admin.</h1>
                <p class="mt-3 text-sm text-gray-600 leading-relaxed">Gunakan email dan password akun Anda.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block mb-2 font-bold uppercase tracking-widest text-xs">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" @error('email') aria-describedby="email-error" @enderror
                        placeholder="nama@email.com"
                        class="block w-full min-w-0 bg-[#f4f4f0] border-[3px] @error('email') border-red-700 @else border-black @enderror px-4 py-3 text-base font-medium rounded-none focus:outline-2 focus:outline-offset-2 focus:outline-blue-900">
                    @error('email')
                        <p id="email-error" class="mt-2 text-sm font-bold text-red-700" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block mb-2 font-bold uppercase tracking-widest text-xs">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" @error('password') aria-describedby="password-error" @enderror
                        placeholder="Masukkan password"
                        class="block w-full min-w-0 bg-[#f4f4f0] border-[3px] @error('password') border-red-700 @else border-black @enderror px-4 py-3 text-base font-medium rounded-none focus:outline-2 focus:outline-offset-2 focus:outline-blue-900">
                    @error('password')
                        <p id="password-error" class="mt-2 text-sm font-bold text-red-700" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <label for="remember" class="inline-flex min-h-11 items-center gap-3 cursor-pointer text-sm font-bold">
                    <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))
                        class="h-5 w-5 accent-blue-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">
                    <span>Ingat saya</span>
                </label>

                <button type="submit" class="w-full min-h-12 flex justify-center items-center gap-3 bg-navy-blue text-white brutal-border brutal-shadow-hover px-5 py-3 font-bold text-sm uppercase tracking-widest focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue-900">
                    Masuk <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </button>
            </form>
        </section>
    </div>
@endsection
