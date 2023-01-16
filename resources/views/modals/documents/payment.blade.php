<x-form id="form-transaction" :method="$method" :route="$route" :model="!empty($transaction) ? $transaction : false">
    <div class="rounded-xl px-5 py-3 mb-5 bg-red-100" v-if="typeof form.response !== 'undefined' && form.response.error">
        <p class="text-sm mb-0 text-red-600" v-html="form.response.message"></p>
    </div>

    <x-tabs active="general" class="grid grid-cols-2" override="class">
        <x-slot name="navs">
            <x-tabs.nav id="general">
                {{ trans('general.general') }}

                <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('paid_at')||form.errors.has('amount')||form.errors.has('payment_method')||form.errors.has('account_id')">
                    {{ trans('general.validation_error') }}
                </span>
            </x-tabs.nav>

            <x-tabs.nav id="other">
                {{ trans_choice('general.others', 1) }}

                <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('number')||form.errors.has('description')||form.errors.has('recurring')">
                    {{ trans('general.validation_error') }}
                </span>
            </x-tabs.nav>
        </x-slot>

        <x-slot name="content">
            <x-tabs.tab id="general">
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                    <x-form.group.date name="paid_at" label="{{ trans('general.date') }}" icon="calendar_today" value="{{ $document->paid_at }}" show-date-format="{{ company_date_format() }}" date-format="Y-m-d" autocomplete="off" form-group-class="col-span-6" />

                    <x-form.group.money name="amount" label="{{ trans('general.amount') }}" value="{{ $document->grand_total }}" autofocus="autofocus" :currency="$currency" dynamicCurrency="currency" form-group-class="col-span-6" />

                    <x-form.group.payment-method form-group-class="col-span-6"/>

                    <x-form.group.account change="onChangePaymentAccount" form-group-class="col-span-6" without-add-new />
                </div>
            </x-tabs.tab>

            <x-tabs.tab id="other">
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                    <x-form.group.textarea name="description" label="{{ trans('general.description') }}" rows="2" not-required form-group-class="col-span-6" />

                    <x-form.group.text name="number" label="{{ trans_choice('general.numbers', 1) }}" value="{{ $number }}" form-group-class="col-span-6" />

                    <x-form.group.text name="reference" label="{{ trans('general.reference') }}" not-required form-group-class="col-span-6" />

                    <x-form.input.hidden name="document_id" :value="$document->id" />
                    <x-form.input.hidden name="category_id" :value="$document->category->id" />
                    <x-form.input.hidden name="amount" :value="$document->grand_total" />
                    <x-form.input.hidden name="currency_code" :value="$document->currency_code" />
                    <x-form.input.hidden name="currency_rate" :value="$document->currency_rate" />
                    <x-form.input.hidden name="type" :value="config('type.document.' . $document->type . '.transaction_type')" />
                </div>
            </x-tabs.tab>
        </x-slot>
    </x-tabs>
</x-form>
