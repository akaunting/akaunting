@stack($name . '_input_start')

<div
    class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
    :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }]">
    {!! Form::label($name, $text, ['class' => 'form-control-label']) !!}

    <html-editor
        :name="'{{ $name }}'"
        :value="form.{{ $name }} = '{{ $value }}'"
        @input="form.{{ $name }} = $event"></html-editor>

    <div class="invalid-feedback d-block"
         v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
         v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
    </div>
</div>

@stack($name . '_input_end')
