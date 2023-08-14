<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.transfers', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.transfers', 2) }}"
        icon="sync_alt"
        route="transfers.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-banking-transfers')
            <x-link href="{{ route('transfers.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.transfers', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons pointer-events-none">more_horiz</span>
            </x-slot>

            @can('create-banking-transfers')
                <x-dropdown.link href="{{ route('import.create', ['banking', 'transfers']) }}">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('transfers.export', request()->input()) }}">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        @if ($transfers->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search
                    search-string="App\Models\Banking\Transfer"
                    bulk-action="App\BulkActions\Banking\Transfers"
                />

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th kind="bulkaction">
                                <x-index.bulkaction.all />
                            </x-table.th>

                            <x-table.th class="w-3/12" hidden-mobile>
                                <x-slot name="first">
                                    <x-sortablelink column="expense_transaction.paid_at" title="{{ trans('general.created_date') }}" />
                                </x-slot>
                                <x-slot name="second">
                                    <x-sortablelink column="expense_transaction.reference" title="{{ trans('general.reference') }}" />
                                </x-slot>
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-slot name="first">
                                    <x-sortablelink column="expense_transaction.name" title="{{ trans('transfers.from_account') }}" />
                                </x-slot>
                                <x-slot name="second">
                                    <x-sortablelink column="income_transaction.name" title="{{ trans('transfers.to_account') }}" />
                                </x-slot>
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-slot name="first">
                                    <x-sortablelink column="expense_transaction.rate" title="{{ trans('transfers.from_rate') }}" />
                                </x-slot>
                                <x-slot name="second">
                                    <x-sortablelink column="income_transaction.rate" title="{{ trans('transfers.to_rate') }}" />
                                </x-slot>
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12" kind="amount">
                                <x-slot name="first">
                                    <x-sortablelink column="expense_transaction.amount" title="{{ trans('transfers.from_amount') }}" />
                                </x-slot>
                                <x-slot name="second">
                                    <x-sortablelink column="income_transaction.amount" title="{{ trans('transfers.to_amount') }}" />
                                </x-slot>
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($transfers as $item)
                            @php
                                if (empty($item->expense_transaction->amount)) {
                                    continue;
                                }

                                $item->name = trans('transfers.messages.delete', [
                                    'from' => $item->expense_transaction->account->name,
                                    'to' => $item->income_transaction->account->name,
                                    'amount' => money($item->expense_transaction->amount, $item->expense_transaction->currency_code)
                                ]);
                            @endphp

                            <x-table.tr href="{{ route('transfers.show', $item->id) }}">
                                <x-table.td kind="bulkaction">
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->expense_transaction->account->name }}" />
                                </x-table.td>

                                <x-table.td class="w-3/12" hidden-mobile>
                                    <x-slot name="first" class="flex items-center font-bold" override="class">
                                        <x-date date="{{ $item->expense_transaction->paid_at }}" />
                                    </x-slot>
                                    <x-slot name="second">
                                        @if (! empty($item->reference))
                                            {{ $item->reference }}
                                        @else
                                            <x-empty-data />
                                        @endif
                                    </x-slot>
                                </x-table.td>

                                <x-table.td class="w-4/12 sm:w-3/12">
                                    <x-slot name="first">
                                        {{ $item->expense_transaction->account->name }}
                                    </x-slot>
                                    <x-slot name="second">
                                        {{ $item->income_transaction->account->name }}
                                    </x-slot>
                                </x-table.td>

                                <x-table.td class="w-4/12 sm:w-3/12">
                                    <x-slot name="first">
                                        {{ $item->expense_transaction->currency_rate }}
                                    </x-slot>
                                    <x-slot name="second">
                                        {{ $item->income_transaction->currency_rate }}
                                    </x-slot>
                                </x-table.td>

                                <x-table.td class="w-4/12 sm:w-3/12" kind="amount">
                                    <x-slot name="first">
                                        <x-money :amount="$item->expense_transaction->amount" :currency="$item->expense_transaction->currency_code" />
                                    </x-slot>
                                    <x-slot name="second">
                                        <x-money :amount="$item->income_transaction->amount" :currency="$item->income_transaction->currency_code" />
                                    </x-slot>
                                </x-table.td>

                                <x-table.td kind="action">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$transfers" />
            </x-index.container>
        @else
            <x-empty-page group="banking" page="transfers" />
        @endif
    </x-slot>

    <x-script folder="banking" file="transfers" />
</x-layouts.admin>
