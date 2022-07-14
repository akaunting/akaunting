<x-show.accordion type="get_paid" :open="($accordionActive == 'get-paid')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('invoices.get_paid') }}"
            description="{!! $description !!}"
        />
    </x-slot>

    <x-slot name="body" class="block" override="class">
        <div class="flex flex-wrap space-x-3 rtl:space-x-reverse">
            @stack('timeline_get_paid_body_button_payment_start')

            @if (! $hideAddPayment)
                @if(empty($document->transactions->count()) || (! empty($document->transactions->count()) && $document->paid != $document->amount))
                    <x-button
                        @click="onPayment"
                        id="button-payment"
                        class="px-3 py-1.5 mb-3 sm:mb-0 rounded-lg text-xs font-medium leading-6 bg-green hover:bg-green-700 text-white disabled:bg-green-100"
                        override="class"
                    >
                        {{ trans('invoices.add_payment') }}
                    </x-button>
                @endif
            @endif

            @stack('timeline_get_paid_body_button_payment_end')

            @if (! $hideAcceptPayment)
                <x-link href="{{ route('apps.categories.show', [
                        'alias' => 'payment-method',
                        'utm_source' => $type,
                        'utm_medium' => 'app',
                        'utm_campaign' => 'payment_method',
                    ]) }}"
                    override="class"
                    class="py-1.5 mb-3 sm:mb-0 text-xs bg-transparent hover:bg-transparent font-medium leading-6"
                >
                    <x-link.hover>
                        {{ trans('documents.accept_payment_online') }}
                    </x-link.hover>
                </x-link>
            @endif
        </div>

        <div class="text-xs mt-4" style="margin-left: 0 !important;">
            <span class="font-medium">
                {{ trans('invoices.payment_received') }} :
            </span>

            @if ($transactions->count())
                @foreach ($transactions as $transaction)
                    <div class="my-2">
                        <span>
                            <x-date :date="$transaction->paid_at" />
                             - {!! trans('documents.transaction', [
                                 'amount' => '<span class="font-medium">' . money($transaction->amount, $transaction->currency_code, true) . '</span>',
                                 'account' => '<span class="font-medium">' . $transaction->account->name . '</span>',
                             ]) !!}
                        </span>

                        </br>

                        @if (! empty($transaction->contact) && $transaction->contact->email)
                            <x-button id="button-email-send" class="text-purple mt-1" override="class" @click="onEmailViaTemplate('{{ route($transactionEmailRoute, $transaction->id) }}', '{{ $transactionEmailTemplate }}')">
                                <x-button.hover color="to-purple">
                                    {{ trans('general.title.send', ['type' => trans_choice('general.receipts', 1)]) }}
                                </x-button.hover>
                            </x-button>
                        @else
                            <x-tooltip message="{{ trans('invoices.messages.email_required') }}" placement="top">
                                <x-button class="text-purple mt-1" override="class" disabled="disabled">
                                    <x-button.hover color="to-purple">
                                        {{ trans('general.title.send', ['type' => trans_choice('general.receipts', 1)]) }}
                                    </x-button.hover>
                                </x-button>
                            </x-tooltip>
                        @endif

                        <span> - </span>

                        <x-button
                            @click="onEditPayment('{{ $transaction->id }}')"
                            id="button-edit-payment"
                            class="text-purple mt-1"
                            override="class"
                        >
                            <x-button.hover color="to-purple">
                                {{ trans('general.title.edit', ['type' => trans_choice('general.payments', 1)]) }}
                            </x-button.hover>
                        </x-button>

                        <span> - </span>

                        @php
                            $message = trans('general.delete_confirm', [
                                'name' => '<strong>' . Date::parse($transaction->paid_at)->format(company_date_format()) . ' - ' . money($transaction->amount, $transaction->currency_code, true) . ' - ' . $transaction->account->name . '</strong>',
                                'type' => strtolower(trans_choice('general.transactions', 1))
                            ]);
                        @endphp

                        <x-delete-link
                            :model="$transaction"
                            :route="'transactions.destroy'"
                            :title="trans('general.title.delete', ['type' => trans_choice('general.payments', 1)])"
                            :message="$message"
                            :label="trans('general.title.delete', ['type' => trans_choice('general.payments', 1)])"
                            class="text-purple mt-1"
                            text-class="bg-no-repeat bg-0-2 bg-0-full hover:bg-full-2 bg-gradient-to-b from-transparent to-purple transition-backgroundSize"
                            override="class"
                        />
                    </div>
                @endforeach
            @else
                <div class="my-2">
                    <span>{{ trans('general.no_records') }}</span>
                </div>
            @endif
        </div>
    </x-slot>
</x-show.accordion>
