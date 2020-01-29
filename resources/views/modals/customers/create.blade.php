{!! Form::open([
    'id' => 'form-create-customer',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'customers.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

        {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

        {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency')) }}

        {{ Form::textareaGroup('address', trans('general.address')) }}

        {{ Form::hidden('type', 'customer') }}
        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
