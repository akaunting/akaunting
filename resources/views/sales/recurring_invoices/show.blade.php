<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.recurring_invoices', 1) . ': ' . $recurring_invoice->document_number }}
    </x-slot>

    <x-slot name="status">
        <x-show.status status="{{ ! empty($recurring_invoice->recurring) ? $recurring_invoice->recurring->status : 'ended' }}" background-color="bg-{{ $recurring_invoice->recurring_status_label }}" text-color="text-text-{{ $recurring_invoice->recurring_status_label }}" />
    </x-slot>

    <x-slot name="buttons">
        <x-documents.show.buttons type="invoice-recurring" :document="$recurring_invoice" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-documents.show.more-buttons
            type="invoice-recurring"
            :document="$recurring_invoice"
            hide-divider1
            hide-divider2
            hide-divider4
            hide-email
            hide-share
            hide-customize
            hide-print
            hide-pdf
            hide-cancel
            hide-delete
        />
    </x-slot>

    <x-slot name="content">
        <x-documents.show.content type="invoice-recurring" :document="$recurring_invoice" hide-status-message hide-send hide-get-paid hide-receive hide-make-payment />
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-documents.script type="invoice-recurring" />
</x-layouts.admin>
