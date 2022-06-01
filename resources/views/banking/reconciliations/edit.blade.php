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

                <div class="overflow-x-visible">
                    <div class="py-2 align-middle inline-block min-w-full">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="flex items-center px-1">
                                    <th scope="col" class="w-2/12  ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                        {{ trans('general.date') }}
                                    </th>

                                    <th scope="col" class="w-3/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                        {{ trans('general.description') }}
                                    </th>

                                    <th scope="col" class="w-3/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                        {{ trans_choice('general.contacts', 1) }}
                                    </th>

                                    <th scope="col" class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                        {{ trans('reconciliations.deposit') }}
                                    </th>

                                    <th scope="col" class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                        {{ trans('reconciliations.withdrawal') }}
                                    </th>

                                    <th scope="col" class="ltr:pl-6 rtl:pr-6 ltr:text-right rtl:text-left py-4 text-center text-sm font-medium">
                                        {{ trans('general.clear') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($transactions as $item)
                                <tr class="relative flex items-center border-b hover:bg-gray-100 px-1 group">
                                    <td class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                        <x-date date="{{ $item->paid_at }}" />
                                    </td>

                                    <td class="w-3/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider truncate">
                                        {{ $item->description }}
                                    </td>

                                    <td class="w-3/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider truncate">
                                        {{ $item->contact->name }}
                                    </td>

                                    @if ($item->isIncome())
                                        <td class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                            <x-money :amount="$item->amount" :currency="$item->currency_code" convert />
                                        </td>

                                        <td class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                            <x-empty-data />
                                        </td>
                                    @else
                                        <td class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                            <x-empty-data />
                                        </td>

                                        <td class="w-2/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                            <x-money :amount="$item->amount" :currency="$item->currency_code" convert />
                                        </td>
                                    @endif

                                    <td class="ltr:pl-6 rtl:pr-6 py-4 text-center text-sm font-medium">
                                        @php $type = $item->isIncome() ? 'income' : 'expense'; @endphp

                                        <x-form.input.checkbox name="{{ $type . '_' . $item->id }}"
                                            label=""
                                            id="transaction-{{ $item->id . '-'. $type }}"
                                            :value="$item->amount_for_account"
                                            :checked="$item->reconciled"
                                            data-field="transactions"
                                            v-model="form.transactions.{{ $type . '_' . $item->id }}"
                                            class="text-purple focus:outline-none focus:ring-purple focus:border-purple"
                                            @change="onCalculate"
                                        />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($transactions->count())
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="float-right">
                                    <tr class="border-b">
                                        <th class="w-11/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-bold text-black">
                                            {{ trans('reconciliations.opening_balance') }}:
                                        </th>

                                        <td id="closing-balance" class="w-1/12 text-right">
                                            <span class="w-auto pl-6 text-sm">
                                                <x-money :amount="$opening_balance" :currency="$account->currency_code" convert />
                                            </span>
                                        </td>
                                    </tr>

                                    <tr class="border-b">
                                        <th class="w-11/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-bold text-black">
                                            {{ trans('reconciliations.closing_balance') }}:
                                        </th>

                                        <td id="closing-balance" class="w-1/12 text-right">
                                            <x-form.input.money
                                                name="closing_balance_total"
                                                value="0"
                                                disabled
                                                row-input
                                                v-model="totals.closing_balance"
                                                :currency="$currency"
                                                dynamicCurrency="currency"
                                                money-class="text-right disabled-money banking-price-text w-auto position-absolute right-4 ltr:pr-0 rtl:pl-0 text-sm js-conversion-input"
                                                form-group-class="text-right disabled-money"
                                            />
                                        </td>
                                    </tr>

                                    <tr class="border-b">
                                        <th class="w-11/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-bold text-black">
                                            {{ trans('reconciliations.cleared_amount') }}:
                                        </th>

                                        <td id="cleared-amount" class="w-1/12 text-right">
                                            <x-form.input.money
                                                name="cleared_amount_total"
                                                value="0"
                                                disabled
                                                row-input
                                                v-model="totals.cleared_amount"
                                                :currency="$currency"
                                                dynamicCurrency="currency"
                                                money-class="text-right disabled-money banking-price-text w-auto position-absolute right-4 ltr:pr-0 rtl:pl-0 text-sm js-conversion-input"
                                                form-group-class="text-right disabled-money"
                                            />
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="w-11/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-bold text-black cursor-pointer">
                                            <span class="px-2 py-1 rounded-xl" :class="difference">
                                                {{ trans('general.difference') }}
                                            </span>
                                        </th>

                                        <td id="difference" class="w-1/12 ltr:pl-6 rtl:pr-0 text-right">
                                            <div class="difference-money">
                                                <x-form.input.money
                                                    name="difference_total"
                                                    value="0"
                                                    disabled
                                                    row-input
                                                    v-model="totals.difference"
                                                    :currency="$currency"
                                                    dynamicCurrency="currency"
                                                    money-class="text-right disabled-money banking-price-text w-auto position-absolute right-4 ltr:pr-0 rtl:pl-0 text-sm js-conversion-input"
                                                    form-group-class="text-right disabled-money"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                @can('update-banking-reconciliations')
                <div class="relative__footer mt-6">
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
                                <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                                <span :class="[{'ml-0': form.loading}]">{{ trans('general.save') }}</span>
                            </x-button>
                            <div v-if="reconcile">
                                <x-tooltip id="tooltip-reconcile" placement="top" message="{{ trans('reconciliations.irreconcilable') }}">
                                    <x-button
                                        type="button"
                                        ::disabled="reconcile || form.loading"
                                        class="relative flex items-center justify-center px-3 py-1.5 ltr:ml-2 rtl:mr-2 text-white text-base rounded-lg bg-blue-300 hover:bg-blue-500 disabled:bg-blue-100"
                                        override="class"
                                        @click="onReconcileSubmit"
                                        data-loading-text="{{ trans('general.loading') }}"
                                    >

                                    <i 
                                        v-if="form.loading"
                                        class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"
                                    >
                                    </i>
                                    <span :class="[{'opacity-1': reconcile}]">{{ trans('reconciliations.reconcile') }}</span>
                                    </x-button>
                                </x-tooltip>
                            </div>
                            <div v-else>
                                <x-button
                                    type="button"
                                    ::disabled="reconcile || form.loading"
                                    class="relative flex items-center justify-center px-3 py-1.5 ltr:ml-2 rtl:mr-2 text-white text-base rounded-lg bg-blue-300 hover:bg-blue-500 disabled:bg-blue-100"
                                    override="class"
                                    @click="onReconcileSubmit"
                                    data-loading-text="{{ trans('general.loading') }}"
                                >

                                    <i 
                                        v-if="form.loading"
                                        class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"
                                    >
                                    </i>
                                    <span :class="[{'opacity-1': reconcile}]">{{ trans('reconciliations.reconcile') }}</span>
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
