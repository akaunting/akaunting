@php $paid = $item->paid; @endphp

<tr class="row align-items-center border-top-1">
    @if (!$hideBulkAction)
        <td class="{{ $classBulkAction }}">
            {{ Form::bulkActionGroup($item->id, $item->document_number) }}
        </td>
    @endif

    @stack('document_number_td_start')
    @if (!$hideDocumentNumber)
        <td class="{{ $classDocumentNumber }}">
            @stack('document_number_td_inside_start')

            <a class="col-aka" href="{{ route($routeButtonShow , $item->id) }}">{{ $item->document_number }}</a>

            @stack('document_number_td_inside_end')
        </td>
    @endif
    @stack('document_number_td_end')

    @stack('contact_name_td_start')
    @if (!$hideContactName)
        <td class="{{ $classContactName }}">
            @stack('contact_name_td_inside_start')

            {{ $item->contact_name }}

            @stack('contact_name_td_inside_end')
        </td>
    @endif
    @stack('contact_name_td_end')

    @stack('amount_td_start')
    @if (!$hideAmount)
        <td class="{{ $classAmount }}">
            @stack('amount_td_inside_start')

            @money($item->amount, $item->currency_code, true)

            @stack('amount_td_inside_end')
        </td>
    @endif
    @stack('amount_td_end')

    @stack('issued_at_td_start')
    @if (!$hideIssuedAt)
        <td class="{{ $classIssuedAt }}">
            @stack('issued_at_td_inside_start')

            @date($item->issued_at)

            @stack('issued_at_td_inside_end')
        </td>
    @endif
    @stack('issued_at_td_end')

    @stack('due_at_td_start')
    @if (!$hideDueAt)
        <td class="{{ $classDueAt }}">
            @stack('due_at_td_inside_start')

            @date($item->due_at)

            @stack('due_at_td_inside_end')
        </td>
    @endif
    @stack('due_at_td_end')

    @stack('status_td_start')
    @if (!$hideStatus)
        <td class="{{ $classStatus }}">
            @stack('status_td_inside_start')

            <span class="badge badge-pill badge-{{ $item->status_label }}">{{ trans($textDocumentStatus . $item->status) }}</span>

            @stack('status_td_inside_end')
        </td>
    @endif
    @stack('status_td_end')

    @if (!$hideActions)
        <td class="{{ $classActions }}">
            <div class="dropdown">
                <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h text-muted"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    @stack('show_button_start')
                    @if (!$hideButtonShow)
                        <a class="dropdown-item" href="{{ route($routeButtonShow, $item->id) }}">{{ trans('general.show') }}</a>
                    @endif
                    @stack('show_button_end')

                    @stack('edit_button_start')
                    @if (!$hideButtonEdit)
                        @if ($checkButtonReconciled)
                            @if (!$item->reconciled)
                                <a class="dropdown-item" href="{{ route($routeButtonEdit, $item->id) }}">{{ trans('general.edit') }}</a>
                            @endif
                        @else
                            <a class="dropdown-item" href="{{ route($routeButtonEdit, $item->id) }}">{{ trans('general.edit') }}</a>
                        @endif
                    @endif
                    @stack('edit_button_end')

                    @if ($checkButtonCancelled)
                        @if ($item->status != 'cancelled')
                            @stack('duplicate_button_start')
                            @if (!$hideButtonDuplicate)
                                @can($permissionCreate)
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route($routeButtonDuplicate, $item->id) }}">{{ trans('general.duplicate') }}</a>
                                @endcan
                            @endif
                            @stack('duplicate_button_end')

                            @stack('cancel_button_start')
                            @if (!$hideButtonCancel)
                                @can($permissionUpdate)
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route($routeButtonCancelled, $item->id) }}">{{ trans('general.cancel') }}</a>
                                @endcan
                            @endif
                            @stack('cancel_button_end')
                        @endif
                    @else
                        @stack('duplicate_button_start')
                        @if (!$hideButtonDuplicate)
                            @can($permissionCreate)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route($routeButtonDuplicate, $item->id) }}">{{ trans('general.duplicate') }}</a>
                            @endcan
                        @endif
                        @stack('duplicate_button_end')

                        @stack('cancel_button_start')
                        @if (!$hideButtonCancel)
                            @can($permissionUpdate)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route($routeButtonCancelled, $item->id) }}">{{ trans('general.cancel') }}</a>
                            @endcan
                        @endif
                        @stack('cancel_button_end')
                    @endif

                    @stack('delete_button_start')
                    @if (!$hideButtonDelete)
                        @can($permissionDelete)
                            @if ($checkButtonReconciled)
                                @if (!$item->reconciled)
                                    {!! Form::deleteLink($item, $routeButtonDelete, $textModalDelete, $valueModalDelete) !!}
                                @endif
                            @else
                                {!! Form::deleteLink($item, $routeButtonDelete, $textModalDelete, $valueModalDelete) !!}
                            @endif
                        @endcan
                    @endif
                    @stack('delete_button_end')
                </div>
            </div>
        </td>
    @endif
</tr>
