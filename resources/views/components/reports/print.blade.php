<x-layouts.print>
    <x-slot name="title">
        {{ $class->model->name }}
    </x-slot>

    <x-slot name="content">
        <h2>{{ $class->model->name }}</h2>

        {{ setting('company.name') }}

        @include($class->views[$class->type])
    </x-slot>
</x-layouts.print>
