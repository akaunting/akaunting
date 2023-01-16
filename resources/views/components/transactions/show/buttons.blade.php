@stack('add_new_button_start')

@if (! $hideButtonAddNew)
    @can($permissionCreate)
        <x-link href="{{ route($routeButtonAddNew, ['type' => $type]) }}" kind="primary" id="show-more-actions-new-{{ $transaction->type }}">
            {{ trans($textButtonAddNew) }}
        </x-link>
    @endcan
@endif

@stack('edit_button_start')

@if (! $transaction->reconciled && $transaction->isNotTransferTransaction())
    @if (! $hideButtonEdit)
        @can($permissionUpdate)
            <x-link href="{{ route($routeButtonEdit, [$transaction->id, 'type' => $type]) }}" id="show-more-actions-edit-{{ $transaction->type }}">
                {{ trans('general.edit') }}
            </x-link>
        @endcan
    @endif
@endif

@stack('edit_button_end')
