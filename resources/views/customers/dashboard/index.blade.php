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
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="col-md-2">{{ trans('invoices.invoice_number') }}</th>
                                <th class="col-md-2 text-right">{{ trans('general.amount') }}</th>
                                <th class="col-md-3 text-right">{{ trans('invoices.invoice_date') }}</th>
                                <th class="col-md-3 text-right">{{ trans('invoices.due_date') }}</th>
                                <th class="col-md-2 text-center">{{ trans_choice('general.statuses', 1) }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->invoices as $item)
                            <tr>
                                <td><a href="{{ url('customers/invoices/' . $item->id) }}">{{ $item->invoice_number }}</a></td>
                                <td class="text-right">@money($item->amount, $item->currency_code, true)</td>
                                <td class="text-right">{{ Date::parse($item->invoiced_at)->format($date_format) }}</td>
                                <td class="text-right">{{ Date::parse($item->due_at)->format($date_format) }}</td>
                                <td class="text-center">{{ $item->status->name }}</td>
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
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ trans('general.date') }}</th>
                                <th>{{ trans('general.amount') }}</th>
                                <th>{{ trans_choice('general.categories', 1) }}</th>
                                <th>{{ trans_choice('general.accounts', 1) }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->revenues as $item)
                            <tr>
                                <td><a href="{{ url('customers/payments/' . $item->id . '') }}">{{ Date::parse($item->paid_at)->format($date_format) }}</a></td>
                                <td>@money($item->amount, $item->currency_code, true)</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->account->name }}</td>
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