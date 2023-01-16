<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.customers', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.customers', 2) }}"
        icon="person"
        route="customers.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-contacts.index.buttons type="customer" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-contacts.index.more-buttons type="customer" />
    </x-slot>

    <x-slot name="content">
        <x-contacts.index.content type="customer" :contacts="$customers" />
    </x-slot>

    <x-contacts.script type="customer" />
</x-layouts.admin>
