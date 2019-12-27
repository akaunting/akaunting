@stack($name . '_input_start')

<div
    class="form-group {{ isset($attributes['col']) ? $attributes['col'] : $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
    :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }]">
    {!! Form::label($name, $text, ['class' => 'form-control-label'])!!} (<a href="{{$link}}" target="_blank">{{ trans('settings.integracoes.link_get_api') }}</a>)

    @if(($icon) && ($icon != ''))
    <div class="input-group input-group-merge {{ $group_class }}">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fa fa-{{ $icon }}"></i>
            </span>
        </div>
        {!! Form::text($name, $value, array_merge([
            'class' => 'form-control',
            'data-name' => $name,
            'data-value' => $value,
            'placeholder' => trans('general.form.enter', ['field' => $text]),
            'v-model' => !empty($attributes['v-model']) ? $attributes['v-model'] : 'form.' . $name
        ], $attributes)) !!}
    </div>
    @else
    {!! Form::text($name, $value, array_merge([
        'class' => 'form-control',
        'data-name' => $name,
        'data-value' => $value,
        'placeholder' => trans('general.form.enter', ['field' => $text]),
        'v-model' => !empty($attributes['v-model']) ? $attributes['v-model'] : 'form.' . $name
    ], $attributes)) !!}
    @endif

    <div class="invalid-feedback d-block"
         v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
         v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
    </div>
</div>

@stack($name . '_input_end')
