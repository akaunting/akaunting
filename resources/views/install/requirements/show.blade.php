<x-layouts.install>
    <x-slot name="title">
        {{ trans('install.steps.requirements') }}
    </x-slot>

    <x-slot name="content"></x-slot>

    @push('scripts_start')
    <script type="text/javascript">
        var flash_requirements = {!! ($requirements) ? json_encode($requirements) : '[]' !!};
    </script>
    @endpush
</x-layouts.install>
