@extends('layouts.admin')

@section('title', trans_choice('general.companies', 2))

@permission('create-common-companies')
@section('new_button')
<span class="new-button"><a href="{{ url('common/companies/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->

<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'common/companies', 'role' => 'form', 'method' => 'GET']) !!}
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
            <table class="table table-striped table-hover" id="tbl-companies">
                <thead>
                    <tr>
                        <th class="col-md-1 hidden-xs">@sortablelink('id', trans('general.id'))</th>
                        <th class="col-md-3">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-2 hidden-xs">@sortablelink('domain', trans('companies.domain'))</th>
                        <th class="col-md-2 hidden-xs">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-md-2 hidden-xs">@sortablelink('created_at', trans('general.created'))</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($companies as $item)
                    <tr>
                        <td class="hidden-xs">{{ $item->id }}</td>
                        <td><a href="{{ url('common/companies/' . $item->id . '/edit') }}">{{ $item->company_name }}</a></td>
                        <td class="hidden-xs">{{ $item->domain }}</td>
                        <td class="hidden-xs">{{ $item->company_email }}</td>
                        <td class="hidden-xs">{{ Date::parse($item->created_at)->format($date_format) }}</td>
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
                                    @if ($item->enabled)
                                    <li><a href="{{ route('companies.switch', $item->id) }}">{{ trans('general.switch') }}</a></li>
                                    <li class="divider"></li>
                                    @endif
                                    <li><a href="{{ url('common/companies/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                    @if ($item->enabled)
                                    <li><a href="{{ route('companies.disable', $item->id) }}">{{ trans('general.disable') }}</a></li>
                                    @else
                                    <li><a href="{{ route('companies.enable', $item->id) }}">{{ trans('general.enable') }}</a></li>
                                    @endif
                                    @permission('delete-common-companies')
                                    <li class="divider"></li>
                                    <li>{!! Form::deleteLink($item, 'common/companies', '', 'company_name') !!}</li>
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
        @include('partials.admin.pagination', ['items' => $companies, 'type' => 'companies'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

