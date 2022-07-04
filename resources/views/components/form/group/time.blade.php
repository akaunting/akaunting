@stack($name . '_input_start')

    <akaunting-date
        @class([
            'relative',
            $formGroupClass,
            'required' => $required,
            'readonly' => $readonly,
            'disabled' => $disabled,
        ])

        @if (isset($attributes['v-show']))
        v-if="{{ $attributes['v-show'] }}"
        @endif

        @if ($required)
        :required="{{ $required ? 'true' : 'false' }}"
        @else
        :not-required={{ $required ? 'false' : 'true' }}
        @endif

        @if ($readonly)
        :readonly="{{ $readonly }}"
        @endif

        @if ($disabled)
        :disabled="{{ $disabled }}"
        @endif

        @if (! empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.get('{{ $name }}') }]"
        @endif

        @if (! empty($icon))
        icon="{{ $icon }}"
        @endif

        title="{!! $label !!}"

        placeholder="{{ $placeholder }}"

        name="{{ $name }}"

        @if (isset($value) || old($name))
        value="{{ old($name, $value) }}"
        @endif

        @if (! empty($attributes['model']))
        :model="{{ $attributes['model'] }}"
        @endif

        @if (! empty($attributes['value']))
        :value="{{ $attributes['value'] }}"
        @endif

        :date-config="{
            allowInput: true,
            wrap: true,
            enableTime: true,
            @if (! empty($attributes['seconds']))
            enableSeconds: true,
            @endif
            @if (! empty($attributes['time_24hr']))
            wrap: false,
            time_24hr: true,
            @else
            wrap: true,
            @endif
            noCalendar: true
        }"

        @if (! empty($attributes['v-model']))
        @interface="form.errors.clear('{{ $attributes['v-model'] }}'); {{ $attributes['v-model'] . ' = $event' }}"
        @elseif (! empty($attributes['data-field']))
        @interface="form.errors.clear('{{ 'form.' . $attributes['data-field'] . '.' . $name }}'); {{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.errors.clear('{{ $name }}'); form.{{ $name }} = $event"
        @endif

        @if (! empty($attributes['change']))
        @change="{{ $attributes['change'] }}"
        @endif

        @if(isset($attributes['v-error-message']))
        :form-error="{{ $attributes['v-error-message'] }}"
        @else
        :form-error="form.errors.get('{{ $name }}')"
        @endif
    ></akaunting-date>

@stack($name . '_input_end')
