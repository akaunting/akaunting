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
    <div class="box-header with-border">
        {!! Form::open(['url' => 'auth/users', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-left">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('role', $roles, request('role'), ['class' => 'form-control input-filter input-sm']) !!}
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
            <table class="table table-striped table-hover" id="tbl-users">
                <thead>
                    <tr>
                        <th class="col-md-3">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-3">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-md-3 hidden-xs">{{ trans_choice('general.roles', 2) }}</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $item)
                    <tr>
                        <td>
                            <a href="{{ url('auth/users/' . $item->id . '/edit') }}">
                                @if (setting('general.use_gravatar', '0') == '1')
                                    <img src="{{ $item->picture }}" class="users-image" alt="{{ $item->name }}" title="{{ $item->name }}">
                                @else
                                @if ($item->picture)
                                    <img src="{{ Storage::url($item->picture->id) }}" class="users-image" alt="{{ $item->name }}" title="{{ $item->name }}">
                                @endif
                                @endif
                                {{ $item->name }}
                            </a>
                        </td>
                        <td>{{ $item->email }}</td>
                        <td class="hidden-xs" style="vertical-align: middle;">
                            @foreach($item->roles as $role)
                                <label class="label label-default">{{ $role->display_name }}</label>
                            @endforeach
                        </td>
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
                                    <li><a href="{{ url('auth/users/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>@if ($item->enabled)
                                    <li><a href="{{ route('users.disable', $item->id) }}">{{ trans('general.disable') }}</a></li>
                                    @else
                                    <li><a href="{{ route('users.enable', $item->id) }}">{{ trans('general.enable') }}</a></li>
                                    @endif
                                    @permission('delete-auth-users')
                                    <li class="divider"></li>
                                    <li>{!! Form::deleteLink($item, 'auth/users') !!}</li>
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
        @include('partials.admin.pagination', ['items' => $users, 'type' => 'users'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

