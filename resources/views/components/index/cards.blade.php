@if ($bulkAction || $sortable)
    <div class="flex flex-wrap items-center justify-between py-4">
        @if ($bulkAction)
            <div class="flex items-center leading-3">
                <x-index.bulkaction.all />

                {{-- The box on its own says nothing about what it would select, and the bar
                     that names the selection only appears once something is in it --}}
                <label for="table-check-all" class="ltr:ml-2 rtl:mr-2 text-sm text-black-400 cursor-pointer">
                    {{ trans('general.select_all') }}
                </label>
            </div>
        @endif

        @if ($sortable)
            @if ($sortStyle === 'inline')
                <div class="flex flex-wrap items-center">
                    <span class="text-sm text-black-400 ltr:mr-2 rtl:ml-2">
                        {{ trans('general.sort') }}
                    </span>

                    @foreach ($sortable as $column => $title)
                        <div @class([
                            'flex items-center px-3 h-9 rounded-xl text-sm',
                            'bg-gray-100 text-purple font-medium' => $sortColumn === (string) $column,
                            'text-black-400 hover:bg-gray-100' => $sortColumn !== (string) $column,
                        ])>
                            <x-sortablelink column="{{ $column }}" title="{{ $title }}" />
                        </div>
                    @endforeach
                </div>
            @else
                <x-dropdown id="{{ $sortId }}">
                    <x-slot name="trigger" class="flex items-center px-3 h-9 rounded-xl hover:bg-gray-100 text-sm text-purple" override="class">
                        <span class="material-icons-outlined text-lg ltr:mr-1 rtl:ml-1 pointer-events-none">sort</span>

                        {{ trans('general.sort') }}

                        {{-- What the page is sorted by belongs on the button, or the only way
                             to read it is to open the menu --}}
                        @if ($sortTitle)
                            <span class="font-medium ltr:ml-1 rtl:mr-1 pointer-events-none">
                                {{ $sortTitle }}
                            </span>
                        @endif
                    </x-slot>

                    @foreach ($sortable as $column => $title)
                        <div @class([
                            'w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap hover:bg-lilac-100',
                            'text-purple font-medium' => $sortColumn === (string) $column,
                            'text-purple' => $sortColumn !== (string) $column,
                        ])>
                            <x-sortablelink column="{{ $column }}" title="{{ $title }}" />
                        </div>
                    @endforeach
                </x-dropdown>
            @endif
        @endif
    </div>
@endif

<div class="grid grid-cols-1 {{ $gridClass }} gap-4">
    {!! $slot !!}
</div>
