<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.invoices', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.invoices', 2) }}"
        icon="description"
        route="invoices.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-documents.index.buttons type="invoice" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-documents.index.more-buttons type="invoice" />
    </x-slot>

    <x-slot name="content">
        <x-documents.index.content type="invoice" :documents="$invoices" :total-documents="$total_invoices" />
    </x-slot>

    <x-documents.script type="invoice" />
</x-layouts.admin>
