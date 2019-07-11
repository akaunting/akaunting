<div class="box-body">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-sm-2">&nbsp;</th>
                    @foreach($dates as $date)
                        <th class="col-sm-2 text-right">{{ trans('reports.quarter.' . $date) }}</th>
                    @endforeach
                    <th class="col-sm-2 text-right">{{ trans_choice('general.totals', 1) }}</th>
                </tr>
            </thead>
        </table>
        <table class="table table-hover" style="margin-top: 40px">
            <thead>
                <tr>
                    <th class="col-sm-2" colspan="6">{{ trans_choice('general.incomes', 1) }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compares['income'] as $category_id => $category)
                    <tr>
                        <td class="col-sm-2">{{ $income_categories[$category_id] }}</td>
                        @foreach($category as $i => $item)
                            @php $gross['income'][$i] += $item['amount']; @endphp
                            <td class="col-sm-2 text-right">@money($item['amount'], setting('general.default_currency'), true)</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="col-sm-2">{{ trans('reports.gross_profit') }}</th>
                    @foreach($gross['income'] as $item)
                        <th class="col-sm-2 text-right">@money($item, setting('general.default_currency'), true)</th>
                    @endforeach
                </tr>
            </tfoot>
        </table>
        <table class="table table-hover" style="margin-top: 40px">
            <thead>
                <tr>
                    <th class="col-sm-2" colspan="6">{{ trans_choice('general.expenses', 2) }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compares['expense'] as $category_id => $category)
                    <tr>
                        <td class="col-sm-2">{{ $expense_categories[$category_id] }}</td>
                        @foreach($category as $i => $item)
                            @php $gross['expense'][$i] += $item['amount']; @endphp
                            <td class="col-sm-2 text-right">@money($item['amount'], setting('general.default_currency'), true)</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="col-sm-2">{{ trans('reports.total_expenses') }}</th>
                    @foreach($gross['expense'] as $item)
                        <th class="col-sm-2 text-right">@money($item, setting('general.default_currency'), true)</th>
                    @endforeach
                </tr>
            </tfoot>
        </table>
        <table class="table" style="margin-top: 40px">
            <tbody>
                <tr>
                    <th class="col-sm-2" colspan="6">{{ trans('reports.net_profit') }}</th>
                    @foreach($totals as $total)
                        <th class="col-sm-2 text-right"><span>@money($total['amount'], $total['currency_code'], true)</span></th>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
