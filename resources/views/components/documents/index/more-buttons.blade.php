<x-dropdown id="dropdown-more-actions">
    <x-slot name="trigger">
        <span class="material-icons">more_horiz</span>
    </x-slot>

    @if ($checkPermissionCreate)
        @can($permissionCreate)
            @if (! $hideImport)
                <x-dropdown.link href="{{ route($importRoute, $importRouteParameters) }}">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endif
        @endcan
    @else
        @if (! $hideImport)
            <x-dropdown.link href="{{ route($importRoute, $importRouteParameters) }}">
                {{ trans('import.import') }}
            </x-dropdown.link>
        @endif
    @endif

    @if (! $hideExport)
        <x-dropdown.link href="{{ route($exportRoute, request()->input()) }}">
            {{ trans('general.export') }}
        </x-dropdown.link>
    @endif
</x-dropdown>
