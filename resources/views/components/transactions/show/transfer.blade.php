@if ($transaction->isTransferTransaction())
    @php $transfer = $transaction->transfer; @endphp

    @if ($transfer)
        @php
            $from_account = '<span class="font-medium">' . $transfer->expense_account->title . '</span>';
            $to_account = '<span class="font-medium">' . $transfer->income_account->title . '</span>';
            $date = '<x-link href="' . route('transfers.show', $transfer->id) . '" class="text-purple" override="class">' . company_date($transaction->paid_at) . '</x-link>';
        @endphp
    @endif

    <div class="border-b pb-4" x-data="{ transfer : 1 }">
        <button class="relative w-full text-left cursor-pointer group"
            x-on:click="transfer !== 1 ? transfer = 1 : transfer = null"
        >
            <span class="font-medium border-b border-transparent transition-all group-hover:border-black">
                {{ trans_choice('general.transfers', 1) }}
            </span>

            @if ($transfer)
                <div class="text-black-400 text-sm">
                    {!! trans('transactions.slider.transfer_headline', ['from_account' => $from_account, 'to_account' => $to_account]) !!}
                </div>
            @endif

            <span class="material-icons absolute right-0 top-0 transition-all transform"
                x-bind:class="transfer === 1 ? 'rotate-180' : ''"
            >expand_more</span>
        </button>

        <div class="overflow-hidden transition-transform origin-top-left ease-linear duration-100"
            x-ref="container1"
            x-bind:class="transfer === 1 ? 'h-auto' : 'scale-y-0 h-0'"
        >
            @if ($transfer)
                <div class="my-2">
                    {!! trans('transactions.slider.transfer_desc', ['date' => $date]) !!}
                </div>
            @else
                <div class="mt-2">
                    <div class="alert alert-notify p-4 text-black font-bold rounded-lg bg-orange-100 text-orange-600">
                        <span class="alert-text">
                            <span>{{ trans('messages.warning.missing_transfer') }}</span>
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
