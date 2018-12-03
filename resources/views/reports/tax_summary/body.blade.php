<div class="box-body">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 120px;">&nbsp;</th>
                    @foreach($dates as $date)
                        <th class="text-right">{{ $date }}</th>
                    @endforeach
                </tr>
            </thead>
        </table>
        @if ($taxes)
            @foreach($taxes as $tax_name)
            <table class="table table-hover" style="margin-top: 40px">
                <thead>
                    <tr>
                        <th style="width: 120px;" colspan="13">{{ $tax_name }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 120px;">{{ trans_choice('general.incomes', 2) }}</td>
                        @foreach($incomes[$tax_name] as $tax_date)
                            <td class="text-right">@money($tax_date['amount'], setting('general.default_currency'), true)</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td style="width: 120px;">{{ trans_choice('general.expenses', 2) }}</td>
                        @foreach($expenses[$tax_name] as $tax_date)
                            <td class="text-right">@money($tax_date['amount'], setting('general.default_currency'), true)</td>
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="width: 120px;">{{ trans('reports.net') }}</th>
                        @foreach($totals[$tax_name] as $tax_date)
                            <th class="text-right">@money($tax_date['amount'], setting('general.default_currency'), true)</th>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
            @endforeach
        @else
            <table class="table table-bordered table-striped table-hover" style="margin-top: 40px">
                <tbody>
                    <tr>
                        <td colspan="13">
                            <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
</div>