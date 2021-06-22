@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.customers', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($customer, [
            'method' => 'PATCH',
            'route' => ['customers.update', $customer->id],
            'role' => 'form',
            'id' => 'customer',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'class' => 'form-loading-button',
            'novalidate' => 'true'
        ]) !!}

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('general.name'), 'user') }}

                {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

                {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

                {{ Form::selectAddNewGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, $customer->currency_code, ['required' => 'required', 'path' => route('modals.currencies.create'), 'field' => ['key' => 'code', 'value' => 'name']]) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

                {{ Form::textGroup('website', trans('general.website'), 'globe',[]) }}

                {{ Form::textareaGroup('address', trans('general.address')) }}

                {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), $customer->enabled) }}

                @stack('create_user_input_start')
                    <div id="customer-create-user" class="form-group col-md-12 margin-top">
                        <div class="custom-control custom-checkbox">
                            @if ($customer->user_id)
                                {{ Form::checkbox('create_user', '1', 1, [
                                    'id' => 'create_user',
                                    'class' => 'custom-control-input',
                                    'disabled' => 'true'
                                ]) }}

                                <label class="custom-control-label" for="create_user">
                                    <strong>{{ trans('customers.user_created') }}</strong>
                                </label>
                            @else
                                {{ Form::checkbox('create_user', '1', null, [
                                    'id' => 'create_user',
                                    'class' => 'custom-control-input',
                                    'v-on:input' => 'onCanLogin($event)'
                                ]) }}

                                <label class="custom-control-label" for="create_user">
                                    <strong>{{ trans('customers.can_login') }}</strong>
                                </label>
                            @endif
                        </div>
                    </div>
                @stack('create_user_input_end')

                <div v-if="can_login" class="row col-md-12">
                    {{Form::passwordGroup('password', trans('auth.password.current'), 'key', ['required' => 'required'], 'col-md-6 password')}}

                    {{Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key', ['required' => 'required'], 'col-md-6 password')}}
                </div>
            </div>
        </div>

        @can('update-sales-customers')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('customers.index') }}
                </div>
            </div>
        @endcan

        {{ Form::hidden('type', 'customer') }}

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
