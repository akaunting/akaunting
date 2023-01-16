<x-layouts.admin>
    <x-slot name="title">
        {{ setting('invoice.title', trans_choice('general.invoices', 1)) . ': ' . $invoice->document_number }}
    </x-slot>

    <x-slot name="status">
        <x-show.status status="{{ $invoice->status }}" background-color="bg-{{ $invoice->status_label }}" text-color="text-text-{{ $invoice->status_label }}" />
    </x-slot>

    <x-slot name="buttons">
        <x-documents.show.buttons type="invoice" :document="$invoice" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-documents.show.more-buttons type="invoice" :document="$invoice" />
    </x-slot>

    <x-slot name="content">
        <x-documents.show.content type="invoice" :document="$invoice" hide-receive hide-make-payment hide-schedule hide-children />
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-documents.script type="invoice" />
</x-layouts.admin>
