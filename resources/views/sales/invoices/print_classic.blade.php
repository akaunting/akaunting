<x-layouts.print>
    <x-slot name="title">
        {{ trans_choice('general.invoices', 1) . ': ' . $invoice->document_number }}
    </x-slot>

    <x-slot name="content">
        <x-documents.template.classic
            type="invoice"
            :document="$invoice"
        />
    </x-slot>
</x-layouts.print>
