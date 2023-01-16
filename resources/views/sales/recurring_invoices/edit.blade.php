<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.recurring_invoices', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-documents.form.content type="invoice-recurring" :document="$recurring_invoice" show-recurring hide-send-to />
    </x-slot>

    <x-documents.script type="invoice-recurring" :document="$recurring_invoice" :items="$recurring_invoice->items()->get()" />
</x-layouts.admin>
