@if ($paginator->hasPages())
    <ul class="pagination flex items-center justify-end text-black text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="font-medium disabled ltr:mr-2 rtl:ml-2"><span class="page-link flex">
                <span class="material-icons">chevron_left</span>
            </span></li>
        @else
            <li class="font-medium ltr:mr-2 rtl:ml-2"><a class="page-link flex" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <span class="material-icons">chevron_left</span>
            </a></li>
        @endif

        @if($paginator->currentPage() > 3)
            <li class="font-medium d-none d-sm-block ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
        @endif
        @if($paginator->currentPage() > 4)
            <li class="font-medium ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple"><span class="page-link flex">...</span></li>
        @endif
        @foreach(range(1, $paginator->lastPage()) as $i)
            @if($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                @if ($i == $paginator->currentPage())
                    <li class="font-medium ltr:mr-2 rtl:ml-2 active bg-lilac-300 text-purple px-3 py-1 rounded-lg"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="font-medium ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <li class="font-medium ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple"><span class="page-link flex">...</span></li>
        @endif
        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <li class="font-medium ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple hidden sm-block"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="font-medium"><a class="page-link flex" href="{{ $paginator->nextPageUrl() }}" rel="next">
                <span class="material-icons">chevron_right</span>
            </a></li>
        @else
            <li class="font-medium disabled"><span class="page-link flex">
                <span class="material-icons">chevron_right</span>
            </span></li>
        @endif
    </ul>
@endif
