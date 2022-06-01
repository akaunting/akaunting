<x-layouts.admin>
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="buttons">
        <x-transactions.show.buttons type="{{ $transaction->type }}" :transaction="$transaction" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-transactions.show.more-buttons type="{{ $transaction->type }}" :transaction="$transaction" hide-divider-3 hide-button-end />
    </x-slot>

    <x-slot name="content">
        <x-transactions.show.content type="{{ $transaction->type }}" :transaction="$transaction" hide-schedule hide-children />
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-transactions.script type="{{ $transaction->type }}" />
</x-layouts.admin>
