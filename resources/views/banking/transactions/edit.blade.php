<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.' . Str::plural($type), 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-slot name="head">
                @if (($recurring = $transaction->recurring) && ($next = $recurring->getNextRecurring()))
                    <div class="media mb-3">
                        <div class="media-body">
                            <div class="media-comment-text">
                                <div class="d-flex">
                                    <h5 class="mt-0">{{ trans('recurring.recurring') }}</h5>
                                </div>

                                <p class="text-sm lh-160 mb-0">
                                    {{
                                        trans('recurring.message', [
                                            'type' => mb_strtolower(trans_choice('general.transactions', 1)),
                                            'date' => $next->format($date_format)
                                        ])
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </x-slot>

            <x-form id="transaction" method="PATCH" :route="['transactions.update', $transaction->id]" :model="$transaction">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('transactions.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.date name="paid_at" label="{{ trans('general.date') }}" icon="calendar_today" value="{{ Date::parse($transaction->paid_at)->toDateString() }}" show-date-format="{{ company_date_format() }}" date-format="Y-m-d" autocomplete="off" />

                        <x-form.group.payment-method />

                        <x-form.group.account />

                        <x-form.group.money name="amount" label="{{ trans('general.amount') }}" :value="$transaction->amount" autofocus="autofocus" :currency="$currency" dynamicCurrency="currency" />

                        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />

                        <x-form.input.hidden name="currency_code" :value="$transaction->currency_code" />
                        <x-form.input.hidden name="currency_rate" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.assign') }}" description="{{ trans('transactions.form_description.assign_' . $type) }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.category :type="$type" />

                        <x-form.group.contact :type="$contact_type" not-required />

                        @if ($transaction->document)
                            <x-form.group.text name="document" label="{{ trans_choice('general.' . Str::plural(config('type.transaction.' . $type . '.document_type')), 1) }}" not-required disabled value="{{ $transaction->document->document_number }}" />

                            <x-form.input.hidden name="document_id" :value="$transaction->document->id" />
                        @endif
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.others', 1) }}" description="{{ trans('transactions.form_description.other') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="number" label="{{ trans_choice('general.numbers', 1) }}" />

                        <x-form.group.text name="reference" label="{{ trans('general.reference') }}" not-required />

                        <x-form.group.attachment />
                    </x-slot>
                </x-form.section>

                @can('update-banking-transactions')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="transactions.index" />
                    </x-slot>
                </x-form.section>
                @endcan

                <x-form.input.hidden name="type" :value="$transaction->type" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="banking" file="transactions" />
</x-layouts.admin>
