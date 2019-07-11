@stack($name . '_input_start')

<div class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }} {{ $errors->has($name) ? 'has-error' : '' }}">
    {!! Form::label($name, $text, ['class' => 'control-label']) !!}
    <br/>
    @foreach($items as $item)
        <div class="col-md-3">
            {{ Form::checkbox($name . '[]', $item->$id) }} &nbsp; {{ $item->$value }}
        </div>
    @endforeach
    {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
</div>

@stack($name . '_input_end')
