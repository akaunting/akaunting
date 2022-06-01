<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.currencies', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.currencies', 2) }}"
        icon="paid"
        route="currencies.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-settings-currencies')
            <x-link href="{{ route('currencies.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.currencies', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        <x-index.container>
            <x-index.search
                search-string="App\Models\Setting\Currency"
                bulk-action="App\BulkActions\Settings\Currencies"
            />

            <x-table>
                <x-table.thead>
                    <x-table.tr class="flex items-center px-1">
                        <x-table.th class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                            <x-index.bulkaction.all />
                        </x-table.th>

                        <x-table.th class="w-6/12 sm:w-4/12">
                            <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                        </x-table.th>

                        <x-table.th class="w-6/12 sm:w-3/12">
                            <x-sortablelink column="code" title="{{ trans('currencies.code') }}" />
                        </x-table.th>

                        <x-table.th class="w-3/12 hidden sm:table-cell">
                            {{ trans('currencies.symbol.symbol') }}
                        </x-table.th>

                        <x-table.th class="w-2/12">
                            <x-sortablelink column="type" title="{{ trans('currencies.rate') }}" />
                        </x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @foreach($currencies as $item)
                        <x-table.tr href="{{ route('currencies.edit', $item->id) }}">
                            <x-table.td class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                            </x-table.td>

                            <x-table.td class="w-6/12 sm:w-4/12">
                                <x-slot name="first" class="flex" override="class">
                                    <div class="font-bold truncate">
                                        {{ $item->name }}
                                    </div>

                                    @if (! $item->enabled)
                                        <x-index.disable text="{{ trans_choice('general.currencies', 1) }}" />
                                    @endif

                                    @if ($item->code == setting("default.currency"))
                                        <x-index.default text="{{ trans('currencies.default') }}" />
                                    @endif
                                </x-slot>
                            </x-table.td>

                            <x-table.td class="w-6/12 sm:w-3/12 truncate">
                                {{ $item->code }}
                            </x-table.td>

                            <x-table.td class="w-3/12 hidden sm:table-cell">
                                {{ $item->symbol }}
                            </x-table.td>

                            <x-table.td class="w-2/12 relative truncate">
                                {{ $item->rate }}
                            </x-table.td>

                            <x-table.td kind="action">
                                <x-table.actions :model="$item" />
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table.tbody>
            </x-table>

            <x-pagination :items="$currencies" />
        </x-index.container>
    </x-slot>

    <x-script folder="settings" file="currencies" />
</x-layouts.admin>
