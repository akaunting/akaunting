<x-layouts.print>
    <x-slot name="title">
        {{ trans_choice('general.transactions', 1) . ': ' . $transaction->id }}
    </x-slot>

    <x-slot name="content">
        <x-transactions.template.ddefault type="{{ $real_type }}" :transaction="$transaction" />
    </x-slot>
</x-layout-print>
