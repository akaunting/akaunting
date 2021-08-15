<div class="accordion" id="notification-recurring-{{$type}}">
    <div class="card">
        <div class="card-header" id="heading-recurring-{{$type}}" data-toggle="collapse" data-target="#collapse-recurring-{{$type}}"
                aria-expanded="false" aria-controls="collapse-recurring-{{$type}}">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="mb-0">
                        {{ trans($textTitle) }}

                        @if ($notifications->total())
                            <button type="button" class="btn btn-outline-success rounded-circle btn-icon-only btn-sm mr-2 d-none"
                                data-toggle="tooltip"
                                data-placement="right"
                                title="{{ trans('notifications.mark_read_all') }}"
                                wire:click="markReadAll()"
                            >
                                <span class="btn-inner--icon"><i class="fas fa-check-double"></i></span>
                            </button>
                        @endif
                    </h3>
                </div>
            </div>
        </div>

        <div id="collapse-recurring-{{$type}}" class="collapse" aria-labelledby="heading-recurring-{{$type}}" data-parent="#notification-recurring-{{$type}}">
            @if ($notifications->total())
                <div class="table-responsive">
                    <table class="table table-flush table-hover" id="tbl-recurring-{{ $type }}">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                @stack('document_number_th_start')
                                @if (!$hideDocumentNumber)
                                    <th class="{{ $classDocumentNumber }}">
                                        @stack('document_number_th_inside_start')

                                        {{ trans_choice($textDocumentNumber, 1) }}

                                        @stack('document_number_th_inside_end')
                                    </th>
                                @endif
                                @stack('document_number_th_end')

                                @stack('contact_name_th_start')
                                @if (!$hideContactName)
                                    <th class="{{ $classContactName }}">
                                        @stack('contact_name_th_inside_start')

                                        {{ trans_choice($textContactName, 1) }}

                                        @stack('contact_name_th_inside_end')
                                    </th>
                                @endif
                                @stack('contact_name_th_end')

                                @stack('amount_th_start')
                                @if (!$hideAmount)
                                    <th class="{{ $classAmount }}">
                                        @stack('amount_th_inside_start')

                                        {{ trans('general.amount') }}

                                        @stack('amount_th_inside_end')
                                    </th>
                                @endif
                                @stack('amount_th_end')

                                @stack('issued_at_th_start')
                                @if (!$hideIssuedAt)
                                    <th class="{{ $classIssuedAt }}">
                                        @stack('issued_at_th_inside_start')

                                        {{ trans($textIssuedAt) }}

                                        @stack('issued_at_th_inside_end')
                                    </th>
                                @endif
                                @stack('issued_at_th_end')

                                @stack('due_at_th_start')
                                @if (!$hideDueAt)
                                    <th class="{{ $classDueAt }}">
                                        @stack('due_at_th_inside_start')

                                        {{ trans($textDueAt) }}

                                        @stack('due_at_th_inside_end')
                                    </th>
                                @endif
                                @stack('due_at_th_end')

                                @stack('status_th_start')
                                @if (!$hideStatus)
                                    <th class="{{ $classStatus }}">
                                        @stack('status_th_inside_start')

                                        {{ trans_choice('general.statuses', 1) }}

                                        @stack('status_th_inside_end')
                                    </th>
                                @endif
                                @stack('status_th_end')

                                @if (!$hideActions)
                                    <th class="{{ $classActions }}">
                                        <a>{{ trans_choice('notifications.reads', 1) }}</a>
                                    </th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($notifications as $item)
                                <tr class="row align-items-center border-top-1">
                                    @stack('document_number_td_start')
                                    @if (!$hideDocumentNumber)
                                        <td class="{{ $classDocumentNumber }}">
                                            @stack('document_number_td_inside_start')
                
                                            <a href="{{ route($routeButtonShow , $item->id) }}" target="_blank">{{ $item->document_number }}</a>
                
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
                                            <button type="button" class="btn btn-outline-success rounded-circle btn-icon-only btn-sm"
                                                data-toggle="tooltip"
                                                data-placement="right"
                                                title="{{ trans('notifications.mark_read') }}"
                                                wire:click="markRead('{{ $item->notification_id }}')"
                                            >
                                                <span class="btn-inner--icon"><i class="fa fa-check"></i></span>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($notifications->total() > 5)
                    <div class="card-footer table-action">
                        <div class="row">
                            @if ($notifications->count())
                                <div class="col-xs-12 col-sm-5 d-flex align-items-center">
                                    {!! Form::select('limit', ['5' => '5'], request('limit', 5), ['class' => 'disabled form-control form-control-sm d-inline-block w-auto d-none d-md-block', 'disabled' => 'disabled']) !!}
                                    <span class="table-text d-none d-lg-block ml-2">
                                        {{ trans('pagination.page') }}
                                        {{ trans('pagination.showing', ['first' => $notifications->firstItem(), 'last' => $notifications->lastItem(), 'total' => $notifications->total()]) }}
                                    </span>
                                </div>

                                <div class="col-xs-12 col-sm-7 pagination-xs">
                                    <nav class="float-right">
                                        {!! $notifications->withPath(request()->url())->withQueryString()->links() !!}
                                    </nav>
                                </div>
                            @else
                                <div class="col-xs-12 col-sm-12" id="datatable-basic_info" role="status" aria-live="polite">
                                    <small>{{ trans('general.no_records') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="col-xs-12 col-sm-12 mt-4 mb-4 text-center">
                    <small>{{ trans('general.no_records') }}</small>
                </div>
            @endif
        </div>
    </div>
</div>

@push('body_js')
    <script type="text/javascript">
        window.addEventListener('mark-read', event => {
            if (event.detail.type == 'recurring-{{ $type }}') {
                $.notify(event.detail.message, {
                    type: 'success',
                });
            }
        });

        window.addEventListener('mark-read-all', event => {
            if (event.detail.type == 'recurring-{{ $type }}') {
                $.notify(event.detail.message, {
                    type: 'success',
                });
            }
        });
    </script>
@endpush
