@extends('layouts.customer')

@section('title', trans_choice('general.transactions', 2))

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'customers/transactions', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
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
                        <th>@sortablelink('paid_at', trans('general.date'))</th>
                        <th>@sortablelink('account.name', trans('accounts.account_name'))</th>
                        <th>@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th>@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th>@sortablelink('description', trans('general.description'))</th>
                        <th>@sortablelink('amount', trans('general.amount'))</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transactions as $item)
                    <tr>
                        <td>{{ Date::parse($item->date)->format($date_format) }}</td>
                        <td>{{ $item->account }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->description }}</td>
                        <td>@money($item->amount, $item->currency_code, true)</td>
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
