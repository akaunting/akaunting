@if (! $hideCreate)
    @if ($checkPermissionCreate)
        @can($permissionCreate)
            <x-link href="{{ route($createRoute) }}" kind="primary" id="index-more-actions-new-account">
                {{ trans('general.title.new', ['type' => trans_choice($textCreate, 1)]) }}
            </x-link>
        @endcan
    @else
        <x-link href="{{ route($createRoute) }}" kind="primary" id="index-more-actions-new-account">
            {{ trans('general.title.new', ['type' => trans_choice($textCreate, 1)]) }}
        </x-link>
    @endif
@endif
