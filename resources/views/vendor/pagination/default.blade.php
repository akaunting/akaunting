<ul class="pagination justify-content-end mb-0">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <li class="font-medium mr-4 disabled">
            <a class="page-link" href="#" tabindex="-1">
                <span class="material-icons">chevron_left</span>
            </a>
        </li>
    @else
        <li class="font-medium mr-4"><a class="page-link" tabindex="-1" href="{{ $paginator->previousPageUrl() }}" rel="prev">
            <span class="material-icons">chevron_left</span>
        </a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li class="font-medium mr-4 disabled"><a class="page-link" href="#" tabindex="-1">{{ $element }}</a></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="font-medium mr-4 active"><a class="page-link" href="#" tabindex="-1">{{ $page }}</a></li>
                @else
                    <li class="font-medium mr-4"><a class="page-link" tabindex="-1" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <li class="font-medium mr-4"><a class="page-link" tabindex="-1" href="{{ $paginator->nextPageUrl() }}" rel="next">
            <span class="material-icons">chevron_right</span>
        </a></li>
    @else
        <li class="font-medium mr-4 disabled">
            <a class="page-link" href="#" tabindex="-1">
                <span class="material-icons">chevron_right</span>
            </a>
        </li>
    @endif
</ul>
