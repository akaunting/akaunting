@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.accounts', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($account, [
            'id' => 'account',
            'method' => 'PATCH',
            'route' => ['accounts.update', $account->id],
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

                    {{ Form::selectAddNewGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, $account->currency_code, ['required' => 'required', 'path' => route('modals.currencies.create'), 'change' => 'onChangeCurrency']) }}

                    {{ Form::moneyGroup('opening_balance', trans('accounts.opening_balance'), 'balance-scale', ['required' => 'required', 'currency' => $currency], $account->opening_balance) }}

                    {{ Form::textGroup('bank_name', trans('accounts.bank_name'), 'university', []) }}

                    {{ Form::textGroup('bank_phone', trans('accounts.bank_phone'), 'phone', []) }}

                    {{ Form::textareaGroup('bank_address', trans('accounts.bank_address')) }}

                    {{ Form::radioGroup('default_account', trans('accounts.default_account'), $account->default_account) }}

                    {{ Form::radioGroup('enabled', trans('general.enabled'), $account->enabled) }}
                </div>
            </div>

            @permission('update-banking-accounts')
                <div class="card-footer">
                    <div class="row save-buttons">
                        {{ Form::saveButtons('accounts.index') }}
                    </div>
                </div>
            @endpermission
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/accounts.js?v=' . version('short')) }}"></script>
@endpush
