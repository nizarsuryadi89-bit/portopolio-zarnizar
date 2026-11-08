@php echo '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach(['site.home', 'profil', 'pembelajaran.index', 'pembelajaran.asesmen', 'gtk2026', 'kulapos', 'services'] as $routeName)
        <url><loc>{{ route($routeName) }}</loc></url>
    @endforeach
    @for($id = 1; $id <= 4; $id++)
        <url><loc>{{ route('pembelajaran.pertemuan', $id) }}</loc></url>
    @endfor
    @foreach($posts as $post)
        <url><loc>{{ route('blog.show', $post->slug) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod></url>
    @endforeach
    @foreach($portfolios as $portfolio)
        <url><loc>{{ route('portfolio.show', $portfolio->slug) }}</loc><lastmod>{{ $portfolio->updated_at->toAtomString() }}</lastmod></url>
    @endforeach
</urlset>
