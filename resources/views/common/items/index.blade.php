<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.items', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.items', 2) }}"
        icon="inventory_2"
        route="items.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-common-items')
            <x-link href="{{ route('items.create') }}" kind="primary" id="index-more-actions-new-item">
                {{ trans('general.title.new', ['type' => trans_choice('general.items', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons pointer-events-none">more_horiz</span>
            </x-slot>

            @can('create-common-items')
                <x-dropdown.link href="{{ route('import.create', ['common', 'items']) }}" id="index-more-actions-import-item">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('items.export', request()->input()) }}" id="index-more-actions-export-item">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        @if ($items->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search
                    search-string="App\Models\Common\Item"
                    bulk-action="App\BulkActions\Common\Items"
                />

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th kind="bulkaction">
                                <x-index.bulkaction.all />
                            </x-table.th>

                            <x-table.th class="w-6/12 sm:w-4/12">
                                <x-slot name="first">
                                    <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                                </x-slot>
                                <x-slot name="second">
                                    <x-sortablelink column="description" title="{{ trans('general.description') }}" />
                                </x-slot>
                            </x-table.th>

                            <x-table.th class="w-3/12" hidden-mobile>
                                <x-sortablelink column="category.name" title="{{ trans_choice('general.categories', 1) }}" />
                            </x-table.th>

                            <x-table.th class="w-2/12" hidden-mobile>
                                {{ trans_choice('general.taxes', 2) }}
                            </x-table.th>

                            <x-table.th class="w-6/12 sm:w-3/12" kind="amount">
                                <x-slot name="first">
                                    <x-sortablelink column="sale_price" title="{{ trans('items.sale_price') }}" />
                                </x-slot>
                                <x-slot name="second">
                                    <x-sortablelink column="purchase_price" title="{{ trans('items.purchase_price') }}" />
                                </x-slot>
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($items as $item)
                            <x-table.tr href="{{ route('items.edit', $item->id) }}">
                                <x-table.td kind="bulkaction">
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                                </x-table.td>

                                <x-table.td class="w-6/12 sm:w-4/12">
                                    <x-slot name="first" class="flex font-bold" override="class">
                                        {{ $item->name }}

                                        @if (! $item->enabled)
                                            <x-index.disable text="{{ trans_choice('general.items', 1) }}" />
                                        @endif
                                    </x-slot>
                                    <x-slot name="second" class="font-normal" override="class">
                                        {{ $item->description }}
                                    </x-slot>
                                </x-table.td>

                                <x-table.td class="w-3/12" hidden-mobile>
                                    <div class="flex items-center">
                                        <x-index.category :model="$item->category" />
                                    </div>
                                </x-table.td>

                                <x-table.td class="w-2/12" hidden-mobile>
                                    @if ($item->taxes->count())
                                        @foreach($item->taxes as $tax)
                                            <span class="bg-lilac-900 px-3 py-1 text-sm rounded-lg text-black ltr:mr-3 rtl:ml-3">
                                                {{ $tax->tax->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <x-empty-data />
                                    @endif
                                </x-table.td>

                                <x-table.td class="w-6/12 sm:w-3/12" kind="amount">
                                    <x-slot name="first">
                                        @if ($item->sale_price)
                                            <x-money :amount="$item->sale_price" />
                                        @else
                                            <x-empty-data />
                                        @endif
                                    </x-slot>
                                    <x-slot name="second">
                                        @if ($item->purchase_price)
                                            <x-money :amount="$item->purchase_price" />
                                        @else
                                            <x-empty-data />
                                        @endif
                                    </x-slot>
                                </x-table.td>

                                <x-table.td kind="action">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$items" />
            </x-index.container>
        @else
            <x-empty-page group="common" page="items" />
        @endif
    </x-slot>

    <x-script folder="common" file="items" />
</x-layouts.admin>
