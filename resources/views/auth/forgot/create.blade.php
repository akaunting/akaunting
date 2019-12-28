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

        <div class="row">
            <div class="col-xs-12 col-sm-12">
                {!! Form::button(
                '<div class="aka-loader"></div> <span>' . trans('general.send') . '</span>',
                [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-success header-button-top float-right', 'data-loading-text' => trans('general.loading')]) !!}
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/forgot.js?v=' . version('short')) }}"></script>
@endpush
