<x-layouts.modules>
    <x-slot name="title">
        {{ trans_choice('general.modules', 2) }}
    </x-slot>

    <x-slot name="buttons">
        <x-link href="{{ route('apps.api-key.create') }}">
            {{ trans('modules.api_key') }}
        </x-link>

        <x-link href="{{ route('apps.my.index') }}">
            {{ trans('modules.my_apps') }}
        </x-link>
    </x-slot>

    <x-slot name="content">
        <x-modules.banners />

        <x-modules.pre-sale />

        <x-modules.paid />

        <x-modules.nnew />

        <x-modules.free />
    </x-slot>

    <x-script folder="modules" file="apps" />
</x-layouts.modules>
