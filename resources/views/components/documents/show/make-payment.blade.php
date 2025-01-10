<x-show.accordion type="make_payment" :open="($accordionActive == 'make-payment')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('bills.make_payment') }}"
            description="{!! $description !!}"
        />
    </x-slot>

    <x-slot name="body" class="block" override="class">
        @stack('timeline_make_payment_body_start')

        <div class="flex flex-wrap space-x-3 rtl:space-x-reverse">
            @stack('timeline_get_paid_body_button_payment_start')

            @if (! $hideAddPayment)
                @if ($document->totals->count())
                    @if ($document->status != 'paid' && (empty($document->transactions->count()) || (! empty($document->transactions->count()) && $document->paid != $document->amount)) )
                        @if ($document->status != 'cancelled')
                            <x-button
                                @click="onAddPayment('{{ route('modals.documents.document.transactions.create', $document->id) }}')"
                                id="show-slider-actions-payment-{{ $document->type }}"
                                class="px-3 py-1.5 mb-3 sm:mb-0 rounded-lg text-xs font-medium leading-6 bg-green hover:bg-green-700 text-white disabled:bg-green-100"
                                override="class"
                            >
                                {{ trans('invoices.add_payment') }}
                            </x-button>
                        @else
                            <x-button kind="disabled" disabled="disabled">
                                {{ trans('invoices.add_payment') }}
                            </x-button>
                        @endif
                    @endif
                @else
                    <x-tooltip message="{{ trans('invoices.messages.totals_required', ['type' => $type]) }}" placement="top">
                        <x-button kind="disabled" disabled="disabled">
                            {{ trans('invoices.add_payment') }}
                        </x-button>
                    </x-tooltip>
                @endif
            @endif

            @stack('timeline_get_paid_body_button_payment_end')
        </div>

        @stack('timeline_make_payment_body_detail_start')

        <div class="text-xs mt-6" style="margin-left: 0 !important;">
            <span class="font-medium">
                {{ trans('bills.payment_made') }} :
            </span>

            @stack('timeline_make_payment_body_detail_description_start')

            @if ($transactions->count())
                @foreach ($transactions as $transaction)
                    <div class="my-4">
                        <span>
                            <x-date :date="$transaction->paid_at" />
                             - {!! trans('documents.transaction', [
                                 'amount' => '<span class="font-medium">' . money($transaction->amount, $transaction->currency_code) . '</span>',
                                 'account' => '<span class="font-medium">' . $transaction->account->name . '</span>',
                             ]) !!}
                        </span>

                        </br>

                        <div class="flex flex-row">
                            @if (! empty($transaction->contact) && $transaction->contact->email)
                                <x-button id="show-slider-actions-transaction-send-email-{{ $document->type }}-{{ $transaction->id }}" class="text-purple mt-1" override="class" @click="onEmailViaTemplate('{{ route($transactionEmailRoute, $transaction->id) }}', '{{ $transactionEmailTemplate }}')">
                                    <x-button.hover color="to-purple">
                                        {{ trans('general.title.send', ['type' => trans_choice('general.receipts', 1)]) }}
                                    </x-button.hover>
                                </x-button>
                            @else
                                <x-tooltip message="{{ trans('invoices.messages.email_required') }}" placement="top">
                                    <x-button class="text-purple mt-1" override="class" kind="disabled" disabled="disabled">
                                        <x-button.hover color="to-purple">
                                            {{ trans('general.title.send', ['type' => trans_choice('general.receipts', 1)]) }}
                                        </x-button.hover>
                                    </x-button>
                                </x-tooltip>
                            @endif

                            <span class="mt-1 mr-2 ml-2"> - </span>

                            @if ($document->totals->count())
                                <x-button
                                    @click="onEditPayment('{{ route('modals.documents.document.transactions.edit', ['document' => $document->id, 'transaction' => $transaction->id]) }}')"
                                    id="show-slider-actions-transaction-edit-{{ $document->type }}-{{ $transaction->id }}"
                                    class="text-purple mt-1"
                                    override="class"
                                >
                                    <x-button.hover color="to-purple">
                                        {{ trans('general.title.edit', ['type' => trans_choice('general.payments', 1)]) }}
                                    </x-button.hover>
                                </x-button>
                            @else
                                <x-tooltip message="{{ trans('invoices.messages.totals_required', ['type' => $type]) }}" placement="top">
                                    <x-button disabled="disabled" id="show-slider-actions-transaction-edit-{{ $document->type }}-{{ $transaction->id }}" class="text-purple mt-1" override="class">
                                        <x-button.hover color="to-purple">
                                            {{ trans('general.title.edit', ['type' => trans_choice('general.payments', 1)]) }}
                                        </x-button.hover>
                                    </x-button>
                                </x-tooltip>
                            @endif

                            <span class="mt-1 mr-2 ml-2"> - </span>

                            @php
                                $message = trans('general.delete_confirm', [
                                    'name' => '<strong>' . Date::parse($transaction->paid_at)->format(company_date_format()) . ' - ' . money($transaction->amount, $transaction->currency_code) . ' - ' . $transaction->account->name . '</strong>',
                                    'type' => strtolower(trans_choice('general.transactions', 1))
                                ]);
                            @endphp

                            <x-delete-link
                                :model="$transaction"
                                :route="['modals.documents.document.transactions.destroy', $document->id, $transaction->id]"
                                :title="trans('general.title.delete', ['type' => trans_choice('general.payments', 1)])"
                                :message="$message"
                                :label="trans('general.title.delete', ['type' => trans_choice('general.payments', 1)])"
                                class="text-purple mt-1"
                                text-class="bg-no-repeat bg-0-2 bg-0-full hover:bg-full-2 bg-gradient-to-b from-transparent to-purple transition-backgroundSize"
                                override="class"
                            />
                        </div>
                    </div>
                @endforeach
            @else
                <div class="my-2">
                    <span>{{ trans('general.no_records') }}</span>
                </div>
            @endif

            @stack('timeline_make_payment_body_detail_description_end')
        </div>

        @stack('timeline_make_payment_body_end')
    </x-slot>
</x-show.accordion>
