<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.bills', 1) . ': ' . $bill->document_number }}
    </x-slot>

    <x-slot name="status">
        <x-show.status status="{{ $bill->status }}" background-color="bg-{{ $bill->status_label }}" text-color="text-text-{{ $bill->status_label }}" />
    </x-slot>

    <x-slot name="buttons">
        <x-documents.show.buttons type="bill" :document="$bill" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-documents.show.more-buttons
            type="bill"
            :document="$bill"
            hide-email
            hide-share
            hide-divider2
            hide-divider3
            hide-customize
        />
    </x-slot>

    <x-slot name="content">
        <x-documents.show.content
            type="bill"
            :document="$bill"
            hide-send
            hide-get-paid
            hide-email
            hide-share
            hide-accept-payment
            hide-schedule
            hide-children 
        />
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-documents.script type="bill" :document="$bill" />
</x-layouts.admin>
