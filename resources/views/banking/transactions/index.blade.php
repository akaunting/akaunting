@extends('layouts.admin')

@section('title', trans_choice('general.transactions', 2))

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'banking/transactions', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::select('account', $accounts, request('account'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('type', $types, request('type'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('category', $categories, request('category'), ['class' => 'form-control input-filter input-sm']) !!}
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
            <table class="table table-striped table-hover" id="tbl-transactions">
                <thead>
                    <tr>
                        <th class="col-md-2">@sortablelink('paid_at', trans('general.date'))</th>
                        <th class="col-md-2">@sortablelink('account_name', trans('accounts.account_name'))</th>
                        <th class="col-md-2">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-md-2">@sortablelink('category_name', trans_choice('general.categories', 1))</th>
                        <th class="col-md-2">@sortablelink('description', trans('general.description'))</th>
                        <th class="col-md-2 text-right amount-space">@sortablelink('amount', trans('general.amount'))</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transactions as $item)
                    <tr>
                        <td>{{ Date::parse($item->paid_at)->format($date_format) }}</td>
                        <td>{{ $item->account_name }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->category_name }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection
