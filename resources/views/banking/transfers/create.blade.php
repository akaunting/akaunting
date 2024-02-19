<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.transfers', 1)]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.transfers', 1)]) }}"
        icon="sync_alt"
        route="transfers.create"
    ></x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="transfer" route="transfers.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('transfers.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select name="from_account_id" label="{{ trans('transfers.from_account') }}" :options="$accounts" change="onChangeFromAccount" />

                        <x-form.group.select name="to_account_id" label="{{ trans('transfers.to_account') }}" :options="$accounts" change="onChangeToAccount" />

                        <div v-if="show_rate" class="sm:col-span-3">
                            <x-form.input.hidden name="from_currency_code" v-model="form.from_currency_code" />

                            <x-form.group.text name="from_account_rate" label="{{ trans('transfers.from_account_rate') }}" v-disabled="form.from_currency_code == '{{ default_currency() }}'" />
                        </div>

                        <div v-if="show_rate" class="sm:col-span-3">
                            <x-form.input.hidden name="to_currency_code" v-model="form.to_currency_code" />

                            <x-form.group.text name="to_account_rate" label="{{ trans('transfers.to_account_rate') }}" v-disabled="form.to_currency_code == '{{ default_currency() }}'" />
                        </div>

                        <x-form.group.date name="transferred_at" label="{{ trans('general.date') }}" icon="calendar_today" value="{{ Date::now()->toDateString() }}" show-date-format="{{ company_date_format() }}" date-format="Y-m-d" autocomplete="off" />

                        <x-form.group.money name="amount" label="{{ trans('general.amount') }}" value="0" :currency="$currency" dynamicCurrency="currency" />

                        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.others', 1) }}" description="{{ trans('transfers.form_description.other') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.payment-method />

                        <x-form.group.text name="reference" label="{{ trans('general.reference') }}" not-required />

                        <x-form.group.attachment />

                        <x-form.input.hidden name="currency_code" v-model="form.currency_code" />
                        <x-form.input.hidden name="currency_rate" v-model="form.currency_rate" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="transfers.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    @push('scripts_start')
        <script type="text/javascript">
            if (typeof aka_currency !== 'undefined') {
                aka_currency = {!! json_encode(! empty($currency) ? $currency : config('money.currencies.' . company()->currency)) !!};
            } else {
                var aka_currency = {!! json_encode(! empty($currency) ? $currency : config('money.currencies.' . company()->currency)) !!};
            }
        </script>
    @endpush

    <x-script folder="banking" file="transfers" />
</x-layouts.admin>
