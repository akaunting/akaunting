@extends('layouts.admin')

@section('title', trans_choice('general.vendors', 2))

@permission('create-expenses-vendors')
@section('new_button')
<span class="new-button"><a href="{{ url('expenses/vendors/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header">
        {!! Form::open(['url' => 'expenses/vendors', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
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
            <table class="table table-bordered table-striped table-hover" id="tbl-vendors">
                <thead>
                    <tr>
                        <th>@sortablelink('name', trans('general.name'))</th>
                        <th>@sortablelink('email', trans('general.email'))</th>
                        <th>@sortablelink('phone', trans('general.phone'))</th>
                        <th>@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                        <th style="width: 15%;">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($vendors as $item)
                    <tr>
                        <td><a href="{{ url('expenses/vendors/' . $item->id . '/edit') }}">{{ $item->name }}</a></td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>
                            @if ($item->enabled)
                                <span class="label label-success">{{ trans('general.enabled') }}</span>
                            @else
                                <span class="label label-danger">{{ trans('general.disabled') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('expenses/vendors/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                            @permission('delete-expenses-vendors')
                            {!! Form::deleteButton($item, 'expenses/vendors') !!}
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
        @include('partials.admin.pagination', ['items' => $vendors, 'type' => 'vendors'])
    </div>
</div>
<!-- /.box-footer -->
@endsection

