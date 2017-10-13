@extends('layouts.admin')

@section('title', trans('general.dashboard'))

@section('content')
    <div class="row">
        <!---Income-->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

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
                <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>

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
                <span class="info-box-icon bg-green"><i class="fa fa-heart"></i></span>

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
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right ui-sortable-handle">
                    <li class=""><a href="#monthly-chart" data-toggle="tab" aria-expanded="false">{{ trans('general.monthly') }}</a></li>
                    <li class="active"><a href="#daily-chart" data-toggle="tab" aria-expanded="true">{{ trans('general.daily') }}</a></li>
                    <li class="pull-left header" style="font-size: 18px;">{{ trans('dashboard.cash_flow') }}</li>
                </ul>

                <div class="tab-content no-padding">
                    <div class="chart tab-pane active" id="daily-chart" style="position: relative; height: 300px;">
                        <div class="row">
                            <div class="chart">
                                <canvas id="cash_flow_daily" style="height: 246px; width: 1069px;" height="246" width="1069"></canvas>
                            </div>
                        </div>

                        <div class="row daily-footer">
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div id="sale"></div>
                                    </div>
                                    <div class="col-md-8 scp">
                                        {{ trans_choice('general.incomes', 1) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div id="cost"></div>
                                    </div>
                                    <div class="col-md-8 scp">
                                        {{ trans_choice('general.expenses', 1) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div id="profit"></div>
                                    </div>
                                    <div class="col-md-8 scp">
                                        {{ trans_choice('general.profits', 1) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chart tab-pane" id="monthly-chart" style="position: relative; height: 300px;">
                        @if ($cash_flow['monthly'])
                            <div class="col-md-2">
                                <div style="margin : 7px 0px; border-left: 3px solid #00c0ef; padding-left: 10px;">
                                    <p style="font-size: 16px; margin: 0px;">
                                        @money($total_incomes['total'], setting('general.default_currency'), true)
                                    </p>
                                    {{ trans_choice('general.incomes', 1) }}
                                </div>
                                <div style="margin : 7px 0px; border-left: 3px solid #C9302C; padding-left: 10px;">
                                    <p style="font-size: 16px; margin: 0px;">
                                        @money($total_expenses['total'], setting('general.default_currency'), true)
                                    </p>
                                    {{ trans_choice('general.expenses', 1) }}
                                </div>
                                <div style="margin : 7px 0px; border-left: 3px solid #00a65a; padding-left: 10px;">
                                    <p style="font-size: 16px; margin: 0px;">
                                        @money($total_incomes['total'] - $total_expenses['total'], setting('general.default_currency'), true)
                                    </p>
                                    {{ trans_choice('general.profits', 1) }}
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div id="cash_flow_monthly" style="min-width: 800px; height: 300px; margin: 0 auto"></div>
                            </div>
                        @else
                            <h5 class="text-center">{{ trans('dashboard.no_profit_loss') }}</h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.incomes_by_category') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="income_category" height="155" width="328" style="width: 328px; height: 155px;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                @foreach ($incomes as $item)
                                    <li><i class="fa fa-circle" style="color:{{ $item['color'] }};"></i> {{ $item['amount'] . ' ' . $item['label'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.expenses_by_category') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="expense_category" height="155" width="328" style="width: 328px; height: 155px;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                @foreach ($expenses as $item)
                                    <li><i class="fa fa-circle" style="color:{{ $item['color'] }};"></i> {{ $item['amount'] . ' ' . $item['label'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Account Balance List-->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.account_balance') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
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

            <!-- Latest Incomes List-->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.latest_incomes') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
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

            <!-- Latest Expenses List-->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('dashboard.latest_expenses') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
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

@section('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('public/js/highchart/highcharts.js') }}"></script>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var areaChartData = {
                labels: {!! $cash_flow['daily']['date'] !!},
                datasets: [
                    {
                        label: "{{ trans_choice('general.incomes', 1) }}",
                        fillColor: "#00c0ef",
                        strokeColor: "#00c0ef",
                        pointColor: "#00c0ef",
                        pointStrokeColor: "#00c0ef",
                        pointHighlightFill: "#FFF",
                        pointHighlightStroke: "#00c0ef",
                        data: {!! $cash_flow['daily']['income'] !!}
                    },
                    {
                        label: "{{ trans_choice('general.expenses', 1) }}",
                        fillColor: "#F56954",
                        strokeColor: "#F56954",
                        pointColor: "#F56954",
                        pointStrokeColor: "#F56954",
                        pointHighlightFill: "#FFF",
                        pointHighlightStroke: "#F56954",
                        data: {!! $cash_flow['daily']['expense'] !!}
                    },
                    {
                        label: "{{ trans_choice('general.profits', 1) }}",
                        fillColor: "#6da252",
                        strokeColor: "#6da252",
                        pointColor: "#6da252",
                        pointStrokeColor: "#6da252",
                        pointHighlightFill: "#FFF",
                        pointHighlightStroke: "#6da252",
                        data: {!! $cash_flow['daily']['profit'] !!}
                    }
                ]
            };

            var areaChartOptions = {
                showScale: true,
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: true,
                scaleShowVerticalLines: true,
                bezierCurve: true,
                bezierCurveTension: 0.3,
                pointDot: false,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
                maintainAspectRatio: true,
                responsive: true
            };

            var cashFlowDailyCanvas = $("#cash_flow_daily").get(0).getContext("2d");
            var cashFlowDaily = new Chart(cashFlowDailyCanvas);
            var cashFlowDailyOptions = areaChartOptions;

            cashFlowDailyOptions.datasetFill = false;
            cashFlowDaily.Line(areaChartData, cashFlowDailyOptions);

            var income_category_canvas = $("#income_category").get(0).getContext("2d");
            var income_category_pie_chart = new Chart(income_category_canvas);
            var income_category_data = jQuery.parseJSON('{!! $income_graph !!}');

            var income_category_options = {
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 1,
                percentageInnerCutout: 50, // This is 0 for Pie charts
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false,
                responsive: true,
                maintainAspectRatio: false,
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
                tooltipTemplate: "<%=label%>"
            };

            income_category_pie_chart.Doughnut(income_category_data, income_category_options);

            var expense_category_canvas = $("#expense_category").get(0).getContext("2d");
            var expense_category_pie_chart = new Chart(expense_category_canvas);
            var expense_category_data = jQuery.parseJSON('{!! $expense_graph !!}');

            var expense_category_options = {
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 1,
                percentageInnerCutout: 50, // This is 0 for Pie charts
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false,
                responsive: true,
                maintainAspectRatio: false,
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
                tooltipTemplate: "<%=label%>"
            };

            expense_category_pie_chart.Doughnut(expense_category_data, expense_category_options);

            @if ($cash_flow['monthly'])
            Highcharts.chart('cash_flow_monthly', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: {!! $cash_flow['monthly']['date'] !!}
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: '{{ trans_choice('general.incomes', 1) }}',
                    data: {!! $cash_flow['monthly']['income'] !!}
                },{
                    name: '{{ trans_choice('general.expenses', 1) }}',
                    data: {!! $cash_flow['monthly']['expense'] !!}
                }, {
                    name: '{{ trans_choice('general.profits', 1) }}',
                    data: {!! $cash_flow['monthly']['profit'] !!}
                }]
            });
            @endif
        });

        jQuery(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function () {
            $('#cash_flow_monthly').each(function() {
                $(this).highcharts().reflow();
            });
        });
    </script>
@endsection
