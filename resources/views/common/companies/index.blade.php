<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.companies', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.companies', 2) }}"
        icon="business"
        route="companies.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-common-companies')
            <x-link href="{{ route('companies.create') }}" kind="primary" id="index-more-actions-new-company">
                {{ trans('general.title.new', ['type' => trans_choice('general.companies', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        <x-index.container>
            <x-index.search
                search-string="App\Models\Common\Company"
                bulk-action="App\BulkActions\Common\Companies"
            />

            <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th kind="bulkaction">
                            <x-index.bulkaction.all />
                        </x-table.th>

                        <x-table.th class="w-2/12 sm:w-1/12">
                            <x-sortablelink column="id" title="{{ trans('general.id') }}" />
                        </x-table.th>

                        <x-table.th class="w-8/12 sm:w-4/12">
                            <x-slot name="first" class="flex items-center">
                                <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                            </x-slot>
                            <x-slot name="second">
                                <x-sortablelink column="tax_number" title="{{ trans('general.tax_number') }}" />
                            </x-slot>
                        </x-table.th>

                        <x-table.th class="w-4/12" hidden-mobile>
                            <x-slot name="first">
                                <x-sortablelink column="email" title="{{ trans('general.email') }}" />
                            </x-slot>
                            <x-slot name="second">
                                <x-sortablelink column="phone" title="{{ trans('general.phone') }}" />
                            </x-slot>
                        </x-table.th>

                        <x-table.th class="w-3/12" kind="right">
                            <x-slot name="first">
                                <x-sortablelink column="country" title="{{ trans_choice('general.countries', 1) }}" />
                            </x-slot>
                            <x-slot name="second">
                                <x-sortablelink column="currency" title="{{ trans_choice('general.currencies', 1) }}" />
                            </x-slot>
                        </x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @foreach($companies as $item)
                        <x-table.tr href="{{ route('companies.edit', $item->id) }}">
                            <x-table.td kind="bulkaction">
                                @if ((company_id() != $item->id))
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                                @else
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" disabled="true" />
                                @endif
                            </x-table.td>

                            <x-table.td class="w-2/12 sm:w-1/12">
                                {{ $item->id }}
                            </x-table.td>

                            <x-table.td class="w-8/12 sm:w-4/12">
                                <x-slot name="first" class="flex" override="class">
                                    <div class="font-bold truncate">
                                        {{ $item->name }}
                                    </div>

                                    @if (! $item->enabled)
                                        <x-index.disable text="{{ trans_choice('general.companies', 1) }}" />
                                    @endif
                                </x-slot>
                                <x-slot name="second">
                                    @if ($item->tax_number)
                                        {{ $item->tax_number }}
                                    @else
                                        <x-empty-data />
                                    @endif
                                </x-slot>
                            </x-table.td>

                            <x-table.td class="w-4/12" hidden-mobile>
                                <x-slot name="first">
                                    @if ($item->email)
                                        {{ $item->email }}
                                    @else
                                        <x-empty-data />
                                    @endif
                                </x-slot>
                                <x-slot name="second">
                                    @if ($item->phone)
                                        {{ $item->phone }}
                                    @else
                                        <x-empty-data />
                                    @endif
                                </x-slot>
                            </x-table.td>

                            <x-table.td class="w-3/12" kind="amount">
                                <x-slot name="first">
                                    @if ($item->country)
                                        <x-index.country code="{{ $item->country }}" />
                                    @else
                                        <x-empty-data />
                                    @endif
                                </x-slot>
                                <x-slot name="second">
                                    @if ($item->currency)
                                        <x-index.currency code="{{ $item->currency }}" />
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

            <x-pagination :items="$companies" />
        </x-index.container>
    </x-slot>

    <x-script folder="common" file="companies" />
</x-layouts.admin>
