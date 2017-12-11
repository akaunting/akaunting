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
                    <div class="chart tab-pane active" id="daily-chart" style="position: relative; height: 310px;">
                        {!! $line_daily->render() !!}
                    </div>
                    <div class="chart tab-pane" id="monthly-chart" style="position: relative; height: 310px;">
                        {!! $line_monthly->render() !!}
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
                    {!! $donut_incomes->render() !!}
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
                    {!! $donut_expenses->render() !!}
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

@push('js')
{!! Charts::assets() !!}
@endpush