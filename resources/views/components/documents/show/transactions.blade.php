<div class="accordion">
    <div class="card">
        <div class="card-header" id="accordion-transactions-header" data-toggle="collapse" data-target="#accordion-transactions-body" aria-expanded="false" aria-controls="accordion-transactions-body">
            <h4 class="mb-0">{{ trans_choice('general.transactions', 2) }}</h4>
        </div>

        <div id="accordion-transactions-body" class="collapse hide" aria-labelledby="accordion-transactions-header">
            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        @stack('row_footer_transactions_head_tr_start')
                            <tr class="row table-head-line">
                                @stack('row_footer_transactions_head_td_start')
                                    @php $class = 'col-sm-3'; @endphp
                                    @cannot($permissionTransactionDelete)
                                        @php $class = 'col-sm-4'; @endphp
                                    @endcan

                                    <th class="col-xs-4 {{ $class }}">
                                        {{ trans('general.date') }}
                                    </th>

                                    <th class="col-xs-4 {{ $class }}">
                                        {{ trans('general.amount') }}
                                    </th>

                                    <th class="{{ $class }} d-none d-sm-block">
                                        {{ trans_choice('general.accounts', 1) }}
                                    </th>

                                    @can($permissionTransactionDelete)
                                        <th class="col-xs-4 col-sm-3">
                                            {{ trans('general.actions') }}
                                        </th>
                                    @endcan
                                @stack('row_footer_transactions_head_td_end')
                            </tr>
                        @stack('row_footer_transactions_head_tr_end')
                    </thead>

                    <tbody>
                        @stack('row_footer_transactions_body_tr_start')
                            @if ($transactions->count())
                                @foreach($transactions as $transaction)
                                    <tr class="row align-items-center border-top-1 tr-py">
                                        @stack('row_footer_transactions_body_td_start')
                                            <td class="col-xs-4 {{ $class }}">
                                                @date($transaction->paid_at)
                                            </td>

                                            <td class="col-xs-4 {{ $class }}">
                                                @money($transaction->amount, $transaction->currency_code, true)
                                            </td>

                                            <td class="{{ $class }} d-none d-sm-block">
                                                {{ $transaction->account->name }}
                                            </td>

                                            @can($permissionTransactionDelete)
                                                <td class="col-xs-4 col-sm-3 py-0">
                                                    @if ($transaction->reconciled)
                                                        <button type="button" class="btn btn-default btn-sm">
                                                            {{ trans('reconciliations.reconciled') }}
                                                        </button>
                                                    @else
                                                        @php $message = trans('general.delete_confirm', [
                                                            'name' => '<strong>' . Date::parse($transaction->paid_at)->format($date_format) . ' - ' . money($transaction->amount, $transaction->currency_code, true) . ' - ' . $transaction->account->name . '</strong>',
                                                            'type' => strtolower(trans_choice('general.transactions', 1))
                                                            ]);
                                                        @endphp

                                                        {!! Form::button(trans('general.delete'), array(
                                                            'type'    => 'button',
                                                            'class'   => 'btn btn-danger btn-sm',
                                                            'title'   => trans('general.delete'),
                                                            '@click'  => 'confirmDelete("' . route('transactions.destroy', $transaction->id) . '", "' . trans_choice('general.transactions', 2) . '", "' . $message. '",  "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                                                        )) !!}
                                                    @endif
                                                </td>
                                            @endcan
                                        @stack('row_footer_transactions_body_td_end')
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        <div class="text-muted nr-py" id="datatable-basic_info" role="status" aria-live="polite">
                                            {{ trans('general.no_records') }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @stack('row_footer_transactions_body_tr_end')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
