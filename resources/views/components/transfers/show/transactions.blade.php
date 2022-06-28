<div class="border-b pb-4" x-data="{ transactions : null }">
    <button class="relative w-full text-left group" x-on:click="transactions !== 1 ? transactions = 1 : transactions = null">
        <span class="font-medium border-b border-transparent transition-all group-hover:border-black">
            {{ trans_choice('general.transactions', 2) }}
        </span>

        <div class="text-black-400 text-sm">
            {!! trans('transfers.slider.transactions', ['user' => $transfer->owner->name]) !!}
        </div>

        <span class="material-icons absolute right-0 top-0 transition-all transform" x-bind:class="transactions === 1 ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div
        class="overflow-hidden transition-transform origin-top-left ease-linear duration-100"
        x-ref="container1"
        x-bind:class="transactions === 1 ? 'h-auto' : 'scale-y-0 h-0'"
    >
        <div class="my-2">
            @php
                $number = '<a href="' . route('transactions.show', $transfer->expense_transaction->id) . '" class="text-purple">' . $transfer->expense_transaction->number . '</a>';
            @endphp
            {!! trans('transfers.slider.from_desc', ['number' => $number, 'account' => $transfer->expense_account->title]) !!}
        </div>

        <div class="my-2">
            @php
                $number = '<a href="' . route('transactions.show', $transfer->income_transaction->id) . '" class="text-purple">' . $transfer->income_transaction->number . '</a>';
            @endphp
            {!! trans('transfers.slider.from_desc', ['number' => $number, 'account' => $transfer->income_account->title]) !!}
        </div>
    </div>
</div>
