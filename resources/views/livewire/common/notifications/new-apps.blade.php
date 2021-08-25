<div class="accordion" id="notification-new-apps">
    <div class="card">
        <div class="card-header" id="heading-new-apps" data-toggle="collapse" data-target="#collapse-new-apps"
             aria-expanded="{{ ($notifications) ? 'true' : 'false' }}" aria-controls="collapse-new-apps">
            <div class="align-items-center">
                <h3 class="mb-0">
                    {{ trans_choice('notifications.new_apps', 2) }}

                    @if ($notifications)
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

        <div id="collapse-new-apps" class="collapse{{ ($notifications) ? ' show' : '' }}" aria-labelledby="heading-new-apps" data-parent="#notification-new-apps">
            @if ($notifications)
                <div class="table-responsive">
                    <table class="table table-flush table-hover" id="tbl-export">
                        <tbody>
                            @foreach ($notifications as $notification)
                                <tr class="row align-items-center border-top-1">
                                    <td class="col-xs-8 col-sm-10 col-md-10 col-lg-11 col-xl-11 text-left text-wrap">
                                        {!! $notification->message !!}
                                    </td>

                                    <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                        <button type="button" class="btn btn-outline-success rounded-circle btn-icon-only btn-sm"
                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="{{ trans('notifications.mark_read') }}"
                                            wire:click="markRead('{{ $notification->alias }}')"
                                        >
                                            <span class="btn-inner--icon"><i class="fa fa-check"></i></span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
            if (event.detail.type == 'new-apps') {
                $.notify(event.detail.message, {
                    type: 'success',
                });
            }
        });

        window.addEventListener('mark-read-all', event => {
            if (event.detail.type == 'new-apps') {
                $.notify(event.detail.message, {
                    type: 'success',
                });
            }
        });
    </script>
@endpush
