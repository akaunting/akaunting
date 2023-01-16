<x-layouts.admin>
    <x-slot name="title">
        {{ $class->model->name }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ $class->model->name }}"
        icon="{{ $class->icon }}"
        :route="['reports.show', $class->model->id]"
    ></x-slot>

    <x-slot name="buttons">
        <x-link href="{{ url($class->getUrl('print')) }}" target="_blank">
            {{ trans('general.print') }}
        </x-link>

        <x-link href="{{ url($class->getUrl('export')) }}">
            {{ trans('general.export') }}
        </x-link>
    </x-slot>

    <x-slot name="content">
        <div class="my-10">
            @include($class->views['filter'])

            @include($class->views[$class->type])

            <x-loading.content />
        </div>
    </x-slot>

    <x-script folder="common" file="reports" />
</x-layouts.admin>
