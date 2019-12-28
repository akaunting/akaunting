@extends('layouts.auth')

@section('message', trans('auth.reset_password'))

@section('content')
    {!! Form::open([
        'route' => 'forgot',
        'id' => 'forgot',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}


        @stack('email_input_start')
            {{ Form::emailGroup('email', false, 'envelope', ['placeholder' => trans('general.email')], null, 'has-feedback', 'input-group-alternative') }}
        @stack('email_input_end')

        <button type="submit" class="btn btn-success float-right">{{ trans('general.send') }}</button>
    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/forgot.js?v=' . version('short')) }}"></script>
@endpush
