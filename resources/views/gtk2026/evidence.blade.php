<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Bukti Privat GTK 2026</title>@vite(['resources/css/gtk2026.css', 'resources/js/gtk2026.js'])</head>
<body class="gtk-page gtk-private"><main class="gtk-private-shell"><a class="gtk-button" href="{{ route('gtk2026') }}">← Kembali ke presentasi</a><p class="gtk-eyebrow">AKSES ADMIN</p><h1>Bukti privat GTK 2026</h1><p>Berkas pada halaman ini hanya tersedia setelah login. Gunakan dokumentasi yang sudah disamarkan saat berbagi layar.</p>
@if(session('status'))<p class="gtk-upload-feedback" role="status">{{ session('status') }}</p>@endif
@if($errors->has('file') || $errors->has('files') || $errors->has('files.*'))<p class="gtk-upload-feedback gtk-upload-error" role="alert">{{ $errors->first('file') ?: ($errors->first('files') ?: $errors->first('files.*')) }}</p>@endif
@foreach($evidence as $id => $item)<article class="gtk-private-card"><span class="gtk-card-label">{{ $item['category'] }}</span><h2>{{ $item['title'] }}</h2><p>{{ $item['summary'] }}</p>
@if($item['available'])
    @if($item['kind'] === 'youtube')
        @include('gtk2026.testimonial-links')
    @elseif($item['kind'] === 'timeline')
        <ol class="gtk-evidence-timeline">@foreach($gtk['timeline'] as $point)<li><strong>{{ $point['year'] }} · {{ $point['title'] }}</strong><p>{{ $point['text'] }}</p></li>@endforeach</ol>
    @else
        @php($mediaFiles = !empty($item['files']) ? $item['files'] : [['url' => $item['url'], 'type' => $item['type'], 'is_uploaded' => false, 'file' => null]])
        <div class="gtk-evidence-media-gallery" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; margin-top: 14px; margin-bottom: 14px;">
            @foreach($mediaFiles as $mediaFile)
                <div class="gtk-evidence-media-item" style="border: 1px solid var(--gtk-line, #2d425b); border-radius: 6px; padding: 10px; background: rgba(16, 37, 62, 0.6); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        @if(Str::startsWith($mediaFile['type'] ?? '', 'image/'))
                            <a href="{{ $mediaFile['url'] }}" target="_blank" rel="noopener" aria-label="Buka gambar {{ $item['title'] }} dalam ukuran penuh">
                                <img src="{{ $mediaFile['url'] }}" alt="Dokumentasi {{ $item['title'] }}" loading="lazy" decoding="async" style="width: 100%; height: 160px; object-fit: cover; border-radius: 4px; display: block;">
                            </a>
                        @elseif(($mediaFile['type'] ?? '') === 'video/mp4')
                            <video src="{{ $mediaFile['url'] }}" controls preload="none" aria-label="Video {{ $item['title'] }}" style="width: 100%; max-height: 180px; border-radius: 4px;"></video>
                        @else
                            <div style="padding: 2rem 1rem; text-align: center;">
                                <a class="gtk-button" href="{{ $mediaFile['url'] }}" target="_blank" rel="noopener">Buka PDF ↗</a>
                            </div>
                        @endif
                    </div>
                    @if(!empty($mediaFile['is_uploaded']) && !empty($mediaFile['file']))
                        <form method="POST" action="{{ route('gtk2026.evidence.delete', $id) }}" onsubmit="return confirm('Hapus file foto ini?')" style="margin-top: 8px; text-align: right;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="file" value="{{ $mediaFile['file'] }}">
                            <button type="submit" style="background: none; border: none; color: #f87171; font-size: 11px; cursor: pointer; text-decoration: underline; padding: 0;">
                                Hapus berkas ini
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@else
    <p class="gtk-muted">Belum tersedia</p>
@endif

@if(!in_array($item['kind'], ['timeline', 'youtube'], true))
    <details class="gtk-evidence-upload">
        <summary>Unggah / tambah bukti (bisa beberapa foto sekaligus)</summary>
        <form method="POST" action="{{ route('gtk2026.evidence.upload', $id) }}" enctype="multipart/form-data">
            @csrf
            <label for="evidence-file-{{ $id }}">Pilih satu atau beberapa gambar, PDF, atau MP4</label>
            <input id="evidence-file-{{ $id }}" type="file" name="files[]" accept=".jpg,.jpeg,.png,.webp,.pdf,.mp4" multiple required>
            <div style="margin-top: 8px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <button class="gtk-button" type="submit">Simpan bukti privat</button>
                <small style="color: var(--gtk-muted, #94a3b8);">Bisa pilih banyak foto sekaligus (tekan Ctrl / Cmd saat memilih file). Maksimal 20 MB per file.</small>
            </div>
        </form>
    </details>
@endif
</article>@endforeach
</main></body></html>
