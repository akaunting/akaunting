<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.recurring_invoices', 1)]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.recurring_invoices', 1)]) }}"
        icon="request_quote"
        url="{{ route('recurring-invoices.create') }}"
    ></x-slot>

    <x-slot name="content">
        <x-documents.form.content type="invoice-recurring" show-recurring hide-send-to />
    </x-slot>

    <x-documents.script type="invoice-recurring" />
</x-layouts.admin>
