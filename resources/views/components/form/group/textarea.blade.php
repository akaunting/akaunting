@stack($name . '_input_start')
    <div
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

        @if (isset($attributes['v-disabled']) || isset($attributes['v-bind:disabled']))
        :class="[
            {'disabled' : {{ (isset($attributes['v-disabled'])) ? $attributes['v-disabled'] : $attributes['v-bind:disabled'] }}},
            {'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }}}
        ]"
        @else
        :class="[
            {'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }}}
        ]"
        @endif
    >
        @if (! $attributes->has('label') && ! empty($label->contents))
            {!! $label ?? '' !!}
        @elseif (! empty($label))
            <x-form.label for="{{ $name }}" :required="$required">{!! $label !!}</x-form.label>
        @endif

        @php
            $vue_key = '@input';
            $vue_value = !empty($attributes['v-model']) ? $attributes['v-model'] . ' = $event.target.value' : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.'. $name . ' = $event.target.value' : 'form.' . $name . ' = $event.target.value');

            if (!empty($attributes['enable-v-model'])) {
                $vue_key = 'v-model';
                $vue_value = !empty($attributes['v-model']) ? $attributes['v-model'] : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.'. $name : 'form.' . $name);
            }

            $custom_attributes = array_merge([$vue_key => $vue_value], $custom_attributes);
            $rows = !empty($rows) ? $rows : 3;
        @endphp

        <x-form.input.textarea
            name="{{ $name }}"
            id="{{ $id }}"
            value="{!! $value !!}"
            placeholder="{{ $placeholder }}"
            rows="{{ $rows }}"
            :disabled="$disabled"
            {{ $attributes->merge($custom_attributes) }}
        />

        @if (! $attributes->has('error') && ! empty($error->contents))
            {!! $error ?? '' !!}
        @else
            <x-form.error name="{{ $name }}" {{ $attributes }} />
        @endif
    </div>
@stack($name . '_input_end')
