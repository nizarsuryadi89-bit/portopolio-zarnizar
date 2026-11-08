<figure class="gtk-video-card" data-gtk-video>
    <div class="gtk-video-screen">
        <button class="gtk-video-load" type="button" data-video-play disabled aria-label="Putar {{ $video['title'] }} di presentasi">
            <span class="gtk-video-play-mark" aria-hidden="true">▶</span>
            <strong>{{ $video['title'] }}</strong>
            <span>Putar di slide · Mulai {{ $video['start'] }}</span>
        </button>
        <iframe title="{{ $video['title'] }}" data-embed-src="{{ $video['embed_url'] }}" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen hidden></iframe>
    </div>
    <figcaption><strong>{{ $video['title'] }}</strong><a href="{{ $video['url'] }}" target="_blank" rel="noopener noreferrer">Buka di YouTube ↗</a></figcaption>
</figure>
