<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.reconciliations', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <div id="reconciliations-table" class="mt-3.5 lg:mt-8">
            <x-form id="reconciliation" method="PATCH" :route="['reconciliations.update', $reconciliation->id]" :model="$reconciliation">
                <div>
                    <h2 class="text-lg font-medium text-black mb-3">{{ trans_choice('general.transactions', 2) }}</h2>
                </div>

                <x-form.input.hidden name="account_id" :value="$account->id" />
                <x-form.input.hidden name="currency_code" :value="$currency->code" />
                <x-form.input.hidden name="opening_balance" value="{{ $opening_balance }}" />
                <x-form.input.hidden name="closing_balance" value="{{ $reconciliation->closing_balance }}" />
                <x-form.input.hidden name="started_at" :value="$reconciliation->started_at" />
                <x-form.input.hidden name="ended_at" :value="$reconciliation->ended_at" />
                <x-form.input.hidden name="reconcile" :value="$reconciliation->reconcile" id="hidden-reconcile" />

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th class="w-6/12 lg:w-2/12">
                                {{ trans('general.date') }}
                            </x-table.th>

                            <x-table.th class="w-3/12" hidden-mobile>
                                {{ trans('general.description') }}
                            </x-table.th>

                            <x-table.th class="w-6/12 lg:w-3/12">
                                {{ trans_choice('general.contacts', 1) }}
                            </x-table.th>

                            <x-table.th class="w-2/12" hidden-mobile>
                                {{ trans('reconciliations.deposit') }}
                            </x-table.th>

                            <x-table.th class="w-6/12 lg:w-2/12" hidden-mobile>
                                {{ trans('reconciliations.withdrawal') }}
                            </x-table.th>

                            <x-table.th kind="amount" class="none-truncate">
                                {{ trans('general.clear') }}
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($transactions as $item)
                            <x-table.tr>
                                <x-table.td class="w-6/12 lg:w-2/12" kind="cursor-none">
                                    <x-date date="{{ $item->paid_at }}" />
                                </x-table.td>

                                <x-table.td class="w-3/12" hidden-mobile kind="cursor-none">
                                    {{ $item->description }}
                                </x-table.td>

                                <x-table.td class="w-6/12 lg:w-3/12" kind="cursor-none">
                                    {{ $item->contact->name }}
                                </x-table.td>

                                @if ($item->isIncome())
                                    <x-table.td class="w-6/12 lg:w-2/12" hidden-mobile kind="cursor-none">
                                        <x-money :amount="$item->amount" hidden-mobile :currency="$item->currency_code" />
                                    </x-table.td>

                                    <x-table.td class="w-6/12 lg:w-2/12" hidden-mobile kind="cursor-none">
                                        <x-empty-data />
                                    </x-table.td>
                                @else
                                    <x-table.td class="w-6/12 lg:w-2/12" hidden-mobile kind="cursor-none">
                                        <x-empty-data />
                                    </x-table.td>

                                    <x-table.td class="w-6/12 lg:w-2/12" hidden-mobile kind="cursor-none">
                                        <x-money :amount="$item->amount" :currency="$item->currency_code" />
                                    </x-table.td>
                                @endif

                                <x-table.td kind="amount" class="none-truncate">
                                    @php
                                        $reconciliation_transactions = [];
                                        $type = $item->isIncome() ? 'income' : 'expense';
                                        $name = $type . '_' . $item->id;

                                        $checked = $item->reconciled;

                                        if (! empty($reconciliation->transactions)) {
                                            $reconciliation_transactions = $reconciliation->transactions;
                                        }

                                        if (! $reconciliation->reconciled && array_key_exists($name, $reconciliation_transactions)) {
                                            $checked = (empty($reconciliation_transactions[$name]) || $reconciliation_transactions[$name] === 'false') ? 0 : 1;
                                        }
                                    @endphp

                                    <x-form.input.checkbox name="{{ $type . '_' . $item->id }}"
                                        label=""
                                        id="transaction-{{ $item->id . '-'. $type }}"
                                        :value="! empty($item->amount_for_account) ? $item->amount_for_account : '0.00'"
                                        :checked="$checked"
                                        data-field="transactions"
                                        v-model="form.transactions.{{ $type . '_' . $item->id }}"
                                        class="text-purple focus:outline-none focus:ring-purple focus:border-purple"
                                        @change="onCalculate"
                                    />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                @if ($transactions->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="sm:float-right">
                            <tr class="border-b">
                                <th class="w-9/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-bold text-black">
                                    {{ trans('reconciliations.opening_balance') }}:
                                </th>

                                <td id="closing-balance" class="w-3/12 text-right">
                                    <span class="w-auto pl-6 text-sm">
                                        <x-money :amount="$opening_balance" :currency="$account->currency_code" />
                                    </span>
                                </td>
                            </tr>

                            <tr class="border-b">
                                <th class="w-9/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-bold text-black">
                                    {{ trans('reconciliations.closing_balance') }}:
                                </th>

                                <td id="closing-balance" class="w-3/12 text-right">
                                    <x-form.input.money
                                        name="closing_balance_total"
                                        value="0"
                                        disabled
                                        row-input
                                        v-model="totals.closing_balance"
                                        :currency="$currency"
                                        dynamicCurrency="currency"
                                        money-class="text-right disabled-money banking-price-text w-auto position-absolute right-4 ltr:pr-0 rtl:pl-0 text-sm"
                                        form-group-class="text-right disabled-money"
                                    />
                                </td>
                            </tr>

                            <tr class="border-b">
                                <th class="w-9/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-bold text-black">
                                    {{ trans('reconciliations.cleared_amount') }}:
                                </th>

                                <td id="cleared-amount" class="w-3/12 text-right">
                                    <x-form.input.money
                                        name="cleared_amount_total"
                                        value="0"
                                        disabled
                                        row-input
                                        v-model="totals.cleared_amount"
                                        :currency="$currency"
                                        dynamicCurrency="currency"
                                        money-class="text-right disabled-money banking-price-text w-auto position-absolute right-4 ltr:pr-0 rtl:pl-0 text-sm"
                                        form-group-class="text-right disabled-money"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <th class="w-9/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-bold text-black cursor-pointer">
                                    <span class="px-2 py-1 rounded-xl" :class="difference">
                                        {{ trans('general.difference') }}
                                    </span>
                                </th>

                                <td id="difference" class="w-3/12 ltr:pl-6 rtl:pr-0 text-right">
                                    <div class="difference-money">
                                        <x-form.input.money
                                            name="difference_total"
                                            value="0"
                                            disabled
                                            row-input
                                            v-model="totals.difference"
                                            :currency="$currency"
                                            dynamicCurrency="currency"
                                            money-class="text-right disabled-money banking-price-text w-auto position-absolute right-4 ltr:pr-0 rtl:pl-0 text-sm"
                                            form-group-class="text-right disabled-money"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif

                @can('update-banking-reconciliations')
                <div class="mt-6">
                    @if ($transactions->count())
                        <div class="sm:col-span-6 flex items-center justify-end">
                            <x-link
                                href="{{ route('reconciliations.index') }}"
                                class="flex items-center justify-center bg-transparent hover:bg-gray-200 py-1.5 text-base rounded-lg disabled:opacity-50 px-3 ltr:mr-2 rtl:ml-2"
                                override="class"
                            >
                                {{ trans('general.cancel') }}
                            </x-link>

                            <x-button
                                type="submit"
                                ::disabled="form.loading"
                                class="relative flex items-center justify-center bg-transparent hover:bg-gray-200 px-3 py-1.5 text-base rounded-lg disabled:opacity-50"
                                override="class"
                            >
                                <x-button.loading action="! reconcile && form.loading">
                                    {{ trans('general.save') }}
                                </x-button.loading>
                            </x-button>

                            <div v-if="! reconcile">
                                <x-tooltip id="tooltip-reconcile" placement="top" message="{{ trans('reconciliations.irreconcilable') }}">
                                    <x-button
                                        type="button"
                                        ::disabled="! reconcile"
                                        class="relative flex items-center justify-center px-3 py-1.5 ltr:ml-2 rtl:mr-2 text-white text-base rounded-lg bg-blue hover:bg-blue-700 disabled:bg-blue-100"
                                        override="class"
                                        @click="onReconcileSubmit"
                                        data-loading-text="{{ trans('general.loading') }}"
                                    >
                                        <x-button.loading action="reconcile && form.loading">
                                            {{ trans('reconciliations.reconcile') }}
                                        </x-button.loading>
                                    </x-button>
                                </x-tooltip>
                            </div>

                            <div v-else>
                                <x-button
                                    type="button"
                                    ::disabled="! reconcile"
                                    class="relative flex items-center justify-center px-3 py-1.5 ltr:ml-2 rtl:mr-2 text-white text-base rounded-lg bg-blue hover:bg-blue-700 disabled:bg-blue-100"
                                    override="class"
                                    @click="onReconcileSubmit"
                                    data-loading-text="{{ trans('general.loading') }}"
                                >
                                    <x-button.loading action="reconcile && form.loading">
                                        {{ trans('reconciliations.reconcile') }}
                                    </x-button.loading>
                                </x-button>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-muted" id="datatable-basic_info" role="status" aria-live="polite">
                            <small>{{ trans('general.no_records') }}</small>
                        </div>
                    @endif
                </div>
                @endcan
            </x-form>
        </div>
    </x-slot>

    <x-script folder="banking" file="reconciliations" />
</x-layouts.admin>
