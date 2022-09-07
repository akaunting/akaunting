@php
    $link_class = 'to-black-400 hover:bg-full-2 bg-no-repeat bg-0-2 bg-0-full bg-gradient-to-b from-transparent transition-backgroundSize';
    $expense_number = '<a href="' . route('transactions.show', $transfer->expense_transaction->id) . '" class="' . $link_class . '" override="class">' . $transfer->expense_transaction->number . '</a>';
    $income_number = '<a href="' . route('transactions.show', $transfer->income_transaction->id) . '" class="' . $link_class . '" override="class">' . $transfer->income_transaction->number . '</a>';
@endphp

<x-show.accordion type="transactions">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans_choice('general.transactions', 2) }}"
            description="{!! trans('transfers.slider.transactions', ['user' => $transfer->owner->name]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="my-2">
            {!! trans('transfers.slider.transactions_desc', ['number' => $expense_number, 'account' => $transfer->expense_account->title]) !!}
        </div>

        <div class="my-2">
            {!! trans('transfers.slider.transactions_desc', ['number' => $income_number, 'account' => $transfer->income_account->title]) !!}
        </div>
    </x-slot>
</x-show.accordion>