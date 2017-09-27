@extends('layouts.customer')

@section('title', trans_choice('general.invoices', 2))

@permission('create-customers-invoices')
@section('new_button')
<span class="new-button"><a href="{{ url('customers/invoices/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header">
        {!! Form::open(['url' => 'customers/invoices', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('status', $status, request('status'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('status.all')]) !!}
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        <div class="pull-right">
            <span class="title-filter">{{ trans('general.show') }}:</span>
            {!! Form::select('limit', $limits, request('limit', setting('general.list_limit', '25')), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-bordered table-striped table-hover" id="tbl-invoices">
                <thead>
                    <tr>
                        <th>@sortablelink('invoice_number', trans('invoices.invoice_number'))</th>
                        <th>@sortablelink('customer_name', trans_choice('general.customers', 1))</th>
                        <th>@sortablelink('amount', trans('invoices.total_price'))</th>
                        <th>@sortablelink('status.name', trans_choice('general.statuses', 1))</th>
                        <th>@sortablelink('invoiced_at', trans('invoices.invoice_date'))</th>
                        <th>@sortablelink('due_at', trans('invoices.due_date'))</th>
                        <th style="width: 18%;">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($invoices as $item)
                    <tr>
                        <td><a href="{{ url('customers/invoices/' . $item->id . '/edit') }}">{{ $item->invoice_number }}</a></td>
                        <td>{{ $item->customer_name }}</td>
                        <td>@money($item->amount, $item->currency_code, true)</td>
                        <td>{{ $item->status->name }}</td>
                        <td>{{ Date::parse($item->invoiced_at)->format($date_format) }}</td>
                        <td>{{ Date::parse($item->due_at)->format($date_format) }}</td>
                        <td>
                            <a href="{{ url('customers/invoices/' . $item->id . '') }}" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> {{ trans('general.show') }}</a>
                        </td>
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
