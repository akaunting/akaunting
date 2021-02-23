@stack('button_group_start')
@if (!$hideButtonMoreActions)
    <div class="dropup header-drop-top">
        <button type="button" class="btn btn-white btn-sm" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-chevron-down"></i>&nbsp; {{ trans('general.more_actions') }}
        </button>

        <div class="dropdown-menu" role="menu">
            @stack('button_dropdown_start')
            @if (in_array($document->status, $hideTimelineStatuses))
                @stack('edit_button_start')
                    @if (!$hideButtonEdit)
                        @can($permissionUpdate)
                            <a class="dropdown-item" href="{{ route($routeButtonEdit, $document->id) }}">
                                {{ trans('general.edit') }}
                            </a>
                        @endcan
                    @endif
                @stack('edit_button_end')
            @endif

            @stack('duplicate_button_start')
                @if (!$hideButtonDuplicate)
                    @can($permissionCreate)
                        <a class="dropdown-item" href="{{ route($routeButtonDuplicate, $document->id) }}">
                            {{ trans('general.duplicate') }}
                        </a>
                    @endcan
                @endif
            @stack('duplicate_button_end')

            @stack('button_dropdown_divider_1_start')
                @if (!$hideButtonGroupDivider1)
                    <div class="dropdown-divider"></div>
                @endif
            @stack('button_dropdown_divider_1_end')

            @if (!$hideButtonPrint)
                @if ($checkButtonCancelled)
                    @if ($document->status != 'cancelled')
                        @stack('button_print_start')
                        <a class="dropdown-item" href="{{ route($routeButtonPrint, $document->id) }}" target="_blank">
                            {{ trans('general.print') }}
                        </a>
                        @stack('button_print_end')
                    @endif
                @else
                    @stack('button_print_start')
                    <a class="dropdown-item" href="{{ route($routeButtonPrint, $document->id) }}" target="_blank">
                        {{ trans('general.print') }}
                    </a>
                    @stack('button_print_end')
                @endif
            @endif

            @if (in_array($document->status, $hideTimelineStatuses))
                @stack('share_button_start')
                    @if (!$hideButtonShare)
                        @if ($document->status != 'cancelled')
                            <a class="dropdown-item" href="{{ $signedUrl }}" target="_blank">
                                {{ trans('general.share') }}
                            </a>
                        @endif
                    @endif
                @stack('share_button_end')

                @stack('edit_button_start')
                    @if (!$hideButtonEmail)
                        @if($document->contact_email)
                            <a class="dropdown-item" href="{{ route($routeButtonEmail, $document->id) }}">
                                {{ trans($textTimelineSendStatusMail) }}
                            </a>
                        @else
                            <el-tooltip content="{{ trans('invoices.messages.email_required') }}" placement="right">
                                <button type="button" class="dropdown-item btn-tooltip">
                                    <span class="text-disabled">{{ trans($textTimelineSendStatusMail) }}</span>
                                </button>
                            </el-tooltip>
                        @endif
                    @endif
                @stack('edit_button_end')
            @endif

            @stack('button_pdf_start')
                @if (!$hideButtonPdf)
                    <a class="dropdown-item" href="{{ route($routeButtonPdf, $document->id) }}">
                        {{ trans('general.download_pdf') }}
                    </a>
                @endif
            @stack('button_pdf_end')

            @if (!$hideButtonCancel)
                @can($permissionUpdate)
                    @if ($checkButtonCancelled)
                        @if ($document->status != 'cancelled')
                            @stack('button_cancelled_start')
                            <a class="dropdown-item" href="{{ route($routeButtonCancelled, $document->id) }}">
                                {{ trans('general.cancel') }}
                            </a>
                            @stack('button_cancelled_end')
                        @endif
                    @else
                        @stack('button_cancelled_start')
                        <a class="dropdown-item" href="{{ route($routeButtonCancelled, $document->id) }}">
                            {{ trans('general.cancel') }}
                        </a>
                        @stack('button_cancelled_end')
                    @endif
                @endcan
            @endif

            @stack('button_dropdown_divider_2_start')
                @if (!$hideButtonGroupDivider2)
                    <div class="dropdown-divider"></div>
                @endif
            @stack('button_dropdown_divider_2_end')

            @if (!$hideButtonCustomize)
                @can($permissionButtonCustomize)
                    @stack('button_customize_start')
                    <a class="dropdown-item" href="{{ route($routeButtonCustomize) }}">
                        {{ trans('general.customize') }}
                    </a>
                    @stack('button_customize_end')
                @endcan
            @endif

            @stack('button_dropdown_divider_3_start')
                @if (!$hideButtonGroupDivider3)
                    <div class="dropdown-divider"></div>
                @endif
            @stack('button_dropdown_divider_3_end')

            @stack('delete_button_start')
                @if (!$hideButtonDelete)
                    @can($permissionDelete)
                        @if ($checkButtonReconciled)
                            @if (!$document->reconciled)
                                {!! Form::deleteLink($document, $routeButtonDelete, $textDeleteModal, 'document_number') !!}
                            @endif
                        @else
                            {!! Form::deleteLink($document, $routeButtonDelete, $textDeleteModal, 'document_number') !!}
                        @endif
                    @endcan
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
