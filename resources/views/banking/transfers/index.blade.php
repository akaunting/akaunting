@extends('layouts.admin')

@section('title', trans_choice('general.transfers', 2))

@permission('create-banking-transfers')
@section('new_button')
<span class="new-button"><a href="{{ url('banking/transfers/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'banking/transfers', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::select('from_account', $accounts, request('from_account'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('to_account', $accounts, request('to_account'), ['class' => 'form-control input-filter input-sm']) !!}
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
            <table class="table table-striped table-hover" id="tbl-transfers">
                <thead>
                <tr>
                    <th class="col-md-3">@sortablelink('payment.paid_at', trans('general.date'))</th>
                    <th class="col-md-3">@sortablelink('payment.name', trans('transfers.from_account'))</th>
                    <th class="col-md-3">@sortablelink('revenue.name', trans('transfers.to_account'))</th>
                    <th class="col-md-3">@sortablelink('payment.amount', trans('general.amount'))</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transfers as $item)
                    <tr>
                        <td>{{ Date::parse($item->paid_at)->format($date_format) }}</td>
                        <td>{{ $item->from_account }}</td>
                        <td>{{ $item->to_account }}</td>
                        <td>@money($item->amount, $item->currency_code, true)</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        @include('partials.admin.pagination', ['items' => $items, 'type' => 'transfers'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection
