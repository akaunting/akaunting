@extends('layouts.auth')

@section('title', trans('auth.reset_password'))
@section('message', trans('auth.reset_password'))

@section('content')
    <form role="form" method="POST" action="{{ url('auth/forgot') }}">
        {{ csrf_field() }}

        @stack('email_input_start')
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                    <input class="form-control" placeholder="{{ trans('general.email') }}" name="email" type="email">
                </div>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        @stack('email_input_end')

        <button type="submit" class="btn btn-success float-right">{{ trans('general.send') }}</button>
    </form>
@endsection
