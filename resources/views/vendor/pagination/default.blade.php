<ul class="pagination justify-content-end mb-0">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">
                <i class="fas fa-angle-left"></i>
            </a>
        </li>
    @else
        <li class="page-item"><a class="page-link" tabindex="-1" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas fa-angle-left"></i></a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">{{ $element }}</a></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active"><a class="page-link" href="#" tabindex="-1">{{ $page }}</a></li>
                @else
                    <li class="page-item"><a class="page-link" tabindex="-1" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <li class="page-item"><a class="page-link" tabindex="-1" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fas fa-angle-right"></i></a></li>
    @else
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">
                <i class="fas fa-angle-right"></i>
            </a>
        </li>
    @endif
</ul>
