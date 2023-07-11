<x-table>
    <x-table.thead>
        <x-table.tr>
            @if (! $hideBulkAction)
            <x-table.th class="{{ $classBulkAction }}" hidden-mobile override="class">
                <x-index.bulkaction.all />
            </x-table.th>
            @endif

            <x-table.th class="w-4/12 sm:w-3/12">
                <x-slot name="first">
                    <x-sortablelink column="recurring.started_at" title="{{ trans('general.start_date') }}" />
                </x-slot>
                <x-slot name="second">
                    {{ trans('recurring.last_issued') }}
                </x-slot>
            </x-table.th>

            <x-table.th class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider" hidden-mobile>
                <x-slot name="first">
                    <x-sortablelink column="contact_name" title="{{ trans_choice($textContactName, 1) }}" />
                </x-slot>
                <x-slot name="second">
                    <x-sortablelink column="category.name" title="{{ trans_choice('general.categories', 1) }}" />
                </x-slot>
            </x-table.th>

            <x-table.th class="w-4/12 sm:w-3/12">
                <x-sortablelink column="recurring.status" title="{{ trans_choice('general.statuses', 1) }}" />
            </x-table.th>

            <x-table.th class="w-2/12" hidden-mobile>
                <x-slot name="first">
                    {{ trans('recurring.frequency') }}
                </x-slot>
                <x-slot name="second">
                    {{ trans('recurring.duration') }}
                </x-slot>
            </x-table.th>

            <x-table.th class="w-4/12 sm:w-2/12" kind="amount">
                <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
            </x-table.th>
        </x-table.tr>
    </x-table.thead>

    <x-table.tbody>
        @foreach($documents as $item)
            <x-table.tr href="{{ route($showRoute, $item->id) }}">
            @if (! $hideBulkAction)
                <x-table.td class="ltr:pr-6 rtl:pl-6" hidden-mobile override="class">
                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->contact->name }}" />
                </x-table.td>
            @endif

                <x-table.td class="w-4/12 sm:w-3/12">
                    <x-slot name="first" class="font-bold">
                        <x-date date="{{ $item->recurring->started_at }}" />
                    </x-slot>
                    <x-slot name="second">
                        @if ($last = $item->recurring->getLastRecurring())
                            {{ $last->format(company_date_format()) }}
                        @endif
                    </x-slot>
                </x-table.td>

                <x-table.td class="w-2/12" hidden-mobile>
                    <x-slot name="first">
                        {{ $item->contact_name }}
                    </x-slot>
                    <x-slot name="second">
                        <div class="flex items-center">
                            <x-index.category :model="$item->category" />
                        </div>
                    </x-slot>
                </x-table.td>

                <x-table.td class="w-4/12 sm:w-3/12">
                    <x-index.status status="{{ $item->recurring->status }}" background-color="bg-{{ $item->recurring_status_label }}" text-color="text-text-{{ $item->recurring_status_label }}" />
                </x-table.td>

                <x-table.td class="w-2/12" hidden-mobile>
                    <x-slot name="first">
                        {{ trans('recurring.' . $item->recurring->frequency) }}
                    </x-slot>
                    <x-slot name="second">
                        @if ($item->recurring->limit_by == 'count')
                            @if ($item->recurring->limit_count == 0)
                                {{ trans('recurring.ends_never') }}
                            @else
                                {{ trans('recurring.ends_after', ['times' => $item->recurring->limit_count]) }}
                            @endif
                        @else
                            {{ trans('recurring.ends_date', ['date' => company_date($item->recurring->limit_date)]) }}
                        @endif
                    </x-slot>
                </x-table.td>

                <x-table.td class="w-4/12 sm:w-2/12" kind="amount">
                    <x-money :amount="$item->amount" :currency="$item->currency_code" />
                </x-table.td>

                <x-table.td kind="action">
                    <x-table.actions :model="$item" />
                </x-table.td>
            </x-table.tr>
        @endforeach
    </x-table.tbody>
</x-table>

<x-pagination :items="$documents" />
