<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.recurring_templates', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.recurring_templates', 2) }}"
        icon="receipt_long"
        route="recurring-transactions.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-transactions.index.buttons type="income-recurring" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-transactions.index.more-buttons type="income-recurring" />
    </x-slot>

    <x-slot name="content">
        <x-transactions.index.content
            type="income-recurring"
            page="recurring_templates"
            :transactions="$transactions"
            tab-active="recurring-templates"
            hide-summary
            hide-bulk-action
        />
    </x-slot>

    <x-transactions.script type="income-recurring" />
</x-layouts.admin>
