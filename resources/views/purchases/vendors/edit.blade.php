<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.vendors', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-contacts.form.content type="vendor" :model="$vendor" hide-can-login />
    </x-slot>

    <x-contacts.script type="vendor" :model="$vendor" />
</x-layouts.admin>
