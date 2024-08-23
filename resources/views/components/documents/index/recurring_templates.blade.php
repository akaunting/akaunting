<x-table>
    <x-table.thead>
        <x-table.tr>
            @if (! $hideBulkAction)
            <x-table.th class="{{ $classBulkAction }}" hidden-mobile override="class">
                <x-index.bulkaction.all />
            </x-table.th>
            @endif

            @stack('stated_at_and_ended_at_th_start')
            @if (! $hideStartedAt || ! $hideEndedAt)
            <x-table.th class="{{ $classStartedAtAndEndedAt }}">
                @stack('stated_at_th_start')
                @if (! $hideStartedAt)
                <x-slot name="first">
                    <x-sortablelink column="recurring.started_at" title="{{ trans('general.start_date') }}" />
                </x-slot>
                @endif
                @stack('stated_at_th_end')

                @stack('ended_at_th_start')
                @if (! $hideEndedAt)
                <x-slot name="second">
                    {{ trans('recurring.last_issued') }}
                </x-slot>
                @endif
                @stack('ended_at_th_end')
            </x-table.th>
            @endif
            @stack('stated_at_and_ended_at_th_end')

            @stack('contact_name_and_category_th_start')
            @if (! $hideContactName || ! $hideCategory)
            <x-table.th class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider" hidden-mobile>
                @stack('contact_name_th_start')
                @if (! $hideContactName)
                <x-slot name="first">
                    <x-sortablelink column="contact_name" title="{{ trans_choice($textContactName, 1) }}" />
                </x-slot>
                @endif
                @stack('contact_name_th_end')

                @stack('category_th_start')
                @if (! $hideCategory)
                <x-slot name="second">
                    <x-sortablelink column="category.name" title="{{ trans_choice($textCategory, 1) }}" />
                </x-slot>
                @endif
                @stack('category_th_end')
            </x-table.th>
            @endif
            @stack('contact_name_and_category_th_end')

            @stack('status_th_start')
            @if (! $hideStatus)
            <x-table.th class="{{ $classStatus }}">
                @stack('status_th_inside_start')
                <x-sortablelink column="recurring.status" title="{{ trans_choice('general.statuses', 1) }}" />
                @stack('status_th_inside_end')
            </x-table.th>
            @endif
            @stack('status_th_end')

            @stack('frequency_and_duration_th_start')
            @if (! $hideFrequency || ! $hideDuration)
            <x-table.th class="{{ $classFrequencyAndDuration }}" hidden-mobile>
                @stack('frequency_th_start')
                @if (! $hideFrequency)
                <x-slot name="first">
                    {{ trans('recurring.frequency') }}
                </x-slot>
                @endif
                @stack('frequency_th_end')

                @stack('duration_th_start')
                @if (! $hideDuration)
                <x-slot name="second">
                    {{ trans('recurring.duration') }}
                </x-slot>
                @endif
                @stack('duration_th_end')
            </x-table.th>
            @endif
            @stack('frequency_and_duration_th_end')

            @stack('amount_th_start')
            @if (! $hideAmount)
            <x-table.th class="{{ $classAmount }}" kind="amount">
                @stack('amount_th_inside_start')
                <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                @stack('amount_th_inside_end')
            </x-table.th>
            @endif
            @stack('amount_th_end')
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

                @stack('stated_at_and_ended_at_td_start')
                @if (! $hideStartedAt || ! $hideEndedAt)
                <x-table.td class="{{ $classStartedAtAndEndedAt }}">
                    @stack('stated_at_td_start')
                    @if (! $hideStartedAt)
                    <x-slot name="first" class="font-bold">
                        <x-date date="{{ $item->recurring->started_at }}" />
                    </x-slot>
                    @endif
                    @stack('stated_at_td_end')

                    @stack('ended_at_td_start')
                    @if (! $hideEndedAt)
                    <x-slot name="second">
                        @if ($item->recurring->status == 'ended')
                            @if ($last = $item->recurring->documents->last()?->issued_at)
                                {{ $last->format(company_date_format()) }}
                            @else
                                <x-empty-data />
                            @endif
                        @else
                            @if ($last = $item->recurring->getLastRecurring())
                                {{ $last->format(company_date_format()) }}
                            @endif
                        @endif
                    </x-slot>
                    @endif
                    @stack('ended_at_td_end')
                </x-table.td>
                @endif
                @stack('stated_at_and_ended_at_td_end')

                @stack('contact_name_and_category_td_start')
                @if (! $hideContactName || ! $hideCategory)
                <x-table.td class="w-2/12" hidden-mobile>
                    @stack('contact_name_td_start')
                    @if (! $hideContactName)
                    <x-slot name="first">
                        {{ $item->contact_name }}
                    </x-slot>
                    @endif
                    @stack('contact_name_td_end')

                    @stack('category_td_start')
                    @if (! $hideCategory)
                    <x-slot name="second">
                        <div class="flex items-center">
                            <x-index.category :model="$item->category" />
                        </div>
                    </x-slot>
                    @endif
                    @stack('category_td_end')
                </x-table.td>
                @endif
                @stack('contact_name_and_category_td_end')

                @stack('status_td_start')
                @if (!$hideStatus)
                    <x-table.td class="{{ $classStatus }}">
                        @stack('status_td_inside_start')
                        <x-show.status
                            status="{{ $item->recurring->status }}" 
                            background-color="bg-{{ $item->recurring_status_label }}" 
                            text-color="text-text-{{ $item->recurring_status_label }}"
                        />
                        @stack('status_td_inside_end')
                    </x-table.td>
                @endif
                @stack('status_td_end')

                @stack('frequency_and_duration_td_start')
                @if (! $hideFrequency || ! $hideDuration)
                <x-table.td class="{{ $classFrequencyAndDuration }}" hidden-mobile>
                    @stack('frequency_td_start')
                    @if (! $hideFrequency)
                    <x-slot name="first">
                        @if ($item->recurring->interval > 1)
                            <x-tooltip 
                                id="tooltip-frequency-{{ $item->recurring->id }}"
                                placement="top"
                                message="{{ trans('recurring.custom_frequency_desc', [
                                    'interval' => $item->recurring->interval,
                                    'frequency' => str()->lower(trans('recurring.' . str_replace(['daily', 'ly'], ['days', 's'], $item->recurring->frequency)))
                                ]) }}"
                            >
                                {{ trans('recurring.custom') }}
                            </x-tooltip>
                        @else
                            {{ trans('recurring.' . $item->recurring->frequency) }}
                        @endif
                    </x-slot>
                    @endif
                    @stack('frequency_td_end')

                    @stack('duration_td_start')
                    @if (! $hideDuration)
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
                    @endif
                    @stack('duration_td_end')
                </x-table.td>
                @endif
                @stack('frequency_and_duration_td_end')

                @stack('amount_td_start')
                @if (! $hideAmount)
                <x-table.td class="{{ $classAmount }}" kind="amount">
                    @stack('amount_td_inside_start')
                    <x-money :amount="$item->amount" :currency="$item->currency_code" />
                    @stack('amount_td_inside_end')
                </x-table.td>
                @endif
                @stack('amount_td_end')

                <x-table.td kind="action">
                    <x-table.actions :model="$item" />
                </x-table.td>
            </x-table.tr>
        @endforeach
    </x-table.tbody>
</x-table>

<x-pagination :items="$documents" />
