@props(['posts'])
<section class="sales-section sales-white" aria-labelledby="related-heading">
    <div class="sales-shell">
        <p class="sales-eyebrow">Catatan & panduan</p>
        <h2 id="related-heading" class="sales-heading">Pahami dulu kebutuhannya.</h2>
        @if($posts->isNotEmpty())
            <div class="sales-grid sales-grid-three">
                @foreach($posts as $post)
                    <article class="sales-card">
                        <p class="sales-eyebrow">{{ $post->published_at?->format('d M Y') }}</p>
                        <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                        <p>{{ Str::limit(strip_tags($post->content), 150) }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="sales-text-link">Baca artikel →</a>
                    </article>
                @endforeach
            </div>
        @else
            <p class="sales-intro">Jelajahi catatan pengembangan dan pengalaman membangun sistem di blog Zarnizar.</p>
        @endif
        <a href="{{ route('site.home') }}#blog" class="sales-text-link">Lihat semua artikel →</a>
    </div>
</section>
