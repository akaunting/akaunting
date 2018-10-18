@stack($name . '_input_start')

<div class="{{ $col }} input-group-invoice-text">
    <div class="form-group col-md-12 {{ isset($attributes['required']) ? 'required' : '' }} {{ $errors->has($name) ? 'has-error' : ''}}">
        {!! Form::label($name, $text, ['class' => 'control-label']) !!}
        <div class="input-group">
            <div class="input-group-addon"><i class="fa fa-{{ $icon }}"></i></div>
            {!! Form::select($name, $values, $selected, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => $text])], $attributes)) !!}
        </div>
        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group col-md-6 hidden {{ $errors->has('invoice_text_text') ? 'has-error' : '' }}">
        {!! Form::label($input_name, trans('settings.invoice.custom'), ['class' => 'control-label']) !!}
        {!! Form::text($input_name, $input_value, ['class' => 'form-control']) !!}
        {!! $errors->first($input_name, '<p class="help-block">:message</p>') !!}
    </div>
</div>

@stack($name . '_input_end')
