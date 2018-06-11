@extends('layouts.admin')

@section('title', trans_choice('general.payments', 2))

@section('new_button')
@permission('create-expenses-payments')
<span class="new-button"><a href="{{ url('expenses/payments/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
<span><a href="{{ url('common/import/expenses/payments') }}" class="btn btn-default btn-sm"><span class="fa fa-download"></span> &nbsp;{{ trans('import.import') }}</a></span>
@endpermission
<span><a href="{{ route('payments.export', request()->input()) }}" class="btn btn-default btn-sm"><span class="fa fa-upload"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'expenses/payments', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('vendor', $vendors, request('vendor'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('category', $categories, request('category'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('account', $accounts, request('account'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        <div class="pull-right">
            <span class="title-filter hidden-xs">{{ trans('general.show') }}:</span>
            {!! Form::select('limit', $limits, request('limit', setting('general.list_limit', '25')), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-payments">
                <thead>
                    <tr>
                        <th class="col-md-2">@sortablelink('paid_at', trans('general.date'))</th>
                        <th class="col-md-2 text-right amount-space">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-md-3 hidden-xs">@sortablelink('vendor.name', trans_choice('general.vendors', 1))</th>
                        <th class="col-md-2 hidden-xs">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th class="col-md-2 hidden-xs">@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($payments as $item)
                    <tr>
                        <td><a href="{{ url('expenses/payments/' . $item->id . '/edit') }}">{{ Date::parse($item->paid_at)->format($date_format) }}</a></td>
                        <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                        <td class="hidden-xs">{{ !empty($item->vendor->name) ? $item->vendor->name : trans('general.na') }}</td>
                        <td class="hidden-xs">{{ $item->category->name }}</td>
                        <td class="hidden-xs">{{ $item->account->name }}</td>
                        <td class="text-center">
                            @if ($item->category->id != $transfer_cat_id)
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ url('expenses/payments/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                    @permission('create-expenses-payments')
                                    <li class="divider"></li>
                                    <li><a href="{{ url('expenses/payments/' . $item->id . '/duplicate') }}">{{ trans('general.duplicate') }}</a></li>
                                    @endpermission
                                    @permission('delete-expenses-payments')
                                    <li class="divider"></li>
                                    <li>{!! Form::deleteLink($item, 'expenses/payments') !!}</li>
                                    @endpermission
                                </ul>
                            </div>
                            @endif
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

