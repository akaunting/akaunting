<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.recurring_' . Str::plural($real_type), 1)]) }}
    </x-slot>

    @php $fav_icon = ($real_type == 'income') ? 'request_quote' : 'paid'; @endphp
    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.recurring_' . Str::plural($real_type), 1)]) }}"
        icon="{{ $fav_icon }}"
        url="{{ route('recurring-transactions.create', ['type' => $type]) }}"
    ></x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="transaction" route="recurring-transactions.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('transactions.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="relative sm:col-span-3">
                            <x-form.label>
                                {{ trans('general.date') }}
                            </x-form.label>

                            <x-tooltip id="tooltip-paid" placement="bottom" message="{{ trans('documents.recurring.tooltip.document_date', ['type' => Str::lower(trans_choice('general.transactions', 1))]) }}">
                                <div class="relative focused has-label">
                                    <x-form.input.text name="disabled_transaction_paid" value="{{ trans('documents.recurring.auto_generated') }}" disabled />
                                </div>
                            </x-tooltip>

                            <x-form.input.hidden name="paid_at" :value="Date::now()" />
                        </div>

                        <x-form.group.money name="amount" label="{{ trans('general.amount') }}" value="0" autofocus="autofocus" :currency="$currency" dynamicCurrency="currency" />

                        <x-form.group.account />

                        <x-form.group.payment-method />

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
                        <x-form.group.category type="{{ $real_type }}" :selected="setting('default.' . $real_type . '_category')" />

                        <x-form.group.contact :type="$contact_type" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.schedules', 1) }}" description="{{ trans('recurring.form_description.schedule', ['type' => Str::lower(trans_choice('general.transactions', 1))]) }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.recurring type="transaction" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.others', 1) }}" description="{{ trans('transactions.form_description.other') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="relative sm:col-span-3">
                            <x-form.label>
                                {{ trans_choice('general.numbers', 1) }}
                            </x-form.label>

                            <x-tooltip id="tooltip-number" placement="bottom" message="{{ trans('documents.recurring.tooltip.document_number', ['type' => Str::lower(trans_choice('general.transactions', 1))]) }}">
                                <div class="relative focused has-label">
                                    <x-form.input.text name="disabled_transaction_number" value="{{ trans('documents.recurring.auto_generated') }}" disabled />
                                </div>
                            </x-tooltip>

                            <x-form.input.hidden name="number" value="{{ $number }}" />
                        </div>

                        <x-form.group.text name="reference" label="{{ trans('general.reference') }}" not-required />

                        <x-form.group.attachment />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="recurring-transactions.index" />
                    </x-slot>
                </x-form.section>

                <x-form.input.hidden name="type" :value="$type" />
                <x-form.input.hidden name="real_type" :value="$real_type" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="banking" file="transactions" />
</x-layouts.admin>
