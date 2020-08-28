@stack($name . '_input_start')
    <div class="{{ $col }} input-group-invoice-text">
        <akaunting-select
            class="float-left{{ isset($attributes['required']) ? ' required' : '' }}{{ isset($attributes['disabled']) ? ' disabled' : '' }}"

            @if (!empty($attributes['v-error']))
            :form-classes="[{'has-error': {{ $attributes['v-error'] }} }, form.{{ $name }} == 'custom' ? 'col-md-6' : 'col-md-12']"
            @else
            :form-classes="[{'has-error': form.errors.has('{{ $name }}') }, form.{{ $name }} == 'custom' ? 'col-md-6' : 'col-md-12']"
            @endif

            icon="{{ $icon }}"
            title="{{ $text }}"
            placeholder="{{ trans('general.form.select.field', ['field' => $text]) }}"
            name="{{ $name }}"
            :options="{{ json_encode($values) }}"

            @if (isset($selected) || old($name))
            value="{{ old($name, $selected) }}"
            @endif

            @if (!empty($attributes['model']))
            :model="{{ $attributes['model'] }}"
            @endif

            @if (!empty($attributes['v-model']))
            @interface="form.errors.clear('{{ $attributes['v-model'] }}'); {{ $attributes['v-model'] . ' = $event' }}"
            @elseif (!empty($attributes['data-field']))
            @interface="form.errors.clear('{{ 'form.' . $attributes['data-field'] . '.' . $name }}'); {{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
            @else
            @interface="form.errors.clear('{{ $name }}'); form.{{ $name }} = $event;"
            @endif

            @if (!empty($attributes['change']))
            @change="{{ $attributes['change'] }}($event)"
            @endif

            @if (isset($attributes['readonly']))
            :readonly="{{ $attributes['readonly'] }}"
            @endif

            @if (isset($attributes['disabled']))
            :disabled="{{ $attributes['disabled'] }}"
            @endif

            @if (isset($attributes['show']))
            v-if="{{ $attributes['show'] }}"
            @endif

            @if (isset($attributes['v-error-message']))
            :form-error="{{ $attributes['v-error-message'] }}"
            @else
            :form-error="form.errors.get('{{ $name }}')"
            @endif

            no-data-text="{{ trans('general.no_data') }}"
            no-matching-data-text="{{ trans('general.no_matching_data') }}"
        ></akaunting-select>

        <div
            class="form-group float-left{{ isset($attributes['required']) ? ' required' : '' }}{{ isset($attributes['readonly']) ? ' readonly' : '' }}{{ isset($attributes['disabled']) ? ' disabled' : '' }}"
            :class="[{'has-error': form.errors.get('{{ $input_name }}')}, form.{{ $name }} == 'custom' ? 'col-md-6' : 'd-none']">
            {!! Form::label($input_name, trans('settings.invoice.custom'), ['class' => 'form-control-label'])!!}

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
    </div>

@stack($name . '_input_end')
