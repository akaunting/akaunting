@extends('layouts.admin')

@section('title', trans('reports.summary.expense'))

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header">
        <div class="pull-left" style="margin-left: 23px">
            <a href="{{ url('reports/expense-summary') }}"><span class="badge @if (request('status') == '') bg-green @else bg-default @endif">{{ trans('general.all') }}</span></a>
            <a href="{{ url('reports/expense-summary') }}?status=paid"><span class="badge @if (request('status') == 'paid') bg-green @else bg-default @endif">{{ trans('bills.paid') }}</span></a>
            <a href="{{ url('reports/expense-summary') }}?status=upcoming"><span class="badge @if (request('status') == 'upcoming') bg-green @else bg-default @endif">{{ trans('dashboard.payables') }}</span></a>
        </div>
        {!! Form::open(['url' => 'reports/expense-summary', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-right">
            {!! Form::select('year', $years, request('year', Date::now()->year), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <div class="box-body">
        <div class="chart">
            <canvas id="expense_graph" style="height: 246px; width: 1069px;" height="246" width="1069"></canvas>
        </div>

        <hr>

        <div class="table table-responsive">
            <table class="table table-bordered table-striped table-hover" id="tbl-report-expenses">
                <thead>
                <tr>
                    <th>{{ trans_choice('general.categories', 1) }}</th>
                    @foreach($dates as $date)
                    <th class="text-right">{{ $date }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @if ($expenses)
                @foreach($expenses as $category_id =>  $category)
                    <tr>
                        <td>{{ $categories[$category_id] }}</td>
                        @foreach($category as $item)
                        <td class="text-right">@money($item['amount'], $item['currency_code'], true)</td>
                        @endforeach
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="4">
                            <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                        </td>
                    </tr>
                @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>{{ trans_choice('general.totals', 1) }}</th>
                        @foreach($totals as $total)
                        <th class="text-right">@money($total['amount'], $total['currency_code'], true)</th>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection

@section('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/chartjs/Chart.min.js') }}"></script>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var areaChartData = {
                labels: {!! json_encode(array_values($dates)) !!},
                datasets: [
                    {
                        label: "{{ trans_choice('general.expenses', 2) }}",
                        fillColor: "#F56954",
                        strokeColor: "#F56954",
                        pointColor: "#F56954",
                        pointStrokeColor: "#F56954",
                        pointHighlightFill: "#FFF",
                        pointHighlightStroke: "#F56954",
                        data: {!! $expenses_graph !!}
                    }
                ]
            };

            var areaChartOptions = {
                showScale: true,
                scaleShowGridLines: false,
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
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                maintainAspectRatio: true,
                responsive: true
            };

            var cashFlowDailyCanvas = $("#expense_graph").get(0).getContext("2d");
            var cashFlowDaily = new Chart(cashFlowDailyCanvas);
            var cashFlowDailyOptions = areaChartOptions;

            cashFlowDailyOptions.datasetFill = false;
            cashFlowDaily.Line(areaChartData, cashFlowDailyOptions);
        });
    </script>
@endsection
