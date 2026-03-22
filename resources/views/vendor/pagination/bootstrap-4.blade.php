@if ($paginator->hasPages())
    @php
        $paginationItemClass = 'page-link font-medium ltr:mr-2 rtl:ml-2 w-10 h-10 inline-flex items-center justify-center rounded-lg';
        $paginationLastItemClass = 'page-link font-medium w-10 h-10 inline-flex items-center justify-center rounded-lg';
    @endphp

    <ul class="pagination flex items-center justify-end text-black text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="font-medium disabled">
                <button disabled class="{{ $paginationItemClass }}">
                    <span class="material-icons rtl:rotate-180">chevron_left</span>
                </button>
            </li>
        @else
            <li class="font-medium">
                <a class="{{ $paginationItemClass }} hover:bg-lilac-300 hover:text-purple" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <span class="material-icons rtl:rotate-180">chevron_left</span>
                </a>
            </li>
        @endif

        @if ($paginator->currentPage() > 3)
            <li><a class="{{ $paginationItemClass }} d-none d-sm-block hover:bg-lilac-300 hover:text-purple" href="{{ $paginator->url(1) }}">1</a></li>
        @endif

        @if ($paginator->currentPage() > 4)
            <li><span class="{{ $paginationItemClass }} hover:bg-lilac-300 hover:text-purple">...</span></li>
        @endif

        @foreach (range(1, $paginator->lastPage()) as $i)
            @if ($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                @if ($i == $paginator->currentPage())
                    <li><button class="{{ $paginationItemClass }} active bg-lilac-300 text-purple">{{ $i }}</button></li>
                @else
                    <li><a class="{{ $paginationItemClass }} hover:bg-lilac-300 hover:text-purple" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach

        @if ($paginator->currentPage() < $paginator->lastPage() - 3)
            <li><span class="{{ $paginationItemClass }} hover:bg-lilac-300 hover:text-purple">...</span></li>
        @endif

        @if ($paginator->currentPage() < $paginator->lastPage() - 2)
            <li><a class="{{ $paginationItemClass }} hidden sm-block hover:bg-lilac-300 hover:text-purple" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="font-medium">
                <a class="{{ $paginationLastItemClass }} hover:bg-lilac-300 hover:text-purple" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <span class="material-icons rtl:rotate-180">chevron_right</span>
                </a>
            </li>
        @else
            <li class="font-medium disabled">
                <button disabled class="{{ $paginationLastItemClass }}">
                    <span class="material-icons rtl:rotate-180">chevron_right</span>
                </button>
            </li>
        @endif
    </ul>
@endif