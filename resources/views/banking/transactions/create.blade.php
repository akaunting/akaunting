<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.' . Str::plural($real_type), 1)]) }}
    </x-slot>

    @php $fav_icon = ($real_type == 'income') ? 'request_quote' : 'paid'; @endphp
    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.' . Str::plural($real_type), 1)]) }}"
        icon="{{ $fav_icon }}"
        url="{{ route('transactions.create', ['type' => $real_type]) }}"
    ></x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="transaction" route="transactions.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('transactions.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.date name="paid_at" label="{{ trans('general.date') }}" icon="calendar_today" value="{{ request()->get('paid_at', Date::now()->toDateString()) }}" show-date-format="{{ company_date_format() }}" date-format="Y-m-d" autocomplete="off" />

                        <x-form.group.payment-method />

                        <x-form.group.account />

                        <x-form.group.money name="amount" label="{{ trans('general.amount') }}" value="0" autofocus="autofocus" :currency="$currency" dynamicCurrency="currency" />

                        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />

                        <x-form.input.hidden name="currency_code" :value="$account_currency_code" />
                        <x-form.input.hidden name="currency_rate" value="1" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.assign') }}" description="{{ trans('transactions.form_description.assign_' . $real_type) }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.category :type="$real_type" :selected="setting('default.' . $real_type . '_category')" />

                        <x-form.group.contact :type="$contact_type" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.others', 1) }}" description="{{ trans('transactions.form_description.other') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="number" label="{{ trans_choice('general.numbers', 1) }}" value="{{ $number }}" />

                        <x-form.group.text name="reference" label="{{ trans('general.reference') }}" not-required />

                        <x-form.group.attachment />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="transactions.index" />
                    </x-slot>
                </x-form.section>

                <x-form.input.hidden name="type" :value="$real_type" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="banking" file="transactions" />
</x-layouts.admin>
