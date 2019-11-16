@stack($name . '_input_start')
    <div
        class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
        :class="[{'has-error': errors.{{ $name }}}]">
        {!! Form::label($name, $text, ['class' => 'form-control-label']) !!}

        <div class="custom-file">
            {!! Form::file($name, array_merge([
                'class' => 'custom-file-input form-attachment cursor',
                '@input' => 'onHandleFileUpload("' . $name .'", $event)'
            ], $attributes)) !!}
            {!! Form::label($name, $text, ['class' => 'custom-file-label']) !!}
        </div>

        <div class="invalid-feedback d-block"
            v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
            v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
        </div>
    </div>
@stack($name . '_input_end')
