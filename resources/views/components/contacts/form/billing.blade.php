<x-form.section>
    <x-slot name="head">
        <x-form.section.head
            title="{{ trans($textSectionBillingTitle) }}"
            description="{{ trans($textSectionBillingDescription) }}"
        />
    </x-slot>

    <x-slot name="body">
        @if (! $hideTaxNumber)
            <x-form.group.text name="tax_number" label="{{ trans($textTaxNumber) }}" not-required />
        @endif

        @if (! $hideCurrency)
            <x-form.group.currency />
        @endif
    </x-slot>
</x-form.section>
