@extends('layouts.admin')

@section('title', trans_choice('general.payments', 2))

@permission('create-expenses-payments')
@section('new_button')
<span class="new-button"><a href="{{ url('expenses/payments/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header">
        {!! Form::open(['url' => 'expenses/payments', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('category', $categories, request('category'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('account', $accounts, request('account'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        <div class="pull-right">
            <span class="title-filter">{{ trans('general.show') }}:</span>
            {!! Form::select('limit', $limits, request('limit', setting('general.list_limit', '25')), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-bordered table-striped table-hover" id="tbl-payments">
                <thead>
                    <tr>
                        <th>@sortablelink('paid_at', trans('general.date'))</th>
                        <th>@sortablelink('amount', trans('general.amount'))</th>
                        <th>@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th>@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                        <th style="width: 15%;">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($payments as $item)
                    <tr>
                        <td><a href="{{ url('expenses/payments/' . $item->id . '/edit') }}">{{ Date::parse($item->paid_at)->format($date_format) }}</a></td>
                        <td>@money($item->amount, $item->currency_code, true)</td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->account->name }}</td>
                        <td>
                            <a href="{{ url('expenses/payments/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                            @permission('delete-expenses-payments')
                            {!! Form::deleteButton($item, 'expenses/payments') !!}
                            @endpermission
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        @include('partials.admin.pagination', ['items' => $payments, 'type' => 'payments'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

