<x-layouts.admin>
    <x-slot name="title">
        {{ $vendor->name }}
    </x-slot>

    <x-slot name="info">
        @if (! $vendor->enabled)
            <x-index.disable text="{{ trans_choice('general.vendors', 1) }}" />
        @endif
    </x-slot>

    <x-slot name="favorite"
        title="{{ $vendor->name }}"
        icon="engineering"
        :route="['vendors.show', $vendor->id]"
    ></x-slot>

    <x-slot name="buttons">
        <x-contacts.show.buttons type="vendor" :model="$vendor" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-contacts.show.more-buttons type="vendor" :model="$vendor" />
    </x-slot>

    <x-slot name="content">
        <x-contacts.show.content type="vendor" :model="$vendor" hide-user />
    </x-slot>

    <x-contacts.script type="vendor" />
</x-layouts.admin>
