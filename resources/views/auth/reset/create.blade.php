@extends('layouts.auth')

@section('title', trans('auth.reset_password'))

@section('message', trans('auth.reset_password'))

@section('content')
    <div role="alert" class="alert alert-danger d-none" :class="(form.response.error) ? 'show' : ''" v-if="form.response.error" v-html="form.response.message"></div>

    {!! Form::open([
        'route' => 'reset.store',
        'id' => 'reset',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}

        <input type="hidden" name="token" value="{{ $token }}">

        @stack('email_input_start')
            {{ Form::emailGroup('email', false, 'envelope', ['placeholder' => trans('general.email')], null, 'has-feedback', 'input-group-alternative') }}
        @stack('email_input_end')

        @stack('password_input_start')
            {{ Form::passwordGroup('password', false, 'unlock-alt', ['placeholder' => trans('auth.password.new')], 'has-feedback', 'input-group-alternative') }}
        @stack('password_input_end')

        @stack('password_confirmation_input_start')
            {{ Form::passwordGroup('password_confirmation', false, 'unlock-alt', ['placeholder' => trans('auth.password.new_confirm')], 'has-feedback', 'input-group-alternative') }}
        @stack('password_confirmation_input_end')

        <div class="row">
            <div class="col-xs-12 col-sm-12">
                {!! Form::button(
                '<div class="aka-loader"></div> <span>' . trans('auth.reset') . '</span>',
                [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-success float-right', 'data-loading-text' => trans('general.loading')]) !!}
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/reset.js?v=' . version('short')) }}"></script>
@endpush
