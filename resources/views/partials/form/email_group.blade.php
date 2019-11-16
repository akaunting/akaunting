@stack($name . '_input_start')

<div
    class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
    :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get(' . $name . ')' }} }]">
    @if ($text)
    {!! Form::label($name, $text, ['class' => 'form-control-label'])!!}
    @endif

    <div class="input-group input-group-merge {{ $group_class }}">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fa fa-{{ $icon }}"></i>
            </span>
        </div>
        {!! Form::email($name, $value, array_merge([
            'class' => 'form-control',
            'data-name' => $name,
            'data-value' => $value,
            'placeholder' => trans('general.form.select.field', ['field' => $text]),
            'v-model' => !empty($attributes['v-model']) ? $attributes['v-model'] : 'form.' . $name
        ], $attributes)) !!}
    </div>

    <div class="invalid-feedback d-block"
         v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has(' . $name . ')' }}"
         v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get(' . $name . ')' }}">
    </div>
</div>

@stack($name . '_input_end')
