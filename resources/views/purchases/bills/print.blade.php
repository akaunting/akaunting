<x-layouts.print>
    <x-slot name="title">
        {{ trans_choice('general.bills', 1) . ': ' . $bill->document_number }}
    </x-slot>

    <x-slot name="content">
        <x-documents.template.ddefault
            type="bill"
            :document="$bill"
            hide-discount
            hide-footer
        />
    </x-slot>
</x-layouts.print>
