<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.accounts', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.accounts', 2) }}"
        icon="account_balance"
        route="accounts.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-accounts.index.buttons />
    </x-slot>

    <x-slot name="content">
        <x-accounts.index.content :accounts="$accounts" />
    </x-slot>

    <x-script folder="banking" file="accounts" />
</x-layouts.admin>
