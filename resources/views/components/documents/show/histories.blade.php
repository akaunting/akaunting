<div class="accordion">
    <div class="card">
        <div class="card-header" id="accordion-histories-header" data-toggle="collapse" data-target="#accordion-histories-body" aria-expanded="false" aria-controls="accordion-histories-body">
            <h4 class="mb-0">{{ trans($textHistories) }}</h4>
        </div>

        <div id="accordion-histories-body" class="collapse hide" aria-labelledby="accordion-histories-header">
            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        @stack('row_footer_histories_head_tr_start')
                        <tr class="row table-head-line">
                            @stack('row_footer_histories_head_start')
                            <th class="col-xs-4 col-sm-3">
                                {{ trans('general.date') }}
                            </th>
                            <th class="col-xs-4 col-sm-3 text-left">
                                {{ trans_choice('general.statuses', 1) }}
                            </th>
                            <th class="col-xs-4 col-sm-6 text-left long-texts">
                                {{ trans('general.description') }}
                            </th>
                            @stack('row_footer_histories_head_end')
                        </tr>
                        @stack('row_footer_histories_head_tr_end')
                    </thead>

                    <tbody>
                        @stack('row_footer_histories_body_tr_start')
                        @foreach($histories as $history)
                            <tr class="row align-items-center border-top-1 tr-py">
                                @stack('row_footer_histories_body_td_start')
                                <td class="col-xs-4 col-sm-3">
                                    @date($history->created_at)
                                </td>
                                <td class="col-xs-4 col-sm-3 text-left">
                                    {{ trans($textHistoryStatus . $history->status) }}
                                </td>
                                <td class="col-xs-4 col-sm-6 text-left long-texts">
                                    {{ $history->description }}
                                </td>
                                @stack('row_footer_histories_body_td_end')
                            </tr>
                        @endforeach
                        @stack('row_footer_histories_body_tr_end')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
