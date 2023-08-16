<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.recurring_templates', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.recurring_templates', 2) }}"
        icon="receipt_long"
        route="recurring-bills.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-documents.index.buttons type="bill-recurring" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-documents.index.more-buttons type="bill-recurring" hide-cancelled />
    </x-slot>

    <x-slot name="content">
        <x-documents.index.content type="bill-recurring"
            page="recurring_templates"
            :documents="$bills"
            tab-active="recurring-templates"
            hide-summary
            hide-bulk-action
        />
    </x-slot>

    <x-documents.script type="bill-recurring" />
</x-layouts.admin>
