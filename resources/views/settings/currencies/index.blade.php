@extends('layouts.admin')

@section('title', trans_choice('general.currencies', 2))

@permission('create-settings-currencies')
@section('new_button')
<span class="new-button"><a href="{{ url('settings/currencies/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'settings/currencies', 'role' => 'form', 'method' => 'GET']) !!}
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
    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-currencies">
                <thead>
                    <tr>
                        <th class="col-md-5">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-3 hidden-xs">@sortablelink('code', trans('currencies.code'))</th>
                        <th class="col-md-2">@sortablelink('rate', trans('currencies.rate'))</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($currencies as $item)
                    <tr>
                        <td><a href="{{ url('settings/currencies/' . $item->id . '/edit') }}">{{ $item->name }}</a></td>
                        <td class="hidden-xs">{{ $item->code }}</td>
                        <td>{{ $item->rate }}</td>
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
                                    <li><a href="{{ url('settings/currencies/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                    @if ($item->enabled)
                                    <li><a href="{{ route('currencies.disable', $item->id) }}">{{ trans('general.disable') }}</a></li>
                                    @else
                                    <li><a href="{{ route('currencies.enable', $item->id) }}">{{ trans('general.enable') }}</a></li>
                                    @endif
                                    @permission('delete-settings-currencies')
                                    <li class="divider"></li>
                                    <li>{!! Form::deleteLink($item, 'settings/currencies') !!}</li>
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
        @include('partials.admin.pagination', ['items' => $currencies, 'type' => 'currencies'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection
