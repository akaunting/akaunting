@extends('layouts.customer')

@section('title', trans_choice('general.invoices', 2))

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'customers/invoices', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('status', $status, request('status'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        <div class="pull-right">
            <span class="title-filter hidden-xs">{{ trans('general.show') }}:</span>
            {!! Form::select('limit', $limits, request('limit', setting('general.list_limit', '25')), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-invoices">
                <thead>
                    <tr>
                        <th class="col-md-2">@sortablelink('invoice_number', trans('invoices.invoice_number'))</th>
                        <th class="col-md-2 text-right">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-md-3 text-right">@sortablelink('invoiced_at', trans('invoices.invoice_date'))</th>
                        <th class="col-md-3 text-right">@sortablelink('due_at', trans('invoices.due_date'))</th>
                        <th class="col-md-2 text-center">@sortablelink('status.name', trans_choice('general.statuses', 1))</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($invoices as $item)
                    <tr>
                        <td><a href="{{ url('customers/invoices/' . $item->id) }}">{{ $item->invoice_number }}</a></td>
                        <td class="text-right">@money($item->amount, $item->currency_code, true)</td>
                        <td class="text-right">{{ Date::parse($item->invoiced_at)->format($date_format) }}</td>
                        <td class="text-right">{{ Date::parse($item->due_at)->format($date_format) }}</td>
                        <td class="text-center"><span class="label {{ $item->status->label }}">{{ trans('invoices.status.' . $item->status->code) }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        @include('partials.admin.pagination', ['items' => $invoices, 'type' => 'invoices'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection
