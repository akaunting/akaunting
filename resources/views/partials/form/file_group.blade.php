@stack($name . '_input_start')

<div class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }} {{ $errors->has($name) ? 'has-error' : '' }}" style="min-height: 59px">
    {!! Form::label($name, $text, ['class' => 'control-label']) !!}
    {!! Form::file($name, array_merge(['class' => 'form-control'], $attributes)) !!}
    {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
</div>

@stack($name . '_input_end')
