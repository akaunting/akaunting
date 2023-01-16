<div>
    @if ($paginator->hasPages())
        <nav>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="font-medium mr-4 disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.previous')</span>
                    </li>
                @else
                    <li class="font-medium mr-4">
                        <button type="button" class="page-link" wire:click="previousPage" wire:loading.attr="disabled" rel="prev">@lang('pagination.previous')</button>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="font-medium mr-4">
                        <button type="button" class="page-link" wire:click="nextPage" wire:loading.attr="disabled" rel="next">@lang('pagination.next')</button>
                    </li>
                @else
                    <li class="font-medium mr-4 disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.next')</span>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>
