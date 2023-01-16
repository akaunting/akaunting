@if ($transaction->isTransferTransaction())
    @php $transfer = $transaction->transfer; @endphp

    @if ($transfer)
        @php
            $from_account = '<span class="font-medium">' . $transfer->expense_account->title . '</span>';
            $to_account = '<span class="font-medium">' . $transfer->income_account->title . '</span>';
            $date = '<a href="' . route('transfers.show', $transfer->id) . '" class="text-purple">' . company_date($transaction->paid_at) . '</a>';
        @endphp
    @endif

    <x-show.accordion type="transfer" open>
        <x-slot name="head">
            <x-show.accordion.head
                title="{{ trans_choice('general.transfers', 1) }}"
            />
            
            @if ($transfer)
                <div class="text-black-400 text-sm space-y-3 mt-1">
                    {!! trans('transactions.slider.transfer_headline', ['from_account' => $from_account, 'to_account' => $to_account]) !!}
                </div>
            @endif
        </x-slot>

        <x-slot name="body">
            @if ($transfer)
                    <div class="my-2">
                        {!! trans('transactions.slider.transfer_desc', ['date' => $date]) !!}
                    </div>
                @else
                    <div class="mt-2">
                        <div class="alert alert-notify p-4 font-bold rounded-lg bg-orange-100 text-orange-600">
                            <span class="alert-text">
                                <span>{{ trans('messages.warning.missing_transfer') }}</span>
                            </span>
                        </div>
                    </div>
            @endif
        </x-slot>
    </x-show.accordion>
@endif
