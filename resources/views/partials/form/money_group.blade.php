@stack($name . '_input_start')

<div
    class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
    :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get(' . $name . ')' }} }]">
    {!! Form::label($name, $text, ['class' => 'form-control-label'])!!}

    <div class="input-group input-group-merge {{ $group_class }}">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fa fa-{{ $icon }}"></i>
            </span>
        </div>
        @php
            if ($attributes['currency']) {
                $value = number_format($value, $attributes['currency']->precision, $attributes['currency']->decimal_mark, $attributes['currency']->thousands_separator);
            } else {
                $value = number_format($value, 2);
            }
        @endphp
        {!! Form::text($name, $value, array_merge([
            'class' => 'form-control',
            'data-name' => $name,
            'data-value' => $value,
            'v-model.lazy' => !empty($attributes['v-model']) ? $attributes['v-model'] : 'form.' . $name,
            'v-money' => 'money'
        ], $attributes)) !!}
    </div>

    <div class="invalid-feedback d-block"
         v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has(' . $name . ')' }}"
         v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get(' . $name . ')' }}">
    </div>
</div>

@stack($name . '_input_end')
