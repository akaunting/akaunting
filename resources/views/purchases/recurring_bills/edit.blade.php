<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.recurring_bills', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-documents.form.content
            type="bill-recurring"
            :document="$recurring_bill"
            show-recurring
            hide-company
            hide-footer
            hide-edit-item-columns
            hide-send-to
            is-purchase-price
        />
    </x-slot>

    <x-documents.script type="bill-recurring" :document="$recurring_bill" :items="$recurring_bill->items()->get()" />
</x-layouts.admin>
