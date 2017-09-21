@extends('layouts.admin')

@section('title', trans_choice('general.companies', 2))

@permission('create-companies-companies')
@section('new_button')
<span class="new-button"><a href="{{ url('companies/companies/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->

<div class="box box-success">
    <div class="box-header">
        {!! Form::open(['url' => 'companies/companies', 'role' => 'form', 'method' => 'GET']) !!}
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
            <table class="table table-bordered table-striped table-hover" id="tbl-companies">
                <thead>
                    <tr>
                        <th class="col-md-5">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-2">@sortablelink('domain', trans('companies.domain'))</th>
                        <th class="col-md-2">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-md-3">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($companies as $item)
                    <tr>
                        <td><a href="{{ url('companies/companies/' . $item->id . '/edit') }}">{{ $item->company_name }}</a></td>
                        <td>{{ $item->domain }}</td>
                        <td>{{ $item->company_email }}</td>
                        <td>
                            <a href="{{ url('companies/companies/' . $item->id . '/set') }}" class="btn btn-info btn-xs"><i class="fa fa-arrow-right" aria-hidden="true"></i> {{ trans('general.change') }}</a>
                            <a href="{{ url('companies/companies/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                            @permission('delete-companies-companies')
                            {!! Form::deleteButton($item, 'companies/companies', '', 'company_name') !!}
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
        @include('partials.admin.pagination', ['items' => $companies, 'type' => 'companies'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

