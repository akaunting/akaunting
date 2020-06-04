@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.accounts', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'accounts.store',
            'id' => 'account',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'font') }}

                    {{ Form::textGroup('number', trans('accounts.number'), 'pencil-alt') }}

                    {{ Form::selectAddNewGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency'), ['required' => 'required', 'path' => route('modals.currencies.create'), 'field' => ['key' => 'code', 'value' => 'name'], 'change' => 'onChangeCurrency']) }}

                    {{ Form::moneyGroup('opening_balance', trans('accounts.opening_balance'), 'balance-scale', ['required' => 'required', 'currency' => $currency, 'dynamic-currency' => 'currency'], 0) }}

                    {{ Form::textGroup('bank_name', trans('accounts.bank_name'), 'university', []) }}

                    {{ Form::textGroup('bank_phone', trans('accounts.bank_phone'), 'phone', []) }}

                    {{ Form::textareaGroup('bank_address', trans('accounts.bank_address')) }}

                    {{ Form::radioGroup('default_account', trans('accounts.default_account'), false) }}

                    {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('accounts.index') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/accounts.js?v=' . version('short')) }}"></script>
@endpush
