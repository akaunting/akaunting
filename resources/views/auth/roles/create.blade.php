@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.roles', 1)]))

@section('content')
    {!! Form::open([
        'id' => 'role',
        'route' => 'roles.store',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('display_name', trans('general.name'), 'user-tag') }}

                    {{ Form::textGroup('name', trans('general.code'), 'code') }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}
                </div>
            </div>
        </div>

        <div id="role-permissions">
            <label for="permissions" class="form-control-label">{{trans_choice('general.permissions', 2)}}</label>
            <br>
            <span class="btn btn-outline-primary btn-sm" @click="permissionSelectAll">{{trans('general.select_all')}}</span>
            <span class="btn btn-outline-primary btn-sm" @click="permissionUnselectAll">{{trans('general.unselect_all')}}</span>

            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    @foreach($names as $name)
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @php echo ($name == 'read') ? 'active' : ''; @endphp" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tab-{{ $name }}" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">{{ ucwords($name) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <div class="tab-content">
                        @foreach($permissions as $code => $code_permissions)
                            <div class="tab-pane fade show @php echo ($code == 'read') ? 'active' : ''; @endphp" id="tab-{{ $code }}"  role="tabpanel">
                                <div class="permission-button-group">
                                    <span class="btn btn-primary btn-sm" @click="select('{{ $code }}')">{{trans('general.select_all')}}</span>
                                    <span class="btn btn-primary btn-sm" @click="unselect('{{ $code }}')">{{trans('general.unselect_all')}}</span>
                                </div>

                                @stack('permissions_input_start')
                                    <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                                        <div class="row pt-4">
                                            @foreach($code_permissions as $item)
                                                <div class="col-md-3 role-list">
                                                    <div class="custom-control custom-checkbox">
                                                        {{ Form::checkbox('permissions', $item->id, null, ['id' => 'permissions-' . $item->id, 'class' => 'custom-control-input', 'v-model' => 'form.permissions']) }}
                                                        <label class="custom-control-label" for="permissions-{{ $item->id }}">
                                                            {{ $item->display_name }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @if ($item->name == 'read-admin-panel' || $item->name == 'read-client-portal')
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

                <div class="card-footer">
                    <div class="row float-right">
                        {{ Form::saveButtons('auth/roles') }}
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/roles.js?v=' . version('short')) }}"></script>
@endpush
