<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => setting('invoice.title', trans_choice('general.invoices', 1))]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => setting('invoice.title', trans_choice('general.invoices', 1))]) }}"
        icon="description"
        route="invoices.create"
    ></x-slot>

    <x-slot name="content">
        <x-documents.form.content type="invoice" />
    </x-slot>

    <x-documents.script type="invoice" />
</x-layouts.admin>
