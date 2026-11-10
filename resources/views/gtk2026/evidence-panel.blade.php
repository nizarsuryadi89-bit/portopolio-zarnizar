<dialog class="gtk-evidence-dialog" data-evidence-dialog aria-labelledby="evidence-title">
    <div class="gtk-evidence-header"><div><p class="gtk-eyebrow">ARSIP PERJALANAN</p><h2 id="evidence-title">Bukti Pendukung<span>.</span></h2></div><button type="button" class="gtk-icon-button" data-evidence-close aria-label="Tutup bukti dan kembali ke slide"><x-gtk-icon name="close" /></button></div>
    <p class="gtk-evidence-intro">Buka dokumentasi tanpa meninggalkan alur presentasi.</p>
    <label class="gtk-search"><x-gtk-icon name="search" /><input type="search" data-evidence-search placeholder="Cari bukti…" aria-label="Cari bukti pendukung" autocomplete="off"><kbd>Esc</kbd></label>
    <div class="gtk-evidence-layout"><nav class="gtk-evidence-list" aria-label="Daftar bukti">@foreach($evidence as $id => $item)<button type="button" class="gtk-evidence-item" data-evidence-item="{{ $id }}" aria-controls="evidence-{{ $id }}" aria-pressed="false" data-search="{{ Str::lower($item['title'].' '.$item['category'].' '.$id) }}"><x-gtk-icon :name="$item['icon']" /><span><strong>{{ $item['title'] }}</strong><small>{{ $item['category'] }}</small></span><span class="gtk-evidence-item-arrow">↗</span></button>@endforeach<p data-no-results hidden>Tidak ada bukti yang cocok.</p></nav>
    <div class="gtk-evidence-detail">@foreach($evidence as $id => $item)<article id="evidence-{{ $id }}" data-evidence-detail="{{ $id }}" hidden tabindex="-1"><span class="gtk-card-label">{{ $item['category'] }}</span><h3>{{ $item['title'] }}</h3><p>{{ $item['summary'] }}</p>
        @if($item['kind'] === 'timeline')
            <ol class="gtk-evidence-timeline">@foreach($gtk['timeline'] as $point)<li><strong>{{ $point['year'] }} · {{ $point['title'] }}</strong><p>{{ $point['text'] }}</p></li>@endforeach</ol><p class="gtk-evidence-caption">Narasi perjalanan sesuai brief peserta.</p>
        @endif
        @if($item['available'])
            @if($item['kind'] === 'youtube')@include('gtk2026.testimonial-links')
            @elseif(Str::startsWith($item['type'], 'image/'))
                @if(count($item['files'] ?? []) > 1)
                    <div class="gtk-evidence-dialog-gallery" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; margin-top: 10px;">
                        @foreach($item['files'] as $f)
                            <a href="{{ $f['url'] }}" target="_blank" rel="noopener" aria-label="Buka gambar {{ $item['title'] }} dalam ukuran penuh">
                                <img data-src="{{ $f['url'] }}" src="{{ $f['url'] }}" alt="Dokumentasi {{ $item['title'] }}" loading="lazy" decoding="async" style="width: 100%; height: 130px; object-fit: cover; border-radius: 4px; display: block;">
                            </a>
                        @endforeach
                    </div>
                @else
                    <a href="{{ $item['url'] }}" target="_blank" rel="noopener" aria-label="Buka gambar {{ $item['title'] }} dalam ukuran penuh"><img data-src="{{ $item['url'] }}" alt="Dokumentasi {{ $item['title'] }}" loading="lazy" decoding="async"></a>
                @endif
            @elseif($item['type'] === 'video/mp4')<video controls preload="none" data-video-src="{{ $item['url'] }}" aria-label="Video {{ $item['title'] }}"></video>
            @else<a class="gtk-button gtk-primary" href="{{ $item['url'] }}" target="_blank" rel="noopener">Buka dokumen PDF <x-gtk-icon name="arrow-right" /></a>@endif
        @elseif($item['kind'] !== 'timeline')<div class="gtk-evidence-empty"><x-gtk-icon :name="$item['icon']" /><strong>Belum tersedia</strong><p>Dokumentasi belum dilampirkan untuk tampilan ini.</p></div>@endif
    </article>@endforeach</div></div>
    <div class="gtk-evidence-footer"><span>⌘ / Ctrl + K untuk mencari bukti</span>@auth<a href="{{ route('gtk2026.presenter', ['present' => 1]) }}">Presentasi lengkap ↗</a><a href="{{ route('gtk2026.evidence') }}" target="_blank" rel="noopener">Bukti privat ↗</a>@endauth</div>
</dialog>
