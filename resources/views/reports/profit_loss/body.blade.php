<div class="box-body">
    <div class="table table-responsive">
        <table class="table" id="tbl-profit-loss">
            <thead>
            <tr>
                <th class="col-md-2">&nbsp;</th>
                @foreach($dates as $date)
                    <th class="col-md-2 text-right">{{ trans('reports.quarter.' . $date) }}</th>
                @endforeach
                <th class="col-md-2 text-right">{{ trans_choice('general.totals', 1) }}</th>
            </tr>
            </thead>
            <tbody>
            @if ($compares)
                <table class="table">
                    <thead>
                        <th class="col-md-2" colspan="6">{{ trans_choice('general.incomes', 2) }}</th>
                    </thead>
                    <tbody>
                        @foreach($compares['income'] as $category_id => $category)
                            <tr>
                                <td>{{ $income_categories[$category_id] }}</td>

                                @foreach($category as $item)
                                    <td class="col-md-2 text-right">@money($item['amount'], $item['currency_code'], true)</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                        <th class="col-md-2" colspan="6">{{ trans_choice('general.expenses', 2) }}</th>
                    </thead>
                    <tbody>
                        @foreach($compares['expense'] as $category_id => $category)
                            <tr>
                                <td>{{ $expense_categories[$category_id] }}</td>

                                @foreach($category as $item)
                                    <td class="col-md-2 text-right">@money($item['amount'], $item['currency_code'], true)</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <tr>
                    <td colspan="13">
                        <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    </td>
                </tr>
            @endif
            </tbody>
            <table class="table">
                <tbody>
                    <tr>
                        <th class="col-md-2" colspan="6">{{ trans('reports.net_profit') }}</th>
                        @foreach($totals as $total)
                            <th class="col-md-2 text-right"><span>@money($total['amount'], $total['currency_code'], true)</span></th>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </table>
    </div>
</div>
