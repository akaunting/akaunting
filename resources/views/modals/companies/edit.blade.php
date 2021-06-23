{!! Form::open([
    'id' => 'setting',
    'method' => 'PATCH',
    'route' => 'settings.update',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('settings.company.name'), 'building', ['required' => 'required'], setting('company.name')) }}

        {{ Form::textGroup('email', trans('settings.company.email'), 'envelope', ['required' => 'required'], setting('company.email')) }}

        {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', [], setting('company.tax_number')) }}

        {{ Form::textGroup('phone', trans('settings.company.phone'), 'phone', [], setting('company.phone')) }}

        {{ Form::textareaGroup('address', trans('settings.company.address'), null, setting('company.address')) }}

        {!! Form::hidden('_prefix', 'company') !!}
    </div>
{!! Form::close() !!}
