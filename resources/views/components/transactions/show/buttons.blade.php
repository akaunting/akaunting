@stack('add_new_button_start')

@if (! $hideButtonAddNew)
    @can($permissionCreate)
        <x-link href="{{ route($routeButtonAddNew, ['type' => $type]) }}"  kind="primary">
            {{ trans($textButtonAddNew) }}
        </x-link>
    @endcan
@endif

@stack('edit_button_start')

@if (! $transaction->hasTransferRelation)
    @if (! $hideButtonEdit)
        @can($permissionUpdate)
            <x-link href="{{ route($routeButtonEdit, [$transaction->id, 'type' => $type]) }}">
                {{ trans('general.edit') }}
            </x-link>
        @endcan
    @endif
@endif

@stack('edit_button_end')
