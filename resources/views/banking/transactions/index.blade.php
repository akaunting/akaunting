<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.transactions', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.transactions', 2) }}"
        icon="receipt_long"
        route="transactions.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-transactions.index.buttons :type="$type" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-transactions.index.more-buttons :type="$type" />
    </x-slot>

    <x-slot name="content">
        <x-transactions.index.content
            :type="$type"
            :transactions="$transactions"
            :total-transactions="$total_transactions"
            :summary-items="$summary_amounts"
        />
    </x-slot>

    <x-transactions.script :type="$type" />
</x-layouts.admin>
