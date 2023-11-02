<x-table>
    <x-table.thead>
        <x-table.tr>
            @if (! $hideBulkAction)
            <x-table.th class="{{ $classBulkAction }}" override="class">
                <x-index.bulkaction.all />
            </x-table.th>
            @endif

            @stack('due_at_and_issued_at_th_start')
            @if (! $hideDueAt || ! $hideIssuedAt)
            <x-table.th class="{{ $classDueAtAndIssueAt }}">
                @stack('due_at_th_start')
                @if (! $hideDueAt)
                <x-slot name="first">
                    @stack('due_at_th_inside_start')
                    <x-sortablelink column="due_at" title="{{ trans($textDueAt) }}" />
                    @stack('due_at_th_inside_end')
                </x-slot>
                @endif
                @stack('due_at_th_end')

                @stack('issued_at_th_start')
                @if (! $hideIssuedAt)
                <x-slot name="second">
                    @stack('issued_at_th_inside_start')
                    <x-sortablelink column="issued_at" title="{{ trans($textIssuedAt) }}" />
                    @stack('issued_at_th_inside_end')
                </x-slot>
                @endif
                @stack('issued_at_th_end')
            </x-table.th>
            @endif
            @stack('due_at_and_issued_at_th_end')

            @stack('status_th_start')
            @if (! $hideStatus)
            <x-table.th class="{{ $classStatus }}">
                @stack('status_th_inside_start')
                <x-sortablelink column="status" title="{{ trans_choice('general.statuses', 1) }}" />
                @stack('status_th_inside_end')
            </x-table.th>
            @endif
            @stack('status_th_end')

            @stack('contact_name_ane_document_number_th_start')
            @if (! $hideContactName || ! $hideDocumentNumber)
            <x-table.th class="{{ $classContactNameAndDocumentNumber }}">
                @stack('contact_name_th_start')
                @if (! $hideContactName)
                <x-slot name="first">
                    @stack('contact_name_th_inside_start')
                    <x-sortablelink column="contact_name" title="{{ trans_choice($textContactName, 1) }}" />
                    @stack('contact_name_th_inside_end')
                </x-slot>
                @endif
                @stack('contact_name_th_end')

                @stack('document_number_th_start')
                @if (! $hideDocumentNumber)
                <x-slot name="second">
                    @stack('document_number_th_inside_start')
                    <x-sortablelink column="document_number" title="{{ trans_choice($textDocumentNumber, 1) }}" />
                    @stack('document_number_th_inside_end')
                </x-slot>
                @endif
                @stack('document_number_th_end')
            </x-table.th>
            @endif
            @stack('contact_name_ane_document_number_th_end')

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
            @php $paid = $item->paid; @endphp
            <x-table.tr href="{{ route($showRoute, $item->id) }}">
                @if (! $hideBulkAction)
                <x-table.td class="{{ $classBulkAction }}" override="class">
                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->document_number }}" />
                </x-table.td>
                @endif

                @stack('due_at_and_issued_at_td_start')
                @if (! $hideDueAt || ! $hideIssuedAt)
                <x-table.td class="{{ $classDueAtAndIssueAt }}">
                    @stack('due_at_td_start')
                    @if (! $hideDueAt)
                    <x-slot name="first" class="font-bold" override="class">
                        @stack('due_at_td_inside_start')
                        <x-date :date="$item->due_at" function="diffForHumans" />
                        @stack('due_at_td_inside_end')
                    </x-slot>
                    @endif
                    @stack('due_at_td_end')

                    @stack('issued_at_td_start')
                    @if (! $hideIssuedAt)
                    <x-slot name="second">
                        @stack('issued_at_td_inside_start')
                        <x-date date="{{ $item->issued_at }}" />
                        @stack('issued_at_td_inside_end')
                    </x-slot>
                    @endif
                    @stack('issued_at_td_end')
                </x-table.td>
                @endif
                @stack('due_at_and_issued_at_td_end')

                @stack('status_td_start')
                @if (!$hideStatus)
                    <x-table.td class="{{ $classStatus }}">
                        @stack('status_td_inside_start')
                        <x-show.status status="{{ $item->status }}" background-color="bg-{{ $item->status_label }}" text-color="text-text-{{ $item->status_label }}" />
                        @stack('status_td_inside_end')
                    </x-table.td>
                @endif
                @stack('status_td_end')

                @stack('contact_name_and_document_number_td_start')
                @if (! $hideContactName || ! $hideDocumentNumber)
                <x-table.td class="{{ $classContactNameAndDocumentNumber }}">
                    @stack('contact_name_td_start')
                    @if (! $hideContactName)
                    <x-slot name="first">
                        @stack('contact_name_td_inside_start')
                        {{ $item->contact_name }}
                        @stack('contact_name_td_inside_end')
                    </x-slot>
                    @endif
                    @stack('contact_name_td_end')

                    @stack('document_number_td_start')
                    @if (! $hideDocumentNumber)
                    <x-slot name="second" class="w-20 group" data-tooltip-target="tooltip-information-{{ $item->id }}" data-tooltip-placement="left" override="class">
                        @stack('document_number_td_inside_start')
                        <span class="border-black border-b border-dashed">
                            {{ $item->document_number }}
                        </span>

                        <div class="w-28 absolute h-10 -ml-12 -mt-6"></div>
                        @stack('document_number_td_inside_end')

                        <x-documents.index.information :document="$item" :hide-show="$hideShow" :show-route="$showContactRoute" />
                    </x-slot>
                    @endif
                    @stack('document_number_td_end')
                </x-table.td>
                @endif
                @stack('contact_name_and_document_number_td_end')

                @stack('amount_td_start')
                @if (! $hideAmount)
                <x-table.td class="{{ $classAmount }}" kind="amount">
                    @stack('amount_td_inside_start')
                    <x-money :amount="$item->amount" :currency="$item->currency_code" />
                    @stack('amount_td_inside_end')
                </x-table.td>

                <x-table.td kind="action">
                    <x-table.actions :model="$item" />
                </x-table.td>
                @endif
                @stack('amount_td_end')
            </x-table.tr>
        @endforeach
    </x-table.tbody>
</x-table>

<x-pagination :items="$documents" />
