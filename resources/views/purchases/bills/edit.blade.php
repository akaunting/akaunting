<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.bills', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-documents.form.content
            type="bill"
            :document="$bill"
            hide-company
            hide-footer
            hide-edit-item-columns
            hide-send-to
            is-purchase-price
        />
    </x-slot>

    <x-documents.script type="bill" :document="$bill" :items="$bill->items()->get()" />
</x-layouts.admin>
