<x-layouts.admin>
    <x-slot name="title">
        {{ $account->name }}
    </x-slot>

    <x-slot name="info">
        <div class="mt-4">
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

        <x-dropdown id="show-new-actions-account">
            <x-slot name="trigger" class="w-full flex items-center justify-between sm:justify-start px-3 py-1.5 mb-3 sm:mb-0 bg-green hover:bg-green-700 rounded-xl text-white text-sm font-bold leading-6" override="class">
                {{ trans('general.new_more') }}
                <span class="material-icons ltr:ml-2 rtl:mr-2">expand_more</span>
            </x-slot>

            @stack('income_button_start')

            @can('create-banking-transactions')
            <x-dropdown.link href="{{ route('accounts.create-income', $account->id) }}" id="show-more-actions-new-income-account">
                {{ trans_choice('general.incomes', 1) }}
            </x-dropdown.link>
            @endcan

            @stack('expense_button_start')

            @can('create-banking-transactions')
            <x-dropdown.link href="{{ route('accounts.create-expense', $account->id) }}" id="show-more-actions-new-expense-account">
                {{ trans_choice('general.expenses', 1) }}
            </x-dropdown.link>
            @endcan

            @stack('transfer_button_start')

            @can('create-banking-transfers')
            <x-dropdown.link href="{{ route('accounts.create-transfer', $account->id) }}" id="show-more-actions-new-transfer-account">
                {{ trans_choice('general.transfers', 1) }}
            </x-dropdown.link>
            @endcan

            @stack('transfer_button_end')
        </x-dropdown>

        @stack('edit_button_start')

        @can('update-banking-accounts')
        <x-link href="{{ route('accounts.edit', $account->id) }}" id="show-more-actions-edit-account">
            {{ trans('general.edit') }}
        </x-link>
        @endcan

        @stack('edit_button_end')
    </x-slot>

    <x-slot name="moreButtons">
        @stack('more_button_start')

        <x-dropdown id="show-more-actions-account">
            <x-slot name="trigger">
                <span class="material-icons pointer-events-none">more_horiz</span>
            </x-slot>

            @stack('see_performance_button_start')

            @can('read-banking-accounts')
            <x-dropdown.link href="{{ route('accounts.see-performance', $account->id) }}" id="show-more-actions-performance-account">
                {{ trans('accounts.see_performance') }}
            </x-dropdown.link>
            @endcan

            <x-dropdown.divider />

            @stack('duplicate_button_start')

            @can('create-banking-accounts')
            <x-dropdown.link href="{{ route('accounts.duplicate', $account->id) }}" id="show-more-actions-duplicate-account">
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
                    <x-slot name="first"
                        amount="{{ $summary_amounts['incoming_for_humans'] }}"
                        title="{{ trans('accounts.incoming') }}"
                        tooltip="{{ $summary_amounts['incoming_exact'] }}"
                    ></x-slot>
                    @stack('summary_incoming_end')

                    @stack('summary_outgoing_start')
                    <x-slot name="second"
                        amount="{{ $summary_amounts['outgoing_for_humans'] }}"
                        title="{{ trans('accounts.outgoing') }}"
                        tooltip="{{ $summary_amounts['outgoing_exact'] }}"
                    ></x-slot>
                    @stack('summary_outgoing_end')

                    @stack('summary_current_start')
                    <x-slot name="third"
                        amount="{{ $summary_amounts['current_for_humans'] }}"
                        title="{{ trans('accounts.current_balance') }}"
                        tooltip="{{ $summary_amounts['current_exact'] }}"
                    ></x-slot>
                    @stack('summary_current_end')
                </x-show.summary.right>
            </x-show.summary>

            <x-show.content>
                <x-show.content.left>
                    @stack('type_input_start')
                    @stack('type_input_end')

                    @stack('name_input_start')
                    @stack('name_input_end')

                    @stack('number_input_start')
                    <div class="flex flex-col text-sm mb-5">
                        <div class="font-medium">
                            {{ trans('accounts.number') }}
                        </div>

                        <span>{{ $account->number }}</span>
                    </div>
                    @stack('number_input_end')

                    @stack('currency_code_input_start')
                    <div class="flex flex-col text-sm mb-5">
                        <div class="font-medium">
                            {{ trans_choice('general.currencies', 1) }}
                        </div>

                        <span>
                            {{ $account->currency->name }}
                        </span>
                    </div>
                    @stack('currency_code_input_end')

                    @stack('opening_balance_input_start')
                    <div class="flex flex-col text-sm mb-5">
                        <div class="font-medium">
                            {{ trans('accounts.opening_balance') }}
                        </div>

                        <span>
                            <x-money :amount="$account->opening_balance" :currency="$account->currency_code" />
                        </span>
                    </div>
                    @stack('opening_balance_input_end')

                    @stack('default_account_input_start')
                    @stack('default_account_input_end')

                    @stack('bank_name_input_start')
                    @stack('bank_name_input_end')

                    @stack('bank_phone_input_start')
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
                    @stack('bank_phone_input_end')

                    @stack('bank_address_input_start')
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
                    @stack('bank_address_input_end')
                </x-show.content.left>

                <x-show.content.right>
                    <x-tabs active="transactions">
                        <x-slot name="navs">
                            @stack('transactions_nav_start')

                            <x-tabs.nav
                                id="transactions"
                                name="{{ trans_choice('general.transactions', 2) }}"
                                active
                            />

                            @stack('transfers_nav_start')

                            <x-tabs.nav
                                id="transfers"
                                name="{{ trans_choice('general.transfers', 2) }}"
                            />

                            @stack('transfers_nav_end')
                        </x-slot>

                        <x-slot name="content">
                            @stack('transactions_tab_start')

                            <x-tabs.tab id="transactions">
                                @if ($transactions->count())
                                    <x-table>
                                        <x-table.thead>
                                            <x-table.tr>
                                                <x-table.th class="w-6/12 lg:w-3/12">
                                                    <x-slot name="first">
                                                        <x-sortablelink column="paid_at" title="{{ trans('general.date') }}" />
                                                    </x-slot>
                                                    <x-slot name="second">
                                                        <x-sortablelink column="number" title="{{ trans_choice('general.numbers', 1) }}" />
                                                    </x-slot>
                                                </x-table.th>

                                                <x-table.th class="w-3/12" hidden-mobile>
                                                    <x-slot name="first">
                                                        <x-sortablelink column="type" title="{{ trans_choice('general.types', 1) }}" />
                                                    </x-slot>
                                                    <x-slot name="second">
                                                        <x-sortablelink column="category.name" title="{{ trans_choice('general.categories', 1) }}" />
                                                    </x-slot>
                                                </x-table.th>

                                                <x-table.th class="w-3/12" hidden-mobile>
                                                    <x-slot name="first">
                                                        <x-sortablelink column="contact.name" title="{{ trans_choice('general.contacts', 1) }}" />
                                                    </x-slot>
                                                    <x-slot name="second">
                                                        <x-sortablelink column="document.document_number" title="{{ trans_choice('general.documents', 1) }}" />
                                                    </x-slot>
                                                </x-table.th>

                                                <x-table.th class="w-6/12 lg:w-3/12" kind="amount">
                                                    <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                                                </x-table.th>
                                            </x-table.tr>
                                        </x-table.thead>

                                        <x-table.tbody>
                                            @foreach($transactions as $item)
                                                <x-table.tr href="{{ route('transactions.show', $item->id) }}">
                                                    <x-table.td class="w-6/12 lg:w-3/12">
                                                        <x-slot name="first" class="font-bold truncate" override="class">
                                                            <x-date date="{{ $item->paid_at }}" />
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            {{ $item->number }}
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-3/12" hidden-mobile>
                                                        <x-slot name="first">
                                                            {{ $item->type_title }}
                                                        </x-slot>
                                                        <x-slot name="second" class="flex items-center">
                                                            <x-index.category :model="$item->category" />
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-3/12" hidden-mobile>
                                                        <x-slot name="first">
                                                            {{ $item->contact->name }}
                                                        </x-slot>
                                                        <x-slot name="second" class="w-20 font-normal group">
                                                            @if ($item->document)
                                                            <div data-tooltip-target="tooltip-information-{{ $item->document_id }}" data-tooltip-placement="left" override="class">
                                                                <x-link href="{{ route($item->route_name, $item->route_id) }}" class="font-normal truncate border-b border-black border-dashed" override="class">
                                                                    {{ $item->document->document_number }}
                                                                </x-link>

                                                                <div class="w-28 absolute h-10 -ml-12 -mt-6"></div>

                                                                <x-documents.index.information :document="$item->document" />
                                                            </div>
                                                            @else
                                                                <x-empty-data />
                                                            @endif
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-6/12 lg:w-3/12" kind="amount">
                                                        <x-money :amount="$item->amount" :currency="$item->currency_code" />
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
                                            <x-table.tr>
                                                <x-table.th class="w-3/12" hidden-mobile>
                                                    <x-slot name="first">
                                                        <x-sortablelink column="expense_transaction.paid_at" title="{{ trans('general.created_date') }}" />
                                                    </x-slot>
                                                    <x-slot name="second">
                                                        <x-sortablelink column="expense_transaction.reference" title="{{ trans('general.reference') }}" />
                                                    </x-slot>
                                                </x-table.th>

                                                <x-table.th class="w-6/12 sm:w-3/12">
                                                    <x-slot name="first">
                                                        <x-sortablelink column="expense_transaction.name" title="{{ trans('transfers.from_account') }}" />
                                                    </x-slot>
                                                    <x-slot name="second">
                                                        <x-sortablelink column="income_transaction.name" title="{{ trans('transfers.to_account') }}" />
                                                    </x-slot>
                                                </x-table.th>

                                                <x-table.th class="w-4/12 sm:w-3/12" hidden-mobile>
                                                    <x-slot name="first">
                                                        <x-sortablelink column="expense_transaction.rate" title="{{ trans('transfers.from_rate') }}" />
                                                    </x-slot>
                                                    <x-slot name="second">
                                                        <x-sortablelink column="income_transaction.rate" title="{{ trans('transfers.to_rate') }}" />
                                                    </x-slot>
                                                </x-table.th>

                                                <x-table.th class="w-6/12 sm:w-3/12" kind="amount">
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
                                                        'amount' => money($item->expense_transaction->amount, $item->expense_transaction->currency_code)
                                                    ]);
                                                @endphp

                                                <x-table.tr href="{{ route('transfers.show', $item->id) }}">
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

                                                    <x-table.td class="w-6/12 sm:w-3/12 truncate">
                                                        <x-slot name="first">
                                                            {{ $item->expense_transaction->account->name }}
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            {{ $item->income_transaction->account->name }}
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-4/12 sm:w-3/12 truncate" hidden-mobile>
                                                        <x-slot name="first">
                                                            {{ $item->expense_transaction->currency_rate }}
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            {{ $item->income_transaction->currency_rate }}
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-6/12 sm:w-3/12" kind="amount">
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

        <akaunting-connect-transactions
            :show="connect.show"
            :transaction="connect.transaction"
            :currency="connect.currency"
            :documents="connect.documents"
            :translations="{{ json_encode($transactions) }}"
            modal-dialog-class="max-w-screen-lg"
            v-on:close-modal="connect.show = false"
        ></akaunting-connect-transactions>
    </x-slot>

    <x-script folder="banking" file="accounts" />
</x-layouts.admin>
