@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="font-medium mr-4 disabled"><span class="page-link">@lang('pagination.previous')</span></li>
        @else
            <li class="font-medium mr-4"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="font-medium mr-4"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></li>
        @else
            <li class="font-medium mr-4 disabled"><span class="page-link">@lang('pagination.next')</span></li>
        @endif
    </ul>
@endif
