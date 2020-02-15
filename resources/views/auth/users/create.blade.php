@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.users', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'users.store',
            'id' => 'user',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'font') }}

                    {{ Form::emailGroup('email', trans('general.email'), 'envelope') }}

                    {{ Form::passwordGroup('password', trans('auth.password.current'), 'key') }}

                    {{ Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key') }}

                    {{ Form::selectGroup('locale', trans_choice('general.languages', 1), 'flag', language()->allowed(), setting('default.locale')) }}

                    {{ Form::selectGroup('landing_page', trans('auth.landing_page'), 'sign-in-alt', $routes, 'dashboard') }}

                    @if (setting('default.use_gravatar', '0') == '1')
                        @stack('picture_input_start')
                            <div class="form-group col-md-6">
                                {!! Form::label('picture', trans_choice('general.pictures', 1), ['class' => 'control-label']) !!}
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-picture-o"></i></div>
                                    {!! Form::text('fake_picture', null, ['id' => 'fake_picture', 'class' => 'form-control', 'disabled' => 'disabled', 'placeholder' => trans('settings.appearance.use_gravatar')]) !!}
                                </div>
                            </div>
                        @stack('picture_input_end')
                    @else
                        {{ Form::fileGroup('picture',  trans_choice('general.pictures', 1)) }}
                    @endif

                    @permission('read-common-companies')
                        {{ Form::checkboxGroup('companies', trans_choice('general.companies', 2), $companies, 'name') }}
                    @endpermission

                    @permission('read-auth-roles')
                        {{ Form::checkboxGroup('roles', trans_choice('general.roles', 2), $roles, 'display_name') }}
                    @endpermission

                    {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('users.index') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/users.js?v=' . version('short')) }}"></script>
@endpush
