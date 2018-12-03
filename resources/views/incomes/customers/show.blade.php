@extends('layouts.admin')

@section('title', $customer->name)

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Stats -->
            <div class="box box-success">
                <div class="box-body box-profile">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item" style="border-top: 0;">
                            <b>{{ trans_choice('general.invoices', 2) }}</b> <a class="pull-right">{{ $counts['invoices'] }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans_choice('general.revenues', 2) }}</b> <a class="pull-right">{{ $counts['revenues'] }}</a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>

            <!-- Profile -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('auth.profile') }}</h3>
                </div>
                <div class="box-body box-profile">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item" style="border-top: 0;">
                            <b>{{ trans('general.email') }}</b> <a class="pull-right">{{ $customer->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans('general.phone') }}</b> <a class="pull-right">{{ $customer->phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans('general.website') }}</b> <a class="pull-right">{{ $customer->website }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans('general.tax_number') }}</b> <a class="pull-right">{{ $customer->tax_number }}</a>
                        </li>
                        @if ($customer->refence)
                        <li class="list-group-item">
                            <b>{{ trans('general.reference') }}</b> <a class="pull-right">{{ $customer->refence }}</a>
                        </li>
                        @endif
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>

            <!-- Address Box -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('general.address') }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p class="text-muted">
                        {{ $customer->address }}
                    </p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- Edit -->
            <div>
                    <a href="{{ url('incomes/customers/' . $customer->id . '/edit') }}" class="btn btn-primary btn-block"><b>{{ trans('general.edit') }}</b></a>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans('general.paid') }}</span>
                            <span class="info-box-number">@money($amounts['paid'], setting('general.default_currency'), true)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-paper-plane-o"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans('dashboard.open_invoices') }}</span>
                            <span class="info-box-number">@money($amounts['open'], setting('general.default_currency'), true)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-warning"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans('dashboard.overdue_invoices') }}</span>
                            <span class="info-box-number">@money($amounts['overdue'], setting('general.default_currency'), true)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#transactions" data-toggle="tab" aria-expanded="true">{{ trans_choice('general.transactions', 2) }}</a></li>
                            <li class=""><a href="#invoices" data-toggle="tab" aria-expanded="false">{{ trans_choice('general.invoices', 2) }}</a></li>
                            <li class=""><a href="#revenues" data-toggle="tab" aria-expanded="false">{{ trans_choice('general.revenues', 2) }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tab-margin active" id="transactions">
                                <div class="table table-responsive">
                                    <table class="table table-striped table-hover" id="tbl-transactions">
                                        <thead>
                                        <tr>
                                            <th class="col-md-3">{{ trans('general.date') }}</th>
                                            <th class="col-md-2 text-right amount-space">{{ trans('general.amount') }}</th>
                                            <th class="col-md-4 hidden-xs">{{ trans_choice('general.categories', 1) }}</th>
                                            <th class="col-md-3 hidden-xs">{{ trans_choice('general.accounts', 1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($transactions as $item)
                                            <tr>
                                                <td>{{ Date::parse($item->paid_at)->format($date_format) }}</td>
                                                <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                                                <td class="hidden-xs">{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                                                <td class="hidden-xs">{{ $item->account->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @include('partials.admin.pagination', ['items' => $transactions, 'type' => 'transactions'])
                            </div>

                            <div class="tab-pane tab-margin" id="invoices">
                                <div class="table table-responsive">
                                    <table class="table table-striped table-hover" id="tbl-invoices">
                                        <thead>
                                        <tr>
                                            <th class="col-md-2">{{ trans_choice('general.numbers', 1) }}</th>
                                            <th class="col-md-2 text-right amount-space">{{ trans('general.amount') }}</th>
                                            <th class="col-md-2">{{ trans('invoices.invoice_date') }}</th>
                                            <th class="col-md-2">{{ trans('invoices.due_date') }}</th>
                                            <th class="col-md-2">{{ trans_choice('general.statuses', 1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($invoices as $item)
                                            <tr>
                                                <td><a href="{{ url('incomes/invoices/' . $item->id . ' ') }}">{{ $item->invoice_number }}</a></td>
                                                <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                                                <td>{{ Date::parse($item->invoiced_at)->format($date_format) }}</td>
                                                <td>{{ Date::parse($item->due_at)->format($date_format) }}</td>
                                                <td><span class="label {{ $item->status->label }}">{{ trans('invoices.status.' . $item->status->code) }}</span></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @include('partials.admin.pagination', ['items' => $invoices, 'type' => 'invoices'])
                            </div>

                            <div class="tab-pane tab-margin" id="revenues">
                                <div class="table table-responsive">
                                    <table class="table table-striped table-hover" id="tbl-revenues">
                                        <thead>
                                        <tr>
                                            <th class="col-md-3">{{ trans('general.date') }}</th>
                                            <th class="col-md-3 text-right amount-space">{{ trans('general.amount') }}</th>
                                            <th class="col-md-3 hidden-xs">{{  trans_choice('general.categories', 1) }}</th>
                                            <th class="col-md-3 hidden-xs">{{ trans_choice('general.accounts', 1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($revenues as $item)
                                            <tr>
                                                <td><a href="{{ url('incomes/revenues/' . $item->id . '/edit') }}">{{ Date::parse($item->paid_at)->format($date_format) }}</a></td>
                                                <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                                                <td class="hidden-xs">{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                                                <td class="hidden-xs">{{ $item->account->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @include('partials.admin.pagination', ['items' => $revenues, 'type' => 'revenues'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
