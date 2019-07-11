@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.users', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::open(['url' => 'auth/users', 'files' => true, 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::emailGroup('email', trans('general.email'), 'envelope') }}

            {{ Form::passwordGroup('password', trans('auth.password.current'), 'key') }}

            {{ Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key') }}

            {{ Form::selectGroup('locale', trans_choice('general.languages', 1), 'flag', language()->allowed(), setting('general.default_locale')) }}

            @if (setting('general.use_gravatar', '0') == '1')
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
            {{ Form::checkboxGroup('companies', trans_choice('general.companies', 2), $companies, 'company_name') }}
            @endpermission

            @permission('read-auth-roles')
            {{ Form::checkboxGroup('roles', trans_choice('general.roles', 2), $roles, 'display_name') }}
            @endpermission

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('auth/users') }}
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/iCheck/square/green.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';

        $(document).ready(function(){
            $("#locale").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.languages', 1)]) }}"
            });

            @if (setting('general.use_gravatar', '0') != '1')
            $('#picture').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                placeholder : '{{ trans('general.form.no_file_selected') }}'
            });
            @endif

            $('input[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endpush
