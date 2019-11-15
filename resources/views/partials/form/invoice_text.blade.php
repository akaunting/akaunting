@stack($name . '_input_start')

<div
    class="form-group {{ $col }}{{ isset($attributes['required']) ? ' required' : '' }}"
    :class="[{'has-error': form.errors.get('{{ $name }}')}]">
    {!! Form::label($name, $text, ['class' => 'form-control-label']) !!}

    <div class="input-group input-group-merge {{ $group_class }}">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fa fa-{{ $icon }}"></i>
            </span>
        </div>

        {!! Form::text($input_name, $input_value, [
            'class' => 'form-control',
            'data-name' => $input_name,
            'data-value' => $input_value,
            'placeholder' => trans('general.form.select.field', ['field' => $text]),
            'v-model' => !empty($attributes['v-model']) ? $attributes['v-model'] : 'form.' . $input_name
        ]) !!}
    </div>

    <div class="invalid-feedback d-block"
         v-if="form.errors.has('{{ $input_name }}')"
         v-html="form.errors.get('{{ $input_name }}')">
    </div>
</div>

@stack($name . '_input_end')
