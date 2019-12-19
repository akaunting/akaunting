{!! Form::open([
    'id' => 'form-create-tax', 
    'role' => 'form', 
    'class' => 'form-loading-button'
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

        {{ Form::textGroup('rate', trans('taxes.rate'), 'percent') }}

        {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types, 'normal') }}

        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
