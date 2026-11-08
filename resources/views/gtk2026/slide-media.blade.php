@php($videoItem = $evidence[$videoEvidence])
@if($videoItem['available'] && $videoItem['videos'])
    <div class="gtk-slide-media" data-media-stage>
        <div class="gtk-media-switch gtk-js-only" role="group" aria-label="Pilihan media {{ $slide['label'] }}">
            <button type="button" data-media-tab="video" aria-pressed="false" aria-controls="{{ $slide['id'] }}-video">{{ $slide['id'] === 'ecosystem' ? 'Profil SASTI' : 'Video testimoni' }}</button>
            <button type="button" data-media-tab="documentation" aria-pressed="true" aria-controls="{{ $slide['id'] }}-documentation">Dokumentasi & data</button>
        </div>
        <div id="{{ $slide['id'] }}-video" data-media-panel="video" hidden>
            @if($slide['id'] === 'ecosystem')
                <div class="gtk-profile-video-grid">
                    <div class="gtk-statistics">@foreach($gtk['statistics'] as $stat)<div><strong>{{ $stat['value'] }}</strong><span>{{ $stat['label'] }}</span></div>@endforeach<span class="gtk-card-foot">Per {{ $gtk['statistics_date'] }}<br>Akses layanan melalui desktop dan mobile.</span></div>
                    <div class="gtk-inline-video-list">@foreach($videoItem['videos'] as $video)@include('gtk2026.video-player')@endforeach</div>
                </div>
            @else
                <p class="gtk-video-impact-summary"><strong>{{ $gtk['votes'] }}</strong> suara digital PILKETOS <span>· Suara dari sekolah</span></p>
                <div class="gtk-inline-video-list">@foreach($videoItem['videos'] as $video)@include('gtk2026.video-player')@endforeach</div>
            @endif
            <p class="gtk-video-connection">Klik putar untuk menonton di slide. Koneksi internet diperlukan.</p>
        </div>
        <div id="{{ $slide['id'] }}-documentation" data-media-panel="documentation">
            @include($documentationView)
        </div>
        <div class="gtk-print-videos">@foreach($videoItem['videos'] as $video)<a href="{{ $video['url'] }}">{{ $video['title'] }} · Mulai {{ $video['start'] }} ↗</a>@endforeach</div>
    </div>
@else
    @include($documentationView)
@endif
