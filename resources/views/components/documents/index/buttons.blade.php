@if ($checkPermissionCreate)
    @can($permissionCreate)
        @if (! $hideCreate)
            <x-link href="{{ route($createRoute) }}" kind="primary" id="index-more-actions-new-{{ $type }}">
                {{ trans('general.title.new', ['type' => trans_choice($textPage, 1)]) }}
            </x-link>
        @endif
    @endcan
@else
    @if (! $hideCreate)
        <x-link href="{{ route($createRoute) }}" kind="primary" id="index-more-actions-new-{{ $type }}">
            {{ trans('general.title.new', ['type' => trans_choice($textPage, 1)]) }}
        </x-link>
    @endif
@endif
