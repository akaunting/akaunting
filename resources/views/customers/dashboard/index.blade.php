@extends('layouts.customer')

@section('title', trans('general.dashboard'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Invoices List-->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans_choice('general.invoices', 2) }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @if ($user->invoices->count())
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{ trans('invoices.invoice_number') }}</th>
                                <th>{{ trans_choice('general.customers', 1) }}</th>
                                <th>{{ trans('invoices.total_price') }}</th>
                                <th>{{ trans_choice('general.statuses', 1) }}</th>
                                <th>{{ trans('invoices.invoice_date') }}</th>
                                <th style="width: 18%;">{{ trans('general.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->invoices as $item)
                            <tr>
                                <td><a href="{{ url('customer/invoices/' . $item->id . '/edit') }}">{{ $item->invoice_number }}</a></td>
                                <td>{{ $item->customer_name }}</td>
                                <td>@money($item->amount, $item->currency_code, true)</td>
                                <td>{{ $item->status->name }}</td>
                                <td>{{ Date::parse($item->invoiced_at)->format($date_format) }}</td>
                                <td>
                                    <a href="{{ url('customers/invoices/' . $item->id . '') }}" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> {{ trans('general.show') }}</a>
                                    <a href="{{ url('customers/invoices/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                                </td>
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

    <div class="row">
        <div class="col-md-12">
            <!-- Revenues List-->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans_choice('general.payments', 2) }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @if ($user->revenues->count())
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{ trans('general.date') }}</th>
                                <th>{{ trans('general.amount') }}</th>
                                <th>{{ trans_choice('general.categories', 1) }}</th>
                                <th>{{ trans_choice('general.accounts', 1) }}</th>
                                <th style="width: 15%;">{{ trans('general.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->revenues as $item)
                            <tr>
                                <td><a href="{{ url('customer/payments/' . $item->id . '') }}">{{ Date::parse($item->paid_at)->format($date_format) }}</a></td>
                                <td>@money($item->amount, $item->currency_code, true)</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->account->name }}</td>
                                <td>
                                    <a href="{{ url('customers/payments/' . $item->id . '') }}" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> {{ trans('general.show') }}</a>
                                </td>
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