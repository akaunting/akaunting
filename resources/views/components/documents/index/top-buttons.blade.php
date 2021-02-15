@if ($checkPermissionCreate)
    @can($permissionCreate)
        @if (!$hideCreate)
            <a href="{{ route($createRoute) }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
        @endif

        @if (!$hideImport)
            <a href="{{ route($importRoute, $importRouteParameters) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
        @endif
    @endcan
@else
    @if (!$hideCreate)
        <a href="{{ route($createRoute) }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
    @endif

    @if (!$hideImport)
        <a href="{{ route($importRoute, $importRouteParameters) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
    @endif
@endif

@if (!$hideExport)
    <a href="{{ route($exportRoute, request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endif
