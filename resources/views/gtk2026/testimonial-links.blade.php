<div class="gtk-inline-video-list" role="group" aria-label="Video {{ $item['title'] }}">
    @foreach($item['videos'] as $video)
        @include('gtk2026.video-player')
    @endforeach
</div>
<p class="gtk-evidence-caption">Pemutaran video memerlukan koneksi internet.</p>
