<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.recurring_bills', 1)]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.recurring_bills', 1)]) }}"
        icon="request_quote"
        url="{{ route('recurring-bills.create') }}"
    ></x-slot>

    <x-slot name="content">
        <x-documents.form.content 
            type="bill-recurring"
            show-recurring
            hide-company
            hide-footer
            hide-edit-item-columns
            hide-send-to
            is-purchase-price
        />
    </x-slot>

    <x-documents.script type="bill-recurring" />
</x-layouts.admin>
