{!! Form::open([
    'id' => 'form-create-currency',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'modals.currencies.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'chart-bar') }}

        {{ Form::selectGroup('code', trans('currencies.code'), 'code', $codes) }}

        {{ Form::textGroup('rate', trans('currencies.rate'), 'sliders-h', ['@input' => 'onChangeRate', 'required' => 'required']) }}

        {!! Form::hidden('enabled', 1) !!}
        {!! Form::hidden('symbol_first', 1) !!}
        {!! Form::hidden('default_currency', 0) !!}
    </div>
{!! Form::close() !!}
