{!! Form::open([
    'id' => 'form-create-tax',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'taxes.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::textGroup('rate', trans('taxes.rate'), 'percent') }}

        {!! Form::hidden('type', 'normal') !!}
        {!! Form::hidden('enabled', '1') !!}
    </div>
{!! Form::close() !!}
