@extends('layouts.admin')

@section('title', trans_choice('general.vendors', 2))

@permission('create-expenses-vendors')
@section('new_button')
<span class="new-button"><a href="{{ url('expenses/vendors/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
<span><a href="{{ url('common/import/expenses/vendors') }}" class="btn btn-success btn-sm"><span class="fa fa-download"></span> &nbsp;{{ trans('import.import') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'expenses/vendors', 'role' => 'form', 'method' => 'GET']) !!}
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
            <table class="table table-striped table-hover" id="tbl-vendors">
                <thead>
                    <tr>
                        <th class="col-md-5">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-3 hidden-xs">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-md-2">@sortablelink('phone', trans('general.phone'))</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($vendors as $item)
                    <tr>
                        <td><a href="{{ url('expenses/vendors/' . $item->id) }}">{{ $item->name }}</a></td>
                        <td class="hidden-xs">{{ !empty($item->email) ? $item->email : trans('general.na') }}</td>
                        <td>{{ $item->phone }}</td>
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
                                    <li><a href="{{ url('expenses/vendors/' . $item->id) }}">{{ trans('general.show') }}</a></li>
                                    <li><a href="{{ url('expenses/vendors/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                    <li class="divider"></li>
                                    @permission('create-expenses-vendors')
                                    <li><a href="{{ url('expenses/vendors/' . $item->id . '/duplicate') }}">{{ trans('general.duplicate') }}</a></li>
                                    <li class="divider"></li>
                                    @endpermission
                                    @permission('delete-expenses-vendors')
                                    <li>{!! Form::deleteLink($item, 'expenses/vendors') !!}</li>
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
        @include('partials.admin.pagination', ['items' => $vendors, 'type' => 'vendors'])
    </div>
</div>
<!-- /.box-footer -->
@endsection

