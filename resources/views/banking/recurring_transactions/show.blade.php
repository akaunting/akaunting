<x-layouts.admin>
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="status">
        <x-show.status status="{{ $recurring_transaction->recurring->status }}" background-color="bg-{{ $recurring_transaction->recurring_status_label }}" text-color="text-text-{{ $recurring_transaction->recurring_status_label }}" />
    </x-slot>

    <x-slot name="buttons">
        <x-transactions.show.buttons type="{{ $recurring_transaction->type }}" :transaction="$recurring_transaction" hide-divider4 hide-button-delete />
    </x-slot>

    <x-slot name="moreButtons">
        <x-transactions.show.more-buttons
            type="{{ $recurring_transaction->type }}"
            :transaction="$recurring_transaction"
            hide-button-print
            hide-button-pdf
            hide-divider-1
            hide-button-share
            hide-button-email
            hide-divider-2
            hide-divider-4
            hide-button-delete
        />
    </x-slot>

    <x-slot name="content">
        <x-transactions.show.content type="{{ $recurring_transaction->type }}" :transaction="$recurring_transaction" hide-number />
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-transactions.script type="{{ $recurring_transaction->type }}" folder="banking" file="transactions" />
</x-layouts.admin>
