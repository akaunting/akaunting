@extends('layouts.admin')

@section('title', trans('general.dashboard'))

@section('content')
    <div class="row">
        <!---Income-->
        <div class="col-md-4">
            <div class="info-box">
                @if ($auth_user->can('read-reports-income-summary'))
                <a href="{{ url('reports/income-summary') }}"><span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span></a>
                @else
                <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
                @endif

                <div class="info-box-content">
                    <span class="info-box-text">{{ trans('dashboard.total_incomes') }}</span>
                    <span class="info-box-number">@money($total_incomes['total'], setting('general.default_currency'), true)</span>
                    <div class="progress-group" title="{{ trans('dashboard.open_invoices') }}: {{ $total_incomes['open_invoice'] }}<br>{{ trans('dashboard.overdue_invoices') }}: {{ $total_incomes['overdue_invoice'] }}" data-toggle="tooltip" data-html="true">
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-aqua" style="width: {{ $total_incomes['progress'] }}%"></div>
                        </div>
                        <span class="progress-text">{{ trans('dashboard.receivables') }}</span>
                        <span class="progress-number">{{ $total_incomes['open_invoice'] }} / {{ $total_incomes['overdue_invoice'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!---Expense-->
        <div class="col-md-4">
            <div class="info-box">
                @if ($auth_user->can('read-reports-expense-summary'))
                <a href="{{ url('reports/expense-summary') }}"><span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span></a>
                @else
                <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>
                @endif

                <div class="info-box-content">
                    <span class="info-box-text">{{ trans('dashboard.total_expenses') }}</span>
                    <span class="info-box-number">@money($total_expenses['total'], setting('general.default_currency'), true)</span>

                    <div class="progress-group" title="{{ trans('dashboard.open_bills') }}: {{ $total_expenses['open_bill'] }}<br>{{ trans('dashboard.overdue_bills') }}: {{ $total_expenses['overdue_bill'] }}" data-toggle="tooltip" data-html="true">
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-red" style="width: {{ $total_expenses['progress'] }}%"></div>
                        </div>
                        <span class="progress-text">{{ trans('dashboard.payables') }}</span>
                        <span class="progress-number">{{ $total_expenses['open_bill'] }} / {{ $total_expenses['overdue_bill'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!---Profit-->
        <div class="col-md-4">
            <div class="info-box">
                @if ($auth_user->can('read-reports-income-expense-summary'))
                <a href="{{ url('reports/income-expense-summary') }}"><span class="info-box-icon bg-green"><i class="fa fa-heart"></i></span></a>
                @else
                <span class="info-box-icon bg-green"><i class="fa fa-heart"></i></span>
                @endif

                <div class="info-box-content">
                    <span class="info-box-text">{{ trans('dashboard.total_profit') }}</span>
                    <span class="info-box-number">@money($total_profit['total'], setting('general.default_currency'), true)</span>

                    <div class="progress-group" title="{{ trans('dashboard.open_profit') }}: {{ $total_profit['open'] }}<br>{{ trans('dashboard.overdue_profit') }}: {{ $total_profit['overdue'] }}" data-toggle="tooltip" data-html="true">
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-green" style="width: {{ $total_profit['progress'] }}%"></div>
                        </div>
                        <span class="progress-text">{{ trans('general.upcoming') }}</span>
                        <span class="progress-number">{{ $total_profit['open'] }} / {{ $total_profit['overdue'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!---Income, Expense and Profit Line Chart-->
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.cash_flow') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" id="cashflow-monthly" class="btn btn-default btn-sm">{{ trans('general.monthly') }}</button>&nbsp;&nbsp;
                        <button type="button" id="cashflow-quarterly" class="btn btn-default btn-sm">{{ trans('general.quarterly') }}</button>&nbsp;&nbsp;
                        <input type="hidden" name="period" id="period" value="month" />
                        <div class="btn btn-default btn-sm">
                            <div id="cashflow-range" class="pull-right">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body" id="cashflow">
                    {!! $cashflow->render() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.incomes_by_category') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    {!! $donut_incomes->render() !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.expenses_by_category') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    {!! $donut_expenses->render() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Account Balance List-->
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.account_balance') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @if ($accounts->count())
                        <table class="table table-striped">
                            <tbody>
                                @foreach($accounts as $item)
                                <tr>
                                    <td class="text-left">{{ $item->name }}</td>
                                    <td class="text-right">@money($item->balance, $item->currency_code, true)</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    @endif
                </div>
            </div>
        </div>

        <!-- Latest Incomes List-->
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.latest_incomes') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @if ($latest_incomes->count())
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-left">{{ trans('general.date') }}</th>
                            <th class="text-left">{{ trans_choice('general.categories', 1) }}</th>
                            <th class="text-right">{{ trans('general.amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($latest_incomes as $item)
                            <tr>
                                <td class="text-left">{{ Date::parse($item->paid_at)->format($date_format) }}</td>
                                <td class="text-left">{{ $item->category ? $item->category->name : trans_choice('general.invoices', 2) }}</td>
                                <td class="text-right">@money($item->amount, $item->currency_code, true)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    @endif
                </div>
            </div>
        </div>

        <!-- Latest Expenses List-->
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.latest_expenses') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @if ($latest_expenses->count())
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-left">{{ trans('general.date') }}</th>
                            <th class="text-left">{{ trans_choice('general.categories', 1) }}</th>
                            <th class="text-right">{{ trans('general.amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($latest_expenses as $item)
                            <tr>
                                <td class="text-left">{{ Date::parse($item->paid_at)->format($date_format) }}</td>
                                <td class="text-left">{{ $item->category ? $item->category->name : trans_choice('general.bills', 2) }}</td>
                                <td class="text-right">@money($item->amount, $item->currency_code, true)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/daterangepicker.css') }}" />
@endpush

@push('js')
{!! Charts::assets() !!}
<script type="text/javascript" src="{{ asset('public/js/moment/moment.js') }}"></script>
@if (is_file(base_path('public/js/moment/locale/' . strtolower(app()->getLocale()) . '.js')))
<script type="text/javascript" src="{{ asset('public/js/moment/locale/' . strtolower(app()->getLocale()) . '.js') }}"></script>
@elseif (is_file(base_path('public/js/moment/locale/' . language()->getShortCode() . '.js')))
<script type="text/javascript" src="{{ asset('public/js/moment/locale/' . language()->getShortCode() . '.js') }}"></script>
@endif
<script type="text/javascript" src="{{ asset('public/js/daterangepicker/daterangepicker.js') }}"></script>
@endpush

@push('scripts')
<script type="text/javascript">
    $(function() {
        var start = moment('{{ $financial_start }}');
        var end = moment('{{ $financial_start }}').add('1', 'years').subtract('1', 'days');

        function cb(start, end) {
            $('#cashflow-range span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
        }

        $('#cashflow-range').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '{{ trans("reports.this_year") }}': [start, end],
                '{{ trans("reports.previous_year") }}': [moment('{{ $financial_start }}').subtract(1, 'year'), moment('{{ $financial_start }}').subtract('1', 'days')],
                '{{ trans("reports.this_quarter") }}': [moment().startOf('quarter'), moment().endOf('quarter')],
                '{{ trans("reports.previous_quarter") }}': [moment().subtract(1, 'quarter').startOf('quarter'), moment().subtract(1, 'quarter').endOf('quarter')],
                '{{ trans("reports.last_12_months") }}': [moment().subtract(11, 'months').startOf('month'), moment().endOf('month')]
            }
        }, cb);

        cb(start, end);
    });

    $(document).ready(function () {
        $('#cashflow-range').on('apply.daterangepicker', function(ev, picker) {
            var period = $('#period').val();

            range = getRange(picker);

            $.ajax({
                url: '{{ url("common/dashboard/cashflow") }}',
                type: 'get',
                dataType: 'html',
                data: 'period=' + period + '&start=' + picker.startDate.format('YYYY-MM-DD') + '&end=' + picker.endDate.format('YYYY-MM-DD') + '&range=' + range,
                success: function(data) {
                    $('#cashflow').html(data);
                }
            });
        });

        $('#cashflow-monthly').on('click', function() {
            var picker = $('#cashflow-range').data('daterangepicker');

            $('#period').val('month');

            range = getRange(picker);

            $.ajax({
                url: '{{ url("common/dashboard/cashflow") }}',
                type: 'get',
                dataType: 'html',
                data: 'period=month&start=' + picker.startDate.format('YYYY-MM-DD') + '&end=' + picker.endDate.format('YYYY-MM-DD') + '&range=' + range,
                success: function(data) {
                    $('#cashflow').html(data);
                }
            });
        });

        $('#cashflow-quarterly').on('click', function() {
            var picker = $('#cashflow-range').data('daterangepicker');

            $('#period').val('quarter');

            range = getRange(picker);

            $.ajax({
                url: '{{ url("common/dashboard/cashflow") }}',
                type: 'get',
                dataType: 'html',
                data: 'period=quarter&start=' + picker.startDate.format('YYYY-MM-DD') + '&end=' + picker.endDate.format('YYYY-MM-DD') + '&range=' + range,
                success: function(data) {
                    $('#cashflow').html(data);
                }
            });
        });
    });

    function getRange(picker) {
        ranges = {
            '{{ trans("reports.this_year") }}': 'custom',
            '{{ trans("reports.previous_year") }}': 'custom',
            '{{ trans("reports.this_quarter") }}': 'this_quarter',
            '{{ trans("reports.previous_quarter") }}': 'previous_quarter',
            '{{ trans("reports.last_12_months") }}': 'last_12_months'
        };

        range = 'custom';

        if (ranges[picker.chosenLabel] != undefined) {
            range = ranges[picker.chosenLabel];
        }

        return range;
    }
</script>
@endpush
