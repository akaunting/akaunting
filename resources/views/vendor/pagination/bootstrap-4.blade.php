@if ($paginator->hasPages())
    <ul class="pagination flex items-center justify-end text-black text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="font-medium disabled ltr:mr-2 rtl:ml-2">
                <button disabled class="material-icons page-link flex">chevron_left</button>
            </li>
        @else
            <li class="font-medium ltr:mr-2 rtl:ml-2"><a class="page-link flex" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <span class="material-icons px-3 py-1 rounded-lg hover:bg-lilac-300">chevron_left</span>
            </a></li>
        @endif

        @if($paginator->currentPage() > 3)
            <li><a class="page-link font-medium d-none d-sm-block ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple" href="{{ $paginator->url(1) }}">1</a></li>
        @endif
        @if($paginator->currentPage() > 4)
            <li><span class="page-link flex font-medium ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple">...</span></li>
        @endif
        @foreach(range(1, $paginator->lastPage()) as $i)
            @if($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                @if ($i == $paginator->currentPage())
                    <li><button class="page-link font-medium ltr:mr-2 rtl:ml-2 active bg-lilac-300 text-purple px-3 py-1 rounded-lg">{{ $i }}</span></li>
                @else
                    <li><a class="page-link font-medium ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <li><span class="page-link flex font-medium ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple">...</span></li>
        @endif
        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <li><a class="page-link font-medium ltr:mr-2 rtl:ml-2 px-3 py-1 rounded-lg hover:bg-lilac-300 hover:text-purple hidden sm-block" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="font-medium"><a class="page-link flex" href="{{ $paginator->nextPageUrl() }}" rel="next">
                <span class="material-icons px-3 py-1 rounded-lg hover:bg-lilac-300">chevron_right</span>
            </a></li>
        @else
            <li class="font-medium disabled"><button disabled class="page-link flex">
                <span class="material-icons">chevron_right</span>
            </button></li>
        @endif
    </ul>
@endif
