<x-dropdown id="dropdown-more-actions">
    <x-slot name="trigger">
        <span class="material-icons pointer-events-none">more_horiz</span>
    </x-slot>

    @if ($checkPermissionCreate)
        @can($permissionCreate)
            @if (! $hideImport)
                <x-dropdown.link href="{{ route($importRoute, $importRouteParameters) }}" id="index-more-actions-import-{{ $type }}">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endif
        @endcan
    @else
        @if (! $hideImport)
            <x-dropdown.link href="{{ route($importRoute, $importRouteParameters) }}" id="index-more-actions-import-{{ $type }}">
                {{ trans('import.import') }}
            </x-dropdown.link>
        @endif
    @endif

    @if (! $hideExport)
        <x-dropdown.link href="{{ route($exportRoute, request()->input()) }}" id="index-more-actions-export-{{ $type }}">
            {{ trans('general.export') }}
        </x-dropdown.link>
    @endif
</x-dropdown>
