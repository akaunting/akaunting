<x-layouts.portal>
    <x-slot name="title">
        {{ trans_choice('general.invoices', 2) }}
    </x-slot>

    <x-slot name="content">
        @if ($invoices->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search search-string="App\Models\Portal\Sale\Invoice" />

                <x-table>
                    <x-table.thead>
                        <x-table.tr class="flex items-center px-1">
                            <x-table.th override="class" class="p-0"></x-table.th>
                            @stack('issued_at_th_start')

                            <x-table.th class="w-4/12 hidden sm:table-cell">
                                @stack('due_at_th_inside_start')

                                <x-slot name="first">
                                    <x-sortablelink column="due_at" title="{{ trans('invoices.due_date') }}" />
                                </x-slot>

                                @stack('due_at_th_inside_end')

                                @stack('issued_at_th_inside_start')

                                <x-slot name="second">
                                    <x-sortablelink column="issued_at" title="{{ trans('invoices.invoice_date') }}" />
                                </x-slot>

                                @stack('issued_at_th_inside_end')
                            </x-table.th>

                            @stack('issued_at_th_end')

                            @stack('status_th_start')

                            <x-table.th class="w-3/12 hidden sm:table-cell">
                                @stack('status_th_inside_start')

                                <x-sortablelink column="status" title="{{ trans_choice('general.statuses', 1) }}" />

                                @stack('status_th_inside_end')
                            </x-table.th>

                            @stack('status_th_end')

                            @stack('document_number_th_start')

                            <x-table.th class="w-3/12 sm:table-cell">
                                @stack('document_number_th_inside_start')

                                <x-sortablelink column="document_number" title="{{ trans_choice('general.numbers', 1) }}" />

                                @stack('document_number_th_inside_end')
                            </x-table.th>

                            @stack('document_number_th_end')

                            @stack('amount_th_start')

                            <x-table.th class="w-6/12 sm:w-2/12" kind="amount">
                                @stack('amount_th_inside_start')

                                <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />

                                @stack('amount_th_inside_end')
                            </x-table.th>

                            @stack('amount_th_end')
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($invoices as $item)
                            @php $paid = $item->paid; @endphp
                            <x-table.tr href="{{ route('portal.invoices.show', $item->id) }}">
                                <x-table.td kind="action"></x-table.td>
                                @stack('issued_at_td_start')

                                <x-table.td class="w-4/12 hidden sm:table-cell">
                                    @stack('due_at_td_inside_start')

                                    <x-slot name="first" class="font-bold truncate" override="class">
                                        {{ \Date::parse($item->due_at)->diffForHumans() }}
                                    </x-slot>

                                    @stack('due_at_td_inside_end')

                                    @stack('issued_at_td_inside_start')

                                    <x-slot name="second">
                                        <x-date date="{{ $item->issued_at }}" />
                                    </x-slot>

                                    @stack('issued_at_td_inside_end')
                                </x-table.td>

                                @stack('issued_at_td_end')

                                @stack('status_td_start')

                                <x-table.td class="w-3/12 hidden sm:table-cell">
                                    @stack('status_td_inside_start')

                                    <x-index.status status="{{ $item->status }}" background-color="bg-{{ $item->status_label }}" text-color="text-text-{{ $item->status_label }}" />

                                    @stack('status_td_inside_end')
                                </x-table.td>

                                @stack('status_td_end')


                                @stack('due_at_and_issued_at_td_start')

                                <x-table.td class="w-3/12  sm:table-cell">
                                    @stack('document_number_td_inside_start')

                                    <x-slot name="first" class="relative w-20 font-normal group" data-tooltip-target="tooltip-information-{{ $item->id }}" data-tooltip-placement="left" override="class,data-tooltip-target,data-tooltip-placement">
                                        <span class="border-black border-b border-dashed">
                                            {{ $item->document_number }}
                                        </span>

                                        <div class="w-full absolute h-10 -left-10 -mt-6"></div>

                                        <x-documents.index.information :document="$item" show-route="portal.invoices.show"/>
                                    </x-slot>

                                    @stack('document_number_td_inside_end')
                                </x-table.td>

                                @stack('due_at_and_issued_at_td_end')

                                @stack('amount_td_start')

                                <x-table.td class="w-6/12 sm:w-2/12" kind="amount">
                                    @stack('amount_td_inside_start')

                                    <x-money :amount="$item->amount" :currency="$item->currency_code" convert />

                                        @stack('amount_td_inside_end')
                                </x-table.td>

                                @stack('amount_td_end')
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$invoices" />
            </x-index.container>
        @else
            <x-empty-page
                group="sales"
                page="invoices"
                hide-button-import
                :buttons="[
                    [
                        'url' =>  route('transactions.create', ['type' => 'income']),
                        'permission' => 'create-sales-invoices',
                        'text' => trans('general.title.new', ['type' => trans_choice('general.incomes', 1)]),
                        'description' => trans('general.empty.actions.new', ['type' => trans_choice('general.incomes', 1)]),
                        'active_badge' => false
                    ],
                    [
                        'url' => 'https://akaunting.com/premium-cloud',
                        'permission' => 'create-sales-invoices',
                        'text' => trans('import.title', ['type' => trans_choice('general.bank_transactions', 2)]),
                        'description' => '',
                        'active_badge' => false
                    ]
                ]"
            />
        @endif
    </x-slot>

    <x-script folder="portal" file="apps" />
</x-layouts.portal>
