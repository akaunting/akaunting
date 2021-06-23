
{!! Form::model($customer, [
    'id' => 'form-edit-customer',
    'method' => 'PATCH',
    'route' => ['customers.update', $customer->id],
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

        {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', [], $customer->tax_number) }}

        {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, $customer->currency_code) }}

        {{ Form::textareaGroup('address', trans('general.address'), null, $customer->address) }}

        {{ Form::hidden('type', 'customer') }}
        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
