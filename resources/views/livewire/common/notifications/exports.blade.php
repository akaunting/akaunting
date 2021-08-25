<div class="accordion" id="notification-exports">
    <div class="card">
        <div class="card-header" id="heading-exports" data-toggle="collapse" data-target="#collapse-exports"
                aria-expanded="false" aria-controls="collapse-exports">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="mb-0">
                        {{ trans('general.export') }}

                        @if ($notifications->total())
                            <button type="button" class="btn btn-outline-success rounded-circle btn-icon-only btn-sm ml-2 d-none"
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

        <div id="collapse-exports" class="collapse" aria-labelledby="heading-exports" data-parent="#notification-exports">
            @if ($notifications->total())
                <div class="table-responsive">
                    <table class="table table-flush table-hover" id="tbl-export">
                        <tbody>
                            @foreach ($notifications as $notification)
                                <tr class="row align-items-center border-top-1">
                                    <td class="col-xs-8 col-sm-10 col-md-10 col-lg-11 col-xl-11 text-left text-wrap">
                                        @if (empty($notification->message))
                                            {!! trans('notifications.messages.export', [
                                                'type' => $notification->translation,
                                                'file_name' => $notification->file_name,
                                                'url' => $notification->download_url
                                            ]) !!}
                                        @else
                                            {!! $notification->message !!}
                                        @endif
                                    </td>

                                    <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                        <button type="button" class="btn btn-outline-success rounded-circle btn-icon-only btn-sm"
                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="{{ trans('notifications.mark_read') }}"
                                            wire:click="markRead('{{ $notification->notification_id }}')"
                                        >
                                            <span class="btn-inner--icon"><i class="fa fa-check"></i></span>
                                        </button>
                                    </td>
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
            if (event.detail.type == 'export') {
                $.notify(event.detail.message, {
                    type: 'success',
                });
            }
        });

        window.addEventListener('mark-read-all', event => {
            if (event.detail.type == 'export') {
                $.notify(event.detail.message, {
                    type: 'success',
                });
            }
        });
    </script>
@endpush
