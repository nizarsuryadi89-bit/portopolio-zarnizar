<div class="gtk-slide-gallery gtk-slide-gallery-{{ $slide['id'] }}" @if($slide['id'] === 'ecosystem') style="--gtk-gallery-columns: {{ count($documentation) }}" @endif role="group" aria-label="Dokumentasi {{ $slide['label'] }}">
    @foreach($documentation as $item)
        <figure class="gtk-documentation gtk-documentation-{{ $item['id'] }}">
            <button type="button" data-evidence-target="{{ $item['id'] }}" aria-label="Perbesar dokumentasi {{ $item['title'] }}">
                <img src="{{ $item['url'] }}" alt="{{ $item['title'] }}">
                <span class="gtk-documentation-expand" aria-hidden="true"><x-gtk-icon name="expand" /></span>
            </button>
            <figcaption>{{ $item['title'] }}@if($item['id'] === 'publication')<small>Sampul publikasi · 2024</small>@elseif($item['id'] === 'contributor')<small>Penyusunan naskah · 19 Desember 2024</small>@elseif(in_array($item['id'], ['sastiearly', 'siagaearly']))<small>Arsip tampilan · 2024</small>@elseif($item['id'] === 'pilketosarchive')<small>Arsip 2025 · 1.209 suara</small>@elseif($item['id'] === 'dashboard')<small>7 November 2026</small>@endif</figcaption>
        </figure>
    @endforeach
</div>
