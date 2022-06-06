<x-layouts.admin>
    <x-slot name="title">
        {{ $account->name }}
    </x-slot>

    <x-slot name="status">
        <div class="mt-3">
            @if (! $account->enabled)
                <x-index.disable text="{{ trans_choice('general.accounts', 1) }}" />
            @endif

            @if (setting('default.account') == $account->id)
                <x-index.default text="{{ trans('accounts.default_account') }}" />
            @endif
        </div>
    </x-slot>

    <x-slot name="favorite"
        title="{{ $account->name }}"
        icon="account_balance"
        :route="['accounts.show', $account->id]"
    ></x-slot>

    <x-slot name="buttons">
        @stack('create_button_start')

        <x-dropdown id="dropdown-new-actions">
            <x-slot name="trigger" class="flex items-center px-3 py-1.5 mb-3 sm:mb-0 bg-green hover:bg-green-700 rounded-xl text-white text-sm font-bold leading-6" override="class">
                {{ trans('general.new_more') }}
                <span class="material-icons ltr:ml-2 rtl:mr-2">expand_more</span>
            </x-slot>

            @stack('income_button_start')

            @can('create-banking-transactions')
            <x-dropdown.link href="{{ route('accounts.create-income', $account->id) }}">
                {{ trans_choice('general.incomes', 1) }}
            </x-dropdown.link>
            @endcan

            @stack('expense_button_start')

            @can('create-banking-transactions')
            <x-dropdown.link href="{{ route('accounts.create-expense', $account->id) }}">
                {{ trans_choice('general.expenses', 1) }}
            </x-dropdown.link>
            @endcan

            @stack('transfer_button_start')

            @can('create-banking-transfers')
            <x-dropdown.link href="{{ route('accounts.create-transfer', $account->id) }}">
                {{ trans_choice('general.transfers', 1) }}
            </x-dropdown.link>
            @endcan

            @stack('transfer_button_end')
        </x-dropdown>

        @stack('edit_button_start')

        @can('update-banking-accounts')
        <x-link href="{{ route('accounts.edit', $account->id) }}">
            {{ trans('general.edit') }}
        </x-link>
        @endcan

        @stack('edit_button_end')
    </x-slot>

    <x-slot name="moreButtons">
        @stack('more_button_start')

        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons">more_horiz</span>
            </x-slot>

            @stack('see_performance_button_start')

            @can('read-banking-accounts')
            <x-dropdown.link href="{{ route('accounts.see-performance', $account->id) }}">
                {{ trans('accounts.see_performance') }}
            </x-dropdown.link>
            @endcan

            <x-dropdown.divider />

            @stack('duplicate_button_start')

            @can('create-banking-accounts')
            <x-dropdown.link href="{{ route('accounts.duplicate', $account->id) }}">
                {{ trans('general.duplicate') }}
            </x-dropdown.link>
            @endcan

            <x-dropdown.divider />

            @stack('delete_button_start')

            @can('delete-banking-accounts')
            <x-delete-link :model="$account" route="accounts.destroy" />
            @endcan

            @stack('delete_button_end')
        </x-dropdown>

        @stack('more_button_end')
    </x-slot>

    <x-slot name="content">
        <x-show.container>
            <x-show.summary>
                <x-show.summary.left>
                </x-show.summary.left>

                <x-show.summary.right>
                    @stack('summary_incoming_start')
                    <x-slot name="first" amount="{{ money($account->income_balance, $account->currency_code, true) }}" title="{{ trans('accounts.incoming') }}"></x-slot>
                    @stack('summary_incoming_end')

                    @stack('summary_outgoing_start')
                    <x-slot name="second" amount="{{ money($account->expense_balance, $account->currency_code, true) }}" title="{{ trans('accounts.outgoing') }}"></x-slot>
                    @stack('summary_outgoing_end')

                    @stack('summary_current_start')
                    <x-slot name="third" amount="{{ money($account->balance, $account->currency_code, true) }}" title="{{ trans('accounts.current_balance') }}"></x-slot>
                    @stack('summary_current_end')
                </x-show.summary.right>
            </x-show.summary>

            <x-show.content>
                <x-show.content.left>
                    @stack('account_number_start')
                    <div class="flex flex-col text-sm mb-5">
                        <div class="font-medium">
                            {{ trans('accounts.number') }}
                        </div>

                        <span>{{ $account->number }}</span>
                    </div>
                    @stack('account_number_end')

                    @stack('account_currency_start')
                    <div class="flex flex-col text-sm mb-5">
                        <div class="font-medium">
                            {{ trans_choice('general.currencies', 2) }}
                        </div>

                        <span>
                            {{ $account->currency->name }}
                        </span>
                    </div>
                    @stack('account_currency_end')

                    @stack('account_starting_balance_start')
                    <div class="flex flex-col text-sm mb-5">
                        <div class="font-medium">
                            {{ trans_choice('accounts.opening_balance', 2) }}
                        </div>

                        <span>
                            <x-money :amount="$account->opening_balance" :currency="$account->currency_code" convert />
                        </span>
                    </div>
                    @stack('account_starting_balance_end')

                    @stack('account_phone_start')
                    @if ($account->bank_phone)
                        <div class="flex flex-col text-sm mb-5">
                            <div class="font-medium">
                                {{ trans('accounts.bank_phone') }}
                            </div>

                            <span>
                                {{ $account->bank_phone }}
                            </span>
                        </div>
                    @endif
                    @stack('account_phone_end')

                    @stack('account_address_start')
                    @if ($account->bank_address)
                        <div class="flex flex-col text-sm mb-5">
                            <div class="font-medium">
                                {{ trans('accounts.bank_address') }}
                            </div>

                            <span>
                                {{ $account->bank_address }}
                            </span>
                        </div>
                    @endif
                    @stack('account_address_end')
                </x-show.content.left>

                <x-show.content.right>
                    <x-tabs active="transactions">
                        <x-slot name="navs">
                            @stack('transactions_nav_start')

                            <x-tabs.nav
                                id="transactions"
                                name="{{ trans_choice('general.transactions', 2) }}"
                                active
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />

                            @stack('transfers_nav_start')

                            <x-tabs.nav
                                id="transfers"
                                name="{{ trans_choice('general.transfers', 2) }}"
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />

                            @stack('transfers_nav_end')
                        </x-slot>

                        <x-slot name="content">
                            @stack('transactions_tab_start')

                            <x-tabs.tab id="transactions">
                                @if ($transactions->count())
                                    <x-table>
                                        <x-table.thead>
                                            <x-table.tr class="flex items-center px-1">
                                                <x-table.th class="w-4/12 sm:w-3/12">
                                                    <x-slot name="first">
                                                        <x-sortablelink column="paid_at" title="{{ trans('general.date') }}" />
                                                    </x-slot>
                                                    <x-slot name="second">
                                                        <x-sortablelink column="number" title="{{ trans_choice('general.numbers', 1) }}" />
                                                    </x-slot>
                                                </x-table.th>

                                                <x-table.th class="w-3/12 hidden sm:table-cell">
                                                    <x-slot name="first">
                                                        <x-sortablelink column="type" title="{{ trans_choice('general.types', 1) }}" />
                                                    </x-slot>
                                                    <x-slot name="second">
                                                        <x-sortablelink column="category.name" title="{{ trans_choice('general.categories', 1) }}" />
                                                    </x-slot>
                                                </x-table.th>

                                                <x-table.th class="w-4/12 sm:w-2/12">
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
                                                    <x-table.td class="w-4/12 sm:w-3/12">
                                                        <x-slot name="first" class="font-bold truncate" override="class">
                                                            <x-date date="{{ $item->paid_at }}" />
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            {{ $item->number }}
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-3/12 hidden sm:table-cell">
                                                        <x-slot name="first">
                                                            {{ $item->type_title }}
                                                        </x-slot>
                                                        <x-slot name="second" class="flex items-center">
                                                            <x-index.category :model="$item->category" />
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-4/12 sm:w-2/12">
                                                        {{ $item->account->name }}
                                                    </x-table.td>

                                                    <x-table.td class="w-2/12 hidden sm:table-cell">
                                                        <x-slot name="first">
                                                            {{ $item->contact->name }}
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            @if ($item->document)
                                                                <a href="{{ route($item->route_name, $item->route_id) }}" class="font-normal truncate border-b border-black border-dashed">
                                                                    {{ $item->document->document_number }}
                                                                </a>
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
                                @else
                                    <x-show.no-records type="account" :model="$account" group="banking" page="transactions" />
                                @endif
                            </x-tabs.tab>

                            @stack('transfers_tab_start')

                            <x-tabs.tab id="transfers">
                                @if ($transfers->count())
                                    <x-table>
                                        <x-table.thead>
                                            <x-table.tr class="flex items-center px-1">
                                                <x-table.th class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                                    <x-index.bulkaction.all />
                                                </x-table.th>

                                                <x-table.th class="w-3/12 hidden sm:table-cell">
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
                                                    $item->name = trans('transfers.messages.delete', [
                                                        'from' => $item->expense_transaction->account->name,
                                                        'to' => $item->income_transaction->account->name,
                                                        'amount' => money($item->expense_transaction->amount, $item->expense_transaction->currency_code, true)
                                                    ]);
                                                @endphp

                                                <x-table.tr href="{{ route('transfers.show', $item->id) }}">
                                                    <x-table.td class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                                        <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->expense_transaction->account->name }}" />
                                                    </x-table.td>

                                                    <x-table.td class="w-3/12 truncate hidden sm:table-cell">
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

                                                    <x-table.td class="w-4/12 sm:w-3/12 truncate">
                                                        <x-slot name="first">
                                                            {{ $item->expense_transaction->account->name }}
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            {{ $item->income_transaction->account->name }}
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-4/12 sm:w-3/12 truncate">
                                                        <x-slot name="first">
                                                            {{ $item->expense_transaction->currency_rate }}
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            {{ $item->income_transaction->currency_rate }}
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-4/12 sm:w-3/12" kind="amount">
                                                        <x-slot name="first">
                                                            <x-money :amount="$item->expense_transaction->amount" :currency="$item->expense_transaction->currency_code" convert />
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            <x-money :amount="$item->income_transaction->amount" :currency="$item->income_transaction->currency_code" convert />
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
                                @else
                                    <x-show.no-records type="account" :model="$account" group="banking" page="transfers" />
                                @endif
                            </x-tabs.tab>

                            @stack('transfers_tab_end')
                        </x-slot>
                    </x-tabs>
                </x-show.content.right>
            </x-show.content>
        </x-show.container>
    </x-slot>

    <x-script folder="banking" file="accounts" />
</x-layouts.admin>
