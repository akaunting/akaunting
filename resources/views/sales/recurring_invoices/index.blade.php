<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.recurring_templates', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.recurring_templates', 2) }}"
        icon="receipt_long"
        route="recurring-invoices.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-documents.index.buttons type="invoice-recurring" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-documents.index.more-buttons type="invoice-recurring" />
    </x-slot>

    <x-slot name="content">
        <x-documents.index.content type="invoice-recurring"
            page="recurring_templates"
            :documents="$invoices"
            tab-active="recurring-templates"
            hide-summary
            hide-bulk-action
        />
    </x-slot>

    <x-documents.script type="invoice-recurring" />
</x-layouts.admin>
