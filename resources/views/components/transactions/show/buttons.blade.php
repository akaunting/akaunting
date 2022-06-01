@stack('add_new_button_start')

@if (! $hideButtonAddNew)
    @can($permissionCreate)
        <x-link href="{{ route($routeButtonAddNew, ['type' => $type]) }}"  kind="primary">
            {{ trans($textButtonAddNew) }}
        </x-link>
    @endcan
@endif

@stack('add_new_button_end')
