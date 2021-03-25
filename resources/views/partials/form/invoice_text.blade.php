@stack($name . '_input_start')

<div
    class="form-group {{ $col }}{{ isset($attributes['required']) ? ' required' : '' }}{{ isset($attributes['readonly']) ? ' readonly' : '' }}{{ isset($attributes['disabled']) ? ' disabled' : '' }}"
    :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }]"
    @if (isset($attributes['show']))
    v-if="{{ $attributes['show'] }}"
    @endif
    >
    @if (!empty($text))
        {!! Form::label($name, $text, ['class' => 'form-control-label'])!!}
    @endif
    <div class="input-group-invoice-text">
        @foreach ($values as $radio_key => $radio_value)
            <div class="custom-control custom-radio mb-2">
                <input 
                    type="radio"
                    name="{{ $name }}"
                    id="{{ $name }}-{{ $radio_key }}"
                    v-model="form.{{ $name }}"
                    @change="form.errors.clear('{{ $name }}');"
                    class="custom-control-input"
                    @if($selected == $radio_key)
                    checked="checked"
                    @endif
                    value="{{ $radio_key }}">
                <label for="{{ $name }}-{{ $radio_key }}" class="custom-control-label"> {{ $radio_value }} </label>

                @if ($radio_key == 'custom')
                <div :class="[{'has-error': form.errors.get('{{ $input_name }}')}, form.{{ $name }} == 'custom' ? 'col-md-12' : 'd-none']"
                    style="margin-top: -25px; padding-left: 5.5rem;"
                >
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
                            'placeholder' => trans('general.form.enter', ['field' => $text]),
                            'v-model' => !empty($attributes['v-model']) ? $attributes['v-model'] : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.'. $input_name : 'form.' . $input_name),
                        ]) !!}
                    </div>

                    <div class="invalid-feedback d-block"
                        v-if="form.errors.has('{{ $input_name }}')"
                        v-html="form.errors.get('{{ $input_name }}')">
                    </div>
                </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="invalid-feedback d-block"
        v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
        v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
    </div>
</div>

@stack($name . '_input_end')
