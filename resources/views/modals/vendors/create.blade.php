{!! Form::open([
    'id' => 'form-create-vendor',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'vendors.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

        {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

        {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency')) }}

        {{ Form::textareaGroup('address', trans('general.address')) }}

        {{ Form::hidden('type', 'vendor') }}
        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
