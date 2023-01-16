<x-form.section>
    <x-slot name="head">
        <x-form.section.head
            title="{{ trans($textSectionAddressTitle) }}"
            description="{{ trans($textSectionAddressDescription) }}"
        />
    </x-slot>

    <x-slot name="body">
        @if (! $hideAddress)
            <x-form.group.textarea name="address" label="{{ trans($textAddress) }}" not-required v-model="form.address" />
        @endif

        @if (! $hideCity)
            <x-form.group.text name="city" label="{{ trans_choice($textCity, 1) }}" not-required />
        @endif

        @if (! $hideZipCode)
            <x-form.group.text name="zip_code" label="{{ trans($textZipCode) }}" not-required />
        @endif

        @if (! $hideState)
            <x-form.group.text name="state" label="{{ trans($textState) }}" not-required />
        @endif

        @if (! $hideCountry)
            <x-form.group.country form-group-class="sm:col-span-3 el-select-tags-pl-38" not-required />
        @endif
    </x-slot>
</x-form.section>
