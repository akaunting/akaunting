@extends('layouts.admin')

@section('title', $vendor->name)

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Stats -->
            <div class="box box-success">
                <div class="box-body box-profile">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item" style="border-top: 0;">
                            <b>{{ trans_choice('general.bills', 2) }}</b> <a class="pull-right">{{ $counts['bills'] }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans_choice('general.payments', 2) }}</b> <a class="pull-right">{{ $counts['payments'] }}</a>
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
                            <b>{{ trans('general.email') }}</b> <a class="pull-right">{{ $vendor->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans('general.phone') }}</b> <a class="pull-right">{{ $vendor->phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans('general.website') }}</b> <a class="pull-right">{{ $vendor->website }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ trans('general.tax_number') }}</b> <a class="pull-right">{{ $vendor->tax_number }}</a>
                        </li>
                        @if ($vendor->refence)
                        <li class="list-group-item">
                            <b>{{ trans('general.reference') }}</b> <a class="pull-right">{{ $vendor->refence }}</a>
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
                        {{ $vendor->address }}
                    </p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- Edit -->
            <div>
                    <a href="{{ url('expenses/vendors/' . $vendor->id . '/edit') }}" class="btn btn-primary btn-block"><b>{{ trans('general.edit') }}</b></a>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>

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
                            <span class="info-box-text">{{ trans('dashboard.open_bills') }}</span>
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
                            <span class="info-box-text">{{ trans('dashboard.overdue_bills') }}</span>
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
                            <li class=""><a href="#bills" data-toggle="tab" aria-expanded="false">{{ trans_choice('general.bills', 2) }}</a></li>
                            <li class=""><a href="#payments" data-toggle="tab" aria-expanded="false">{{ trans_choice('general.payments', 2) }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tab-margin active" id="transactions">
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

                                @include('partials.admin.pagination', ['items' => $transactions, 'type' => 'transactions'])
                            </div>

                            <div class="tab-pane tab-margin" id="bills">
                                <div class="table table-responsive">
                                    <table class="table table-striped table-hover" id="tbl-bills">
                                        <thead>
                                        <tr>
                                            <th class="col-md-2">{{ trans_choice('general.numbers', 1) }}</th>
                                            <th class="col-md-2 text-right amount-space">{{ trans('general.amount') }}</th>
                                            <th class="col-md-2">{{ trans('bills.bill_date') }}</th>
                                            <th class="col-md-2">{{ trans('bills.due_date') }}</th>
                                            <th class="col-md-2">{{ trans_choice('general.statuses', 1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bills as $item)
                                            <tr>
                                                <td><a href="{{ url('expenses/bills/' . $item->id . ' ') }}">{{ $item->bill_number }}</a></td>
                                                <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                                                <td>{{ Date::parse($item->billed_at)->format($date_format) }}</td>
                                                <td>{{ Date::parse($item->due_at)->format($date_format) }}</td>
                                                <td><span class="label {{ $item->status->label }}">{{ trans('bills.status.' . $item->status->code) }}</span></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @include('partials.admin.pagination', ['items' => $bills, 'type' => 'bills'])
                            </div>

                            <div class="tab-pane tab-margin" id="payments">
                                <div class="table table-responsive">
                                    <table class="table table-striped table-hover" id="tbl-payments">
                                        <thead>
                                        <tr>
                                            <th class="col-md-3">{{ trans('general.date') }}</th>
                                            <th class="col-md-3 text-right amount-space">{{ trans('general.amount') }}</th>
                                            <th class="col-md-3 hidden-xs">{{  trans_choice('general.categories', 1) }}</th>
                                            <th class="col-md-3 hidden-xs">{{ trans_choice('general.accounts', 1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($payments as $item)
                                            <tr>
                                                <td><a href="{{ url('expenses/payments/' . $item->id . '/edit') }}">{{ Date::parse($item->paid_at)->format($date_format) }}</a></td>
                                                <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                                                <td class="hidden-xs">{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                                                <td class="hidden-xs">{{ $item->account->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @include('partials.admin.pagination', ['items' => $payments, 'type' => 'payments'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
