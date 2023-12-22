<x-form id="form-transaction" :method="$method" :route="$route" :model="!empty($transaction) ? $transaction : false">
    <div class="rounded-xl px-5 py-3 mb-5 bg-red-100" v-if="typeof form.response !== 'undefined' && form.response.error">
        <p class="text-sm mb-0 text-red-600" v-html="form.response.message"></p>
    </div>

    <x-tabs active="general" class="grid grid-cols-2" override="class">
        <x-slot name="navs">
            <x-tabs.nav id="general">
                {{ trans('general.general') }}

                <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('paid_at') || form.errors.has('amount') || form.errors.has('payment_method') || form.errors.has('account_id')">
                    {{ trans('general.validation_error') }}
                </span>
            </x-tabs.nav>

            <x-tabs.nav id="other">
                {{ trans_choice('general.others', 1) }}

                <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('number') || form.errors.has('description') || form.errors.has('recurring')">
                    {{ trans('general.validation_error') }}
                </span>
            </x-tabs.nav>
        </x-slot>

        <x-slot name="content">
            <x-tabs.tab id="general">
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                    <x-form.group.date
                        name="paid_at"
                        label="{{ trans('general.date') }}"
                        icon="calendar_today"
                        value="{{ $document->paid_at }}"
                        show-date-format="{{ company_date_format() }}"
                        date-format="Y-m-d"
                        autocomplete="off"
                        form-group-class="col-span-6"
                    />

                    <x-form.group.account
                        change="onChangePaymentAccount"
                        form-group-class="col-span-6"
                        without-add-new
                    />

                    <x-form.group.money
                        v-show="form.document_currency_code == form.currency_code"
                        name="amount"
                        :label="trans('general.amount')"
                        :value="$amount"
                        autofocus="autofocus"
                        :currency="! empty($transaction) ? $transaction->currency : $currency"
                        form-group-class="col-span-6"
                    />

                    <div class="sm:col-span-6 grid sm:grid-cols-6 gap-x-4 gap-y-6 relative" v-if="form.document_currency_code != form.currency_code">
                        <x-form.group.money
                            name="amount"
                            label="{{ trans('general.amount') }}"
                            :value="$amount"
                            v-model="form.amount"
                            autofocus="autofocus"
                            :currency="! empty($transaction) ? $transaction->currency : $currency"
                            form-group-class="col-span-6"
                            input="onChangeAmount($event)"
                        />

                        <div class="sm:col-span-2 text-xs absolute right-0 top-1">
                            <div class="custom-control custom-checkbox">
                                <x-form.input.checkbox
                                    name="pay_in_full"
                                    label="Pay in full"
                                    id="checkbox-pay_in_full-{{ $document->id }}"
                                    value="1"
                                    checked
                                    data-type="single"
                                    v-model="form.pay_in_full"
                                    @change="onChangePayInFull($event.target.checked)"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-6 grid sm:grid-cols-6 gap-x-4 -mt-2" v-if="form.document_currency_code != form.currency_code">
                        <x-form.group.text
                            name="currency_rate"
                            label="{{ trans_choice('general.currency_rates', 1) }}"
                            form-group-class="col-span-6"
                            ::disabled="form.pay_in_full"
                            not-required
                            @input="onChangeRatePayment($event)"
                        />

                        <div class="relative col-span-6 text-xs flex mt-2">
                            <div class="w-auto text-xs mr-2"
                                v-html="'{{ trans('general.currency_convert', ['from' => '#form', 'to' => $document->currency_code]) }}'.replace('#form', form.currency_code)"
                            ></div>

                            <div class="mr-2">-</div>

                            <x-form.input.money
                                name="default_amount"
                                value="0"
                                disabled
                                row-input
                                v-model="form.default_amount"
                                :currency="$currency"
                                dynamic-currency="{!! json_encode($currency) !!}"
                                money-class="disabled-money p-0 m-0 text-xs"
                                form-group-class="text-right disabled-money"
                            />
                        </div>
                    </div>

                    <x-form.group.payment-method form-group-class="col-span-6"/>
                </div>
            </x-tabs.tab>

            <x-tabs.tab id="other">
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                    <x-form.group.textarea name="description" label="{{ trans('general.description') }}" rows="2" not-required form-group-class="col-span-6" />

                    <x-form.group.text name="number" label="{{ trans_choice('general.numbers', 1) }}" value="{{ $number }}" form-group-class="col-span-6" />

                    <x-form.group.text name="reference" label="{{ trans('general.reference') }}" not-required form-group-class="col-span-6" />

                    <x-form.input.hidden name="document_id" :value="$document->id" />
                    <x-form.input.hidden name="category_id" :value="$document->category->id" />
                    <x-form.input.hidden name="paid_amount" :value="$document->paid_amount" />
                    <x-form.input.hidden name="amount" :value="$amount" />
                    <x-form.input.hidden name="currency_code" :value="! empty($transaction) ? $transaction->currency_code : $document->currency_code" />
                    <x-form.input.hidden name="currency_rate" :value="! empty($transaction) ? $transaction->currency_rate : $document->currency_rate" v-if="form.document_currency_code == form.currency_code" />
                    <x-form.input.hidden name="company_currency_code" :value="default_currency()" />
                    <x-form.input.hidden name="document_currency_code" :value="$document->currency_code" />
                    <x-form.input.hidden name="document_currency_rate" :value="$document->currency_rate" />
                    <x-form.input.hidden name="document_default_amount" :value="$document->grand_total" />
                    <x-form.input.hidden name="type" :value="config('type.document.' . $document->type . '.transaction_type')" />
                </div>
            </x-tabs.tab>
        </x-slot>
    </x-tabs>
</x-form>
