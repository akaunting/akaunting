{!! Form::open([
    'id' => 'form-create-tax',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button m--3',
    'route' => 'taxes.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::textGroup('rate', trans('taxes.rate'), 'percent') }}

        {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types, 'normal') }}

        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
