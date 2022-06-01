<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.transactions', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.transactions', 2) }}"
        icon="receipt_long"
        route="transactions.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-banking-transactions')
            <x-link href="{{ route('transactions.create', ['type' => 'income']) }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.incomes', 1)]) }}
            </x-link>

            <x-link href="{{ route('transactions.create', ['type' => 'expense']) }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.expenses', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons">more_horiz</span>
            </x-slot>

            @can('create-banking-transactions')
                <x-dropdown.link href="{{ route('import.create', ['banking', 'transactions']) }}">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('transactions.export', request()->input()) }}">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        @if ($transactions->count() || request()->get('search', false))
            <x-index.summary>
                <x-slot name="first"
                    href="{{ route('transactions.index', ['search' => 'type:income']) }}"
                    amount="{{ money($totals['income'], setting('default.currency'), true) }}"
                    title="{{ trans_choice('general.incomes', 1) }}"
                    divider="remove"
                ></x-slot>

                <x-slot name="second"
                    href="{{ route('transactions.index', ['search' => 'type:expense']) }}"
                    amount="{{ money($totals['expense'], setting('default.currency'), true) }}"
                    title="{{ trans_choice('general.expenses', 2) }}"
                    divider="drag_handle"
                ></x-slot>

                <x-slot name="third"
                    amount="{{ money($totals['profit'], setting('default.currency'), true) }}"
                    title="{{ trans_choice('general.profits', 1) }}"
                ></x-slot>
            </x-index.summary>

            <x-index.container>
                <x-tabs active="transactions">
                    <x-slot name="navs">
                        <x-tabs.nav
                            id="transactions"
                            name="{{ trans_choice('general.transactions', 2) }}"
                            active
                        />

                        <x-tabs.nav-link
                            id="recurring-templates"
                            name="{{ trans_choice('general.recurring_templates', 2) }}"
                            href="{{ route('recurring-transactions.index') }}"
                        />
                    </x-slot>

                    <x-slot name="content">
                        <x-index.search
                            search-string="App\Models\Banking\Transaction"
                            bulk-action="App\BulkActions\Banking\Transactions"
                        />

                        <x-tabs.tab id="transactions">
                            <x-table>
                                <x-table.thead>
                                    <x-table.tr class="flex items-center px-1">
                                        <x-table.th class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                            <x-index.bulkaction.all />
                                        </x-table.th>

                                        <x-table.th class="w-4/12 sm:w-3/12">
                                            <x-slot name="first">
                                                <x-sortablelink column="paid_at" title="{{ trans('general.date') }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="number" title="{{ trans_choice('general.numbers', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-2/12 hidden sm:table-cell">
                                            <x-slot name="first">
                                                <x-sortablelink column="type" title="{{ trans_choice('general.types', 1) }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="category.name" title="{{ trans_choice('general.categories', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-4/12 sm:w-3/12">
                                            <x-sortablelink column="account.name" title="{{ trans_choice('general.accounts', 1) }}" />
                                        </x-table.th>

                                        <x-table.th class="w-2/12 hidden sm:table-cell">
                                            <x-slot name="first">
                                                <x-sortablelink column="contact.name" title="{{ trans_choice('general.contacts', 1) }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="document.document_number" title="{{ trans_choice('general.documents', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-4/12 sm:w-2/12" kind="amount">
                                            <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                                        </x-table.th>
                                    </x-table.tr>
                                </x-table.thead>

                                <x-table.tbody>
                                    @foreach($transactions as $item)
                                        <x-table.tr href="{{ route('transactions.show', $item->id) }}">
                                            <x-table.td class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                                <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->contact->name }}" />
                                            </x-table.td>

                                            <x-table.td class="w-4/12 sm:w-3/12">
                                                <x-slot name="first" class="font-bold truncate" override="class">
                                                    <x-date date="{{ $item->paid_at }}" />
                                                </x-slot>
                                                <x-slot name="second">
                                                    {{ $item->number }}
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-2/12 hidden sm:table-cell">
                                                <x-slot name="first">
                                                    {{ $item->type_title }}
                                                </x-slot>
                                                <x-slot name="second" class="flex items-center">
                                                    <x-index.category :model="$item->category" />
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-4/12 sm:w-3/12">
                                                {{ $item->account->name }}
                                            </x-table.td>

                                            <x-table.td class="w-2/12 hidden sm:table-cell">
                                                <x-slot name="first">
                                                    {{ $item->contact->name }}
                                                </x-slot>
                                                <x-slot name="second" class="w-20 font-normal group" data-tooltip-target="tooltip-information-{{ $item->id }}" data-tooltip-placement="left" override="class">
                                                    @if ($item->document)
                                                        <a href="{{ route($item->route_name, $item->route_id) }}" class="font-normal truncate border-b border-black border-dashed">
                                                            {{ $item->document->document_number }}
                                                        </a>

                                                        <div class="w-28 absolute h-10 -ml-12 -mt-6"></div>

                                                        <x-documents.index.information :document="$item->document" />
                                                    @else
                                                        <x-empty-data />
                                                    @endif
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="relative w-4/12 sm:w-2/12" kind="amount">
                                                <x-money :amount="$item->amount" :currency="$item->currency_code" convert />
                                            </x-table.td>

                                            <x-table.td kind="action">
                                                <x-table.actions :model="$item" />
                                            </x-table.td>
                                        </x-table.tr>
                                    @endforeach
                                </x-table.tbody>
                            </x-table>

                            <x-pagination :items="$transactions" />
                        </x-tabs.tab>
                    </x-slot>
                </x-tabs>
            </x-index.container>

            <akaunting-connect-transactions
                :show="connect.show"
                :transaction="connect.transaction"
                :currency="connect.currency"
                :documents="connect.documents"
                :translations="{{ json_encode($translations) }}"
                modal-dialog-class="max-w-screen-lg"
                v-on:close-modal="connect.show = false"
            ></akaunting-connect-transactions>
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

    <x-script folder="banking" file="transactions" />
</x-layouts.admin>
