@extends('layouts.admin')

@section('title', trans_choice('general.items', 2))

@permission('create-items-items')
@section('new_button')
<span class="new-button"><a href="{{ url('items/items/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
<span><a href="{{ url('common/import/items/items') }}" class="btn btn-success btn-sm"><span class="fa fa-download"></span> &nbsp;{{ trans('import.import') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'items/items', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
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
            <table class="table table-striped table-hover" id="tbl-items">
                <thead>
                    <tr>
                        <th class="col-md-1 hidden-xs">{{ trans_choice('general.pictures', 1) }}</th>
                        <th class="col-md-3">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('category', trans_choice('general.categories', 1))</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('quantity', trans_choice('items.quantities', 1))</th>
                        <th class="col-md-2 text-right amount-space">@sortablelink('sale_price', trans('items.sales_price'))</th>
                        <th class="col-md-2 hidden-xs text-right amount-space">@sortablelink('purchase_price', trans('items.purchase_price'))</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="hidden-xs"><img src="{{ $item->picture ? Storage::url($item->picture->id) : asset('public/img/akaunting-logo-green.png') }}" class="img-thumbnail" width="50" alt="{{ $item->name }}"></td>
                        <td><a href="{{ url('items/items/' . $item->id . '/edit') }}">{{ $item->name }}</a></td>
                        <td class="hidden-xs">{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                        <td class="hidden-xs">{{ $item->quantity }}</td>
                        <td class="text-right amount-space">{{ money($item->sale_price, setting('general.default_currency'), true) }}</td>
                        <td class="hidden-xs text-right amount-space">{{ money($item->purchase_price, setting('general.default_currency'), true) }}</td>
                        <td class="hidden-xs">
                            @if ($item->enabled)
                                <span class="label label-success">{{ trans('general.enabled') }}</span>
                            @else
                                <span class="label label-danger">{{ trans('general.disabled') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ url('items/items/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                    <li class="divider"></li>
                                    @permission('create-items-items')
                                    <li><a href="{{ url('items/items/' . $item->id . '/duplicate') }}">{{ trans('general.duplicate') }}</a></li>
                                    <li class="divider"></li>
                                    @endpermission
                                    @permission('delete-items-items')
                                    <li>{!! Form::deleteLink($item, 'items/items') !!}</li>
                                    @endpermission
                                </ul>
                            </div>
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
