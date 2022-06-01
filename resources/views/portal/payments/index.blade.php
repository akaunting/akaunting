<x-layouts.portal>
    <x-slot name="title">
        {{ trans_choice('general.payments', 2) }}
    </x-slot>

    <x-slot name="content">
        @if ($payments->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search search-string="App\Models\Portal\Banking\Transaction" />

                <x-table>
                    <x-table.thead>
                        <x-table.tr class="flex items-center px-1">
                            <x-table.th override="class" class="p-0"></x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-sortablelink column="paid_at" title="{{ trans('general.date') }}" />
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-sortablelink column="payment_method" title="{{ trans_choice('general.payment_methods', 1) }}" />
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-sortablelink column="description" title="{{ trans('general.description') }}" />
                            </x-table.th>

                            <x-table.th class="w-3/12" kind="amount">
                                <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($payments as $item)
                            <x-table.tr href="{{ route('portal.payments.show', $item->id) }}">
                                <x-table.td kind="action"></x-table.td>
                                
                                <x-table.td class="w-4/12 sm:w-3/12">
                                    <span class="font-bold"><x-date date="{{ $item->paid_at }}" /></span>
                                </x-table.td>

                                <x-table.td class="w-4/12 sm:w-3/12">
                                    {{ $payment_methods[$item->payment_method] }}
                                </x-table.td>

                                <x-table.td class="w-3/12 hidden sm:table-cell">
                                    {{ $item->description }}
                                </x-table.td>

                                <x-table.td class="w-3/12" kind="amount">
                                    <x-money :amount="$item->amount" :currency="$item->currency_code" convert />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$payments" />
            </x-index.container>
        @else
            <x-empty-page
                group="banking"
                page="transactions"
                hide-button-import
                :buttons="[
                    [
                        'url' =>  route('transactions.create', ['type' => 'income']),
                        'permission' => 'create-banking-transactions',
                        'text' => trans('general.title.new', ['type' => trans_choice('general.incomes', 1)]),
                        'description' => trans('general.empty.actions.new', ['type' => trans_choice('general.incomes', 1)]),
                        'active_badge' => false
                    ],
                    [
                        'url' => route('transactions.create', ['type' => 'expense']),
                        'permission' => 'create-banking-transactions',
                        'text' => trans('general.title.new', ['type' => trans_choice('general.expenses', 1)]),
                        'description' => trans('general.empty.actions.new', ['type' => trans_choice('general.expenses', 1)]),
                        'active_badge' => false
                    ],
                    [
                        'url' => 'https://akaunting.com/premium-cloud',
                        'permission' => 'create-banking-transactions',
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
