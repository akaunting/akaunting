@stack($name . '_input_start')

<div class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }} {{ $errors->has($name) ? 'has-error' : '' }}">
    {!! Form::label($name, $text, ['class' => 'control-label']) !!}
    <div class="input-group">
        <div class="btn-group radio-inline" data-toggle="buttons">
            <label id="{{ $name }}_1" class="btn btn-default">
                {!! Form::radio($name, '1') !!}
                <span class="radiotext">{{ trans('general.yes') }}</span>
            </label>
            <label id="{{ $name }}_0" class="btn btn-default">
                {!! Form::radio($name, '0', true) !!}
                <span class="radiotext">{{ trans('general.no') }}</span>
            </label>
        </div>
    </div>
    {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
</div>

@stack($name . '_input_end')
