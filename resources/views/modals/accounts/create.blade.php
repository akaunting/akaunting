{!! Form::open([
    'id' => 'form-create-account',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'accounts.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::textGroup('number', trans('accounts.number'), 'pencil-alt') }}

        {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency'), ['required' => 'required', 'change' => 'onChangeCurrency']) }}

        {{ Form::moneyGroup('opening_balance', trans('accounts.opening_balance'), 'balance-scale', ['required' => 'required', 'currency' => $currency], 0.00) }}

        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
