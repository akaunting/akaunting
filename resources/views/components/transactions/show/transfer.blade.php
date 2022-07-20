@if ($transaction->isTransferTransaction())
    @php
        $from_account = '<span class="font-medium">' . $transaction->transfer->expense_account->title . '</span>';
        $to_account = '<span class="font-medium">' . $transaction->transfer->income_account->title . '</span>';
    @endphp

    <div class="border-b pb-4" x-data="{ transfer : null }">
        <button class="relative w-full text-left cursor-pointer group"
            x-on:click="transfer !== 1 ? transfer = 1 : transfer = null"
        >
            <span class="font-medium border-b border-transparent transition-all group-hover:border-black">
                {{ trans_choice('general.transfers', 1) }}
            </span>

            <div class="text-black-400 text-sm">
                {!! trans('transactions.slider.transfer_headline', ['from_account' => $from_account, 'to_account' => $to_account]) !!}
            </div>

            <span class="material-icons absolute right-0 top-0 transition-all transform"
                x-bind:class="transfer === 1 ? 'rotate-180' : ''"
            >expand_more</span>
        </button>

        <div class="overflow-hidden transition-transform origin-top-left ease-linear duration-100"
            x-ref="container1"
            x-bind:class="transfer === 1 ? 'h-auto' : 'scale-y-0 h-0'"
        >
            @php
                $date = '<a href="' . route('transfers.show', $transaction->transfer->id) . '" class="text-purple">' . company_date($transaction->paid_at) . '</a>';
            @endphp

            <div class="my-2">
                {!! trans('transactions.slider.transfer_desc', ['date' => $date]) !!}
            </div>
        </div>
    </div>
@endif
