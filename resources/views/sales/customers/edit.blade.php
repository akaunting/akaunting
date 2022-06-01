<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.customers', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-contacts.form.content type="customer" :model="$customer" hide-logo />
    </x-slot>

    <x-contacts.script type="customer" />
</x-layouts.admin>
