@if ($notifications)
    <div class="accordion" id="new-apps">
        <div class="card">
            <div class="card-header" id="heading-new-apps" data-toggle="collapse" data-target="#collapse-new-apps" aria-expanded="true" aria-controls="collapse-new-apps">
                <div class="align-items-center">
                    <h3 class="mb-0">
                        {{ trans_choice('notifications.new_apps', 2) }}
                    </h3>
                </div>
            </div>

            <div id="collapse-new-apps" class="collapse show" aria-labelledby="heading-new-apps" data-parent="#new-apps">
                <div class="table-responsive">
                    <table class="table table-flush table-hover" id="tbl-export">
                        <tbody>
                            @foreach ($notifications as $notification)
                                <tr class="row align-items-center border-top-1">
                                    <td class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-left">
                                        {!! $notification->message !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
