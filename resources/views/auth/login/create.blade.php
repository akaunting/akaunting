@extends('layouts.auth')

@section('title', trans('auth.login'))
@section('message', trans('auth.login_to'))

@section('content')
<form role="form" method="POST" action="{{ url('auth/login') }}" class="form-loading-button">
    {{ csrf_field() }}

    @stack('email_input_start')
    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
        <input name="email" type="email" class="form-control" placeholder="{{ trans('general.email') }}" required autofocus>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    @stack('email_input_end')

    @stack('password_input_start')
    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
        <input name="password" type="password" class="form-control" placeholder="{{ trans('auth.password.current') }}" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
    @stack('password_input_end')

    <div class="row">
        @stack('remember_input_start')
        <div class="col-sm-8">
            <div class="checkbox icheck">
                <label>
                    <input name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}> &nbsp;{{ trans('auth.remember_me') }}
                </label>
            </div>
        </div>
        @stack('remember_input_end')
        <!-- /.col -->

        <div class="col-sm-4">
            <button type="submit" class="btn btn-success btn-block btn-flat button-submit" data-loading-text="{{ trans('general.loading') }}">{{ trans('auth.login') }}</button>
        </div>
        <!-- /.col -->
    </div>
</form>

<a href="{{ url('auth/forgot') }}">{{ trans('auth.forgot_password') }}</a><br>
@endsection

@push('js')
<!-- iCheck -->
<script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
@endpush

@push('css')
<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/iCheck/square/green.css') }}">
@endpush

@push('scripts')
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
            increaseArea: '20%' // optional
        });
    });
</script>
@endpush
