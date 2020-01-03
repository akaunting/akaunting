{!! Form::open([
    'id' => 'form-create-category',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'categories.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

        {!! Form::hidden('type', $type, []) !!}
        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
