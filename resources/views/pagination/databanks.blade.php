@if($paginator->hasPages())
    <nav aria-label="Navigasi Databanks" class="flex flex-col sm:flex-row items-center justify-center gap-3 text-sm font-bold uppercase">
        @if($paginator->onFirstPage())
            <span aria-disabled="true" class="w-full sm:w-auto min-h-11 flex items-center justify-center px-4 py-2 brutal-border bg-gray-100 text-gray-500">← Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-full sm:w-auto min-h-11 flex items-center justify-center px-4 py-2 brutal-border bg-white hover:bg-black hover:text-white focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-blue-800 transition">← Sebelumnya</a>
        @endif

        <div class="flex flex-wrap justify-center gap-2">
            @foreach($elements as $element)
                @if(is_string($element))
                    <span aria-hidden="true" class="min-h-11 min-w-11 flex items-center justify-center px-2">{{ $element }}</span>
                @else
                    @foreach($element as $page => $url)
                        @if($page === $paginator->currentPage())
                            <span aria-current="page" aria-label="Halaman {{ $page }}, halaman saat ini" class="min-h-11 min-w-11 flex items-center justify-center px-3 py-2 brutal-border bg-navy-blue text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="Ke halaman {{ $page }}" class="min-h-11 min-w-11 flex items-center justify-center px-3 py-2 brutal-border bg-white hover:bg-black hover:text-white focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-blue-800 transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-full sm:w-auto min-h-11 flex items-center justify-center px-4 py-2 brutal-border bg-white hover:bg-black hover:text-white focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-blue-800 transition">Berikutnya →</a>
        @else
            <span aria-disabled="true" class="w-full sm:w-auto min-h-11 flex items-center justify-center px-4 py-2 brutal-border bg-gray-100 text-gray-500">Berikutnya →</span>
        @endif
    </nav>
@endif
