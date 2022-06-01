<ul class="pagination justify-content-end mb-0">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <li class="font-medium mr-4 disabled">
            <a class="page-link" href="#" tabindex="-1">
                <span class="material-icons">chevron_left</span>
            </a>
        </li>
    @else
        <li class="font-medium mr-4">
            <button type="button" dusk="previousPage" class="page-link" wire:click="previousPage" wire:loading.attr="disabled" rel="prev" aria-label="@lang('pagination.previous')">
                <span class="material-icons">chevron_left</span>
            </button>
        </li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li class="font-medium mr-4 disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="font-medium mr-4 active" wire:key="paginator-page-{{ $page }}" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="font-medium mr-4" wire:key="paginator-page-{{ $page }}"><button type="button" class="page-link" wire:click="gotoPage({{ $page }})">{{ $page }}</button></li>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <li class="font-medium mr-4">
            <button type="button" dusk="nextPage" class="page-link" wire:click="nextPage" wire:loading.attr="disabled" rel="next" aria-label="@lang('pagination.next')">
                <span class="material-icons">chevron_right</span>
            </button>
        </li>
    @else
        <li class="font-medium mr-4 disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span class="page-link" aria-hidden="true">
                <span class="material-icons">chevron_right</span>
            </span>
        </li>
    @endif
</ul>
