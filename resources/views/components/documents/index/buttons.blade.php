@if ($checkPermissionCreate)
    @can($permissionCreate)
        @if (! $hideCreate)
            <x-link href="{{ route($createRoute) }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice($textPage, 1)]) }}
            </x-link>
        @endif
    @endcan
@else
    @if (! $hideCreate)
        <x-link href="{{ route($createRoute) }}" kind="primary">
            {{ trans('general.title.new', ['type' => trans_choice($textPage, 1)]) }}
        </x-link>
    @endif
@endif
