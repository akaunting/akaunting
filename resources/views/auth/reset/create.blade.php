@extends('layouts.auth')

@section('title', trans('auth.reset_password'))
@section('message', trans('auth.reset_password'))

@section('content')
<form role="form" method="POST" action="{{ url('auth/reset') }}">
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">

    @stack('email_input_start')
    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
        <input name="email" type="email" class="form-control" placeholder="{{ trans('auth.current_email') }}" value="{{ $email or old('email') }}" required autofocus>
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
        <input name="password" type="password" class="form-control" placeholder="{{ trans('auth.password.new') }}" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
    @stack('password_input_end')

    @stack('password_confirmation_input_start')
    <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <input name="password_confirmation" type="password" class="form-control" placeholder="{{ trans('auth.password.new_confirm') }}" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
    </div>
    @stack('password_confirmation_input_end')

    <div class="row">
        <!-- /.col -->
        <div class="col-sm-offset-8 col-sm-4">
            <button type="submit" class="btn btn-success btn-block btn-flat">{{ trans('auth.reset') }}</button>
        </div>
        <!-- /.col -->
    </div>
</form>
@endsection
