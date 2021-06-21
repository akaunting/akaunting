@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.customers', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'customers.store',
            'id' => 'customer',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'autocomplete' => "off",
            'class' => 'form-loading-button needs-validation',
            'novalidate' => 'true'
        ]) !!}

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('general.name'), 'user') }}

                {{ Form::textGroup('email', trans('general.email'), 'envelope', ['autocomplete' => 'off']) }}

                {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

                {{ Form::selectAddNewGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency'), ['required' => 'required', 'path' => route('modals.currencies.create'), 'field' => ['key' => 'code', 'value' => 'name']]) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

                {{ Form::textGroup('website', trans('general.website'), 'globe', []) }}

                {{ Form::textareaGroup('address', trans('general.address')) }}

                {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}

                @stack('create_user_input_start')
                    <div id="customer-create-user" class="form-group col-md-12 margin-top">
                        <div class="custom-control custom-checkbox">
                            {{ Form::checkbox('create_user', '1', null, [
                                'v-model' => 'form.create_user',
                                'id' => 'create_user',
                                'class' => 'custom-control-input',
                                '@input' => 'onCanLogin($event)'
                            ]) }}

                            <label class="custom-control-label" for="create_user">
                                <strong>{{ trans('customers.can_login') }}</strong>
                            </label>
                        </div>
                    </div>
                @stack('create_user_input_end')

                <div v-if="can_login" class="row col-md-12">
                    {{Form::passwordGroup('password', trans('auth.password.current'), 'key', ['required' => 'required'], 'col-md-6 password')}}

                    {{Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key', ['required' => 'required'], 'col-md-6 password')}}
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('customers.index') }}
            </div>
        </div>

        {{ Form::hidden('type', 'customer') }}

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script>
        var can_login_errors = {
            valid: '{!! trans('validation.required', ['attribute' => 'email']) !!}',
            email: '{{ trans('customers.error.email') }}'
        };
    </script>

    <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
