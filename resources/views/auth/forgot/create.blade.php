@extends('layouts.auth')

@section('title', trans('auth.reset_password'))
@section('message', trans('auth.reset_password'))

@section('content')
<form role="form" method="POST" action="{{ url('auth/forgot') }}">
    {{ csrf_field() }}

    @stack('email_input_start')

    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
        <input name="email" type="email" class="form-control" placeholder="{{ trans('auth.enter_email') }}" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    @stack('email_input_end')

    <div class="row">
        <!-- /.col -->
        <div class="col-sm-offset-8 col-sm-4">
            <button type="submit" class="btn btn-success btn-block btn-flat">{{ trans('general.send') }}</button>
        </div>
        <!-- /.col -->
    </div>
</form>
@endsection
