@stack('button_group_start')
@if (!$hideButtonMoreActions)
    <div class="dropup header-drop-top">
        <button type="button" class="btn btn-white btn-sm" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-chevron-down"></i>&nbsp; {{ trans('general.more_actions') }}
        </button>

        <div class="dropdown-menu" role="menu">
            @stack('button_dropdown_start')
            @stack('edit_button_start')
                @if (!$hideButtonEdit)
                    @can($permissionUpdate)
                        <a class="dropdown-item" href="{{ route($routeButtonEdit, $transfer->id) }}">
                            {{ trans('general.edit') }}
                        </a>
                    @endcan
                @endif
            @stack('edit_button_end')

            @stack('duplicate_button_start')
                @if (!$hideButtonDuplicate)
                    @can($permissionCreate)
                        <a class="dropdown-item" href="{{ route($routeButtonDuplicate, $transfer->id) }}">
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
                @stack('button_print_start')
                <a class="dropdown-item" href="{{ route($routeButtonPrint, $transfer->id) }}" target="_blank">
                    {{ trans('general.print') }}
                </a>
                @stack('button_print_end')
            @endif

            @stack('button_pdf_start')
                @if (!$hideButtonPdf)
                    <a class="dropdown-item" href="{{ route($routeButtonPdf, $transfer->id) }}">
                        {{ trans('general.download_pdf') }}
                    </a>
                @endif
            @stack('button_pdf_end')

            @stack('button_dropdown_divider_2_start')
                @if (!$hideButtonGroupDivider2)
                    <div class="dropdown-divider"></div>
                @endif
            @stack('button_dropdown_divider_2_end')

            @if (!$hideButtonTemplate)
                @stack('button_template_start')
                <button type="button" class="dropdown-item" @click="onTemplate">
                    {{ trans('general.form.choose', ['field' => trans_choice('general.templates', 1)]) }}
                </button>
                @stack('button_template_end')
            @endif

            @stack('button_dropdown_divider_3_start')
                @if (!$hideButtonGroupDivider3)
                    <div class="dropdown-divider"></div>
                @endif
            @stack('button_dropdown_divider_3_end')

            @stack('delete_button_start')
                @if (!$hideButtonDelete)
                    @can($permissionDelete)
                        {!! Form::deleteLink($transfer, $routeButtonDelete, $textDeleteModal, 'transfer_number') !!}
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
