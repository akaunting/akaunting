<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.vendors', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.vendors', 2) }}"
        icon="engineering"
        route="vendors.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-contacts.index.buttons type="vendor" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-contacts.index.more-buttons type="vendor" />
    </x-slot>

    <x-slot name="content">
        <x-contacts.index.content type="vendor" :contacts="$vendors" show-logo />
    </x-slot>

    <x-contacts.script type="vendor" />
</x-layouts.admin>
