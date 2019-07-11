@extends('layouts.customer')

@section('title', trans_choice('general.payments', 1))

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'customers/payments', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('category_id', $categories, request('category_id'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('payment_method', $payment_methods, request('payment_method'), ['class' => 'form-control input-filter input-sm']) !!}
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
            <table class="table table-striped table-hover" id="tbl-revenues">
                <thead>
                    <tr>
                        <th>@sortablelink('paid_at', trans('general.date'))</th>
                        <th>@sortablelink('amount', trans('general.amount'))</th>
                        <th>@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th>@sortablelink('payment_method', trans_choice('general.payment_methods', 1))</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($payments as $item)
                <tr>
                    <td><a href="{{ url('customers/payments/' . $item->id) }}">{{ Date::parse($item->paid_at)->format($date_format) }}</a></td>
                    <td>@money($item->amount, $item->currency_code, true)</td>
                    <td>{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                    <td>{{ $payment_methods[$item->payment_method] }}</td>
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
</div>
<!-- /.box -->
@endsection
