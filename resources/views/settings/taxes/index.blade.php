<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.tax_rates', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.tax_rates', 2) }}"
        icon="percent"
        route="tax_rates.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-settings-taxes')
            <x-link href="{{ route('taxes.create') }}" kind="primary" id="index-more-actions-new-tax">
                {{ trans('general.title.new', ['type' => trans_choice('general.taxes', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons pointer-events-auto">more_horiz</span>
            </x-slot>

            @can('create-settings-taxes')
                <x-dropdown.link href="{{ route('import.create', ['settings', 'taxes']) }}" id="index-more-actions-import-tax">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('taxes.export', request()->input()) }}" id="index-more-actions-export-tax">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        @if ($taxes->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search
                    search-string="App\Models\Setting\Tax"
                    bulk-action="App\BulkActions\Settings\Taxes"
                />

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th kind="bulkaction">
                                <x-index.bulkaction.all />
                            </x-table.th>

                            <x-table.th class="w-5/12">
                                <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                            </x-table.th>

                            <x-table.th class="w-4/12">
                                <x-sortablelink column="type" title="{{ trans_choice('general.types', 1) }}" />
                            </x-table.th>

                            <x-table.th class="w-3/12">
                                <x-sortablelink column="rate" title="{{ trans('taxes.rate_percent') }}" />
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($taxes as $item)
                            <x-table.tr href="{{ route('taxes.edit', $item->id) }}">
                                <x-table.td kind="bulkaction">
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                                </x-table.td>

                                <x-table.td class="w-5/12">
                                    <x-slot name="first" class="flex" override="class">
                                        <div class="font-bold truncate">
                                            {{ $item->name }}
                                        </div>

                                        @if (! $item->enabled)
                                            <x-index.disable text="{{ trans_choice('general.tax_rates', 1) }}" />
                                        @endif
                                    </x-slot>
                                </x-table.td>

                                <x-table.td class="w-4/12">
                                    {{ $types[$item->type] }}
                                </x-table.td>

                                <x-table.td class="w-3/12">
                                    {{ $item->rate }}
                                </x-table.td>

                                <x-table.td kind="action">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$taxes" />
            </x-index.container>
        @else
            <x-empty-page group="settings" page="taxes" />
        @endif
    </x-slot>

    <x-script folder="settings" file="taxes" />
</x-layouts.admin>
