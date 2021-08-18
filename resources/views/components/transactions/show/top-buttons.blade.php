@stack('button_group_start')
@if (!$hideButtonMoreActions)
    <div class="dropup header-drop-top">
        <button type="button" class="btn btn-white btn-sm" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-chevron-down"></i>&nbsp; {{ trans('general.more_actions') }}
        </button>

        <div class="dropdown-menu" role="menu">
            @stack('button_dropdown_start')
            @stack('edit_button_start')
            @if (!$transaction->hasTransferRelation)
                @if (!$hideButtonEdit)
                    @can($permissionUpdate)
                        <a class="dropdown-item" href="{{ route($routeButtonEdit, $transaction->id) }}">
                            {{ trans('general.edit') }}
                        </a>
                    @endcan
                @endif
            @endif
            @stack('edit_button_end')

            @stack('duplicate_button_start')
            @if (!$transaction->hasTransferRelation)
                @if (!$hideButtonDuplicate)
                    @can($permissionCreate)
                        <a class="dropdown-item" href="{{ route($routeButtonDuplicate, $transaction->id) }}">
                            {{ trans('general.duplicate') }}
                        </a>
                    @endcan
                @endif
            @endif
            @stack('duplicate_button_end')

            @stack('button_dropdown_divider_1_start')
            @if (!$transaction->hasTransferRelation)
                @if (!$hideButtonGroupDivider1)
                    <div class="dropdown-divider"></div>
                @endif
            @endif
            @stack('button_dropdown_divider_1_end')

            @if (!$hideButtonPrint)
                @stack('button_print_start')
                <a class="dropdown-item" href="{{ route($routeButtonPrint, $transaction->id) }}" target="_blank">
                    {{ trans('general.print') }}
                </a>
                @stack('button_print_end')
            @endif

            @stack('share_button_start')
            @if (!$transaction->hasTransferRelation)
                @if (!$hideButtonShare)
                    <a class="dropdown-item" href="{{ $signedUrl }}" target="_blank">
                        {{ trans('general.share') }}
                    </a>
                @endif
            @endif
            @stack('share_button_end')

            @stack('edit_button_start')
            @if (!$transaction->hasTransferRelation)
                @if (!$hideButtonEmail)
                    @if($transaction->contact->email)
                        <a class="dropdown-item" href="{{ route($routeButtonEmail, $transaction->id) }}">
                            {{ trans('invoices.send_mail') }}
                        </a>
                    @else
                        <el-tooltip content="{{ trans('invoices.messages.email_required') }}" placement="right">
                            <button type="button" class="dropdown-item btn-tooltip">
                                <span class="text-disabled">{{ trans('invoices.send_mail') }}</span>
                            </button>
                        </el-tooltip>
                    @endif
                @endif
            @endif
            @stack('edit_button_end')

            @stack('button_pdf_start')
                @if (!$hideButtonPdf)
                    <a class="dropdown-item" href="{{ route($routeButtonPdf, $transaction->id) }}">
                        {{ trans('general.download_pdf') }}
                    </a>
                @endif
            @stack('button_pdf_end')

            @stack('button_dropdown_divider_3_start')
            @if (!$transaction->hasTransferRelation)
                @if (!$hideButtonGroupDivider3)
                    <div class="dropdown-divider"></div>
                @endif
            @endif
            @stack('button_dropdown_divider_3_end')

            @stack('delete_button_start')
            @if (!$transaction->hasTransferRelation)
                @if (!$hideButtonDelete)
                    @can($permissionDelete)
                        @if ($checkButtonReconciled)
                            @if (!$transaction->reconciled)
                                {!! Form::deleteLink($transaction, $routeButtonDelete, $textDeleteModal, 'transaction_number') !!}
                            @endif
                        @else
                            {!! Form::deleteLink($transaction, $routeButtonDelete, $textDeleteModal, 'transaction_number') !!}
                        @endif
                    @endcan
                @endif
            @endif
            @stack('delete_button_end')
            @stack('button_dropdown_end')
        </div>
    </div>
@endif
@stack('button_group_end')

@stack('add_new_button_start')
@if (!$hideButtonAddNew)
    @can($permissionCreate)
        <a href="{{ route($routeButtonAddNew) }}" class="btn btn-white btn-sm">
            {{ trans('general.add_new') }}
        </a>
    @endcan
@endif
@stack('add_new_button_end')
