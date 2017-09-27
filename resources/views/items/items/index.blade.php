@extends('layouts.admin')

@section('title', trans_choice('general.items', 2))

@permission('create-items-items')
@section('new_button')
<span class="new-button"><a href="{{ url('items/items/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header">
        {!! Form::open(['url' => 'items/items', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('category', $categories, request('category'), ['class' => 'form-control input-filter input-sm']) !!}
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
            <table class="table table-bordered table-striped table-hover" id="tbl-items">
                <thead>
                    <tr>
                        <th class="col-md-1">{{ trans_choice('general.pictures', 1) }}</th>
                        <th class="col-md-3">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-1">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th class="col-md-1">@sortablelink('quantity', trans_choice('items.quantities', 1))</th>
                        <th>@sortablelink('sale_price', trans('items.sales_price'))</th>
                        <th>@sortablelink('purchase_price', trans('items.purchase_price'))</th>
                        <th class="col-md-1">@sortablelink('enabled', trans('general.status'))</th>
                        <th class="col-md-2">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td><img src="{{ asset($item->picture) }}" class="img-thumbnail" width="50" alt="{{ $item->name }}"></td>
                        <td><a href="{{ url('items/items/' . $item->id . '/edit') }}">{{ $item->name }}</a></td>
                        <td>{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ money($item->sale_price, setting('general.default_currency'), true) }}</td>
                        <td>{{ money($item->purchase_price, setting('general.default_currency'), true) }}</td>
                        <td>
                            @if ($item->enabled)
                                <span class="label label-success">{{ trans('general.enabled') }}</span>
                            @else
                                <span class="label label-danger">{{ trans('general.disabled') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('items/items/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                            @permission('delete-items-items')
                            {!! Form::deleteButton($item, 'items/items') !!}
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
        @include('partials.admin.pagination', ['items' => $items, 'type' => 'items'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection
