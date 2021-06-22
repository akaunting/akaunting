
{!! Form::model($vendor, [
    'id' => 'form-edit-vendor',
    'method' => 'PATCH',
    'route' => ['vendors.update', $vendor->id],
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

        {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

        {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, $vendor->currency_code) }}

        {{ Form::textareaGroup('address', trans('general.address'), null, $vendor->address) }}

        <div class="form-group col-md-12 d-none">
            <textarea name="address" class="form-control" rows="3">{{ $vendor->address }}</textarea>
        </div>

        {{ Form::hidden('type', 'vendor') }}
        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
