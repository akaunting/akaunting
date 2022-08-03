<div class="mt-5 sm:mt-12">
    <div class="lg:flex lg:items-center">
        @stack('pagination_start')

        @if ($items->firstItem())
            <div class="w-full sm:w-6/12 flex items-center">
                <div class="flex items-center text-sm text-black">
                    <x-dropdown id="pagination-limit" style="left:auto;">
                        <x-slot name="trigger" class="p-0 mt-0 mx-1 bg-transparent text-sm text-center border-b border-black rounded-none" override="class">
                            {{ request('limit', setting('default.list_limit', '25')) }}
                        </x-slot>

                        @foreach($limits as $item)
                            <x-link href="javascript:;" @click="onChangePaginationLimit($event)" value="{{ $item }}"
                                class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap"
                                override="class"
                            >
                                <span class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100" value="{{ $item }}">
                                    {{ $item }}
                                </span>
                            </x-link>
                        @endforeach
                    </x-dropdown>

                    <span class="flex items-center">
                        {{ trans('pagination.page') }}
                        {{ trans('pagination.showing', ['first' => $items->firstItem(), 'last' => $items->lastItem(), 'total' => $items->total()]) }}
                    </span>
                </div>
            </div>

            <div class="w-full sm:w-6/12 pagination-xs">
                {!! $items->withPath(request()->url())->withQueryString()->links() !!}
            </div>
        @else
            <div id="datatable-basic_info" role="status" aria-live="polite">
                <small>{{ trans('general.no_records') }}</small>
            </div>
        @endif

        @stack('pagination_end')
    </div>
</div>
