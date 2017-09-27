@extends('layouts.admin')

@section('title', trans_choice('general.users', 2))

@permission('create-auth-users')
@section('new_button')
<span class="new-button"><a href="{{ url('auth/users/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header">
        {!! Form::open(['url' => 'auth/users', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('role', $roles, request('role'), ['class' => 'form-control input-filter input-sm']) !!}
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
            <table class="table table-bordered table-striped table-hover" id="tbl-users">
                <thead>
                    <tr>
                        <th>@sortablelink('name', trans('general.name'))</th>
                        <th>@sortablelink('email', trans('general.email'))</th>
                        <th>@sortablelink('roles', trans_choice('general.roles', 2))</th>
                        <th style="width: 15%;">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $item)
                    <tr>
                        <td><a href="{{ url('auth/users/' . $item->id . '/edit') }}"><img src="{{ asset($item->picture) }}" class="users-image" alt="{{ $item->name }}" title="{{ $item->name }}"> {{ $item->name }}</a></td>
                        <td>{{ $item->email }}</td>
                        <td style="vertical-align: middle;">
                            @foreach($item->roles as $role)
                                <label class="label label-default">{{ $role->display_name }}</label>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ url('auth/users/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                            @permission('delete-auth-users')
                            {!! Form::deleteButton($item, 'auth/users') !!}
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
        @include('partials.admin.pagination', ['items' => $users, 'type' => 'users'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

