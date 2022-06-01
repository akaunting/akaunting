<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.bills', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.bills', 2) }}"
        icon="file_open"
        route="bills.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-documents.index.buttons type="bill" hide-accept-payment />
    </x-slot>

    <x-slot name="moreButtons">
        <x-documents.index.more-buttons type="bill" />
    </x-slot>

    <x-slot name="content">
        <x-documents.index.content type="bill" :documents="$bills" active-tab="bill" />
    </x-slot>

    <x-documents.script type="bill" />
</x-layouts.admin>
