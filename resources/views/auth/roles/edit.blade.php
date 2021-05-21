@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.roles', 1)]))

@section('content')
    {!! Form::model($role, [
        'id' => 'role',
        'method' => 'PATCH',
        'route' => ['roles.update', $role->id],
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('display_name', trans('general.name'), 'font') }}

                    {{ Form::textGroup('name', trans('general.code'), 'code') }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}
                </div>
            </div>
        </div>

        <div id="role-permissions">
            <label for="permissions" class="form-control-label d-block">{{trans_choice('general.permissions', 2)}}</label>
            <span class="btn btn-outline-primary btn-sm" @click="permissionSelectAll">{{trans('general.select_all')}}</span>
            <span class="btn btn-outline-primary btn-sm" @click="permissionUnselectAll">{{trans('general.unselect_all')}}</span>

            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    @foreach($actions as $action)
                        @php $active_action_tab = ($action == 'read') ? 'active' : ''; @endphp
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 {{ $active_action_tab }}" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tab-{{ $action }}" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">{{ ucwords($action) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        @foreach($permissions as $action => $action_permissions)
                            @php $active_action_tab = ($action == 'read') ? 'active' : ''; @endphp
                            <div class="tab-pane fade show {{ $active_action_tab }}" id="tab-{{ $action }}" ref="tab-{{ $action }}" role="tabpanel">
                                <span class="btn btn-primary btn-sm" @click="select('{{ $action }}')">{{ trans('general.select_all') }}</span>
                                <span class="btn btn-primary btn-sm" @click="unselect('{{ $action }}')">{{ trans('general.unselect_all') }}</span>

                                @stack('permissions_input_start')

                                <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                                    <div class="row pt-4">
                                        @foreach($action_permissions as $item)
                                            <div class="col-md-4 role-list">
                                                <div class="custom-control custom-checkbox">
                                                    @if (($item->name == 'read-admin-panel'))
                                                        {{ Form::checkbox('permissions', $item->id, null, ['id' => 'permissions-' . $item->id, 'class' => 'custom-control-input', 'v-model' => 'form.permissions', ':disabled' => 'form.permissions.includes(permissions.read_client_portal)']) }}
                                                    @elseif (($item->name == 'read-client-portal'))
                                                        {{ Form::checkbox('permissions', $item->id, null, ['id' => 'permissions-' . $item->id, 'class' => 'custom-control-input', 'v-model' => 'form.permissions', ':disabled' => 'form.permissions.includes(permissions.read_admin_panel)']) }}
                                                    @else
                                                        {{ Form::checkbox('permissions', $item->id, null, ['id' => 'permissions-' . $item->id, 'class' => 'custom-control-input', 'v-model' => 'form.permissions']) }}
                                                    @endif

                                                    <label class="custom-control-label" for="permissions-{{ $item->id }}">
                                                        {{ $item->title }}
                                                    </label>
                                                </div>
                                            </div>

                                            @if (($item->name == 'read-admin-panel') || ($item->name == 'read-client-portal'))
                                                {{ Form::hidden($item->name, $item->id, ['id' => $item->name]) }}
                                            @endif
                                        @endforeach
                                        {!! $errors->first('permissions', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                @stack('permissions_input_end')
                            </div>
                        @endforeach
                    </div>
                </div>

                @can('update-auth-roles')
                    <div class="card-footer">
                        <div class="row save-buttons">
                            {{ Form::saveButtons('roles.index') }}
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/roles.js?v=' . version('short')) }}"></script>
@endpush
