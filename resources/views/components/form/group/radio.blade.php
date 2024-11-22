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

        <div @class([
                'mt-1',
                $inputGroupClass,
            ])
        >
            @foreach($options as $option)
                <x-form.input.radio
                    name="{{ $name }}"
                    id="{{ $id }}"
                    class="sr-only"
                    value="{{ $value }}"
                    :checked="($checked && ($option->$optionKey == $checked)) ? true : false"
                    :option="$option"
                    optionKey="{{ $optionKey }}"
                    optionValue="{{ $optionValue }}"
                    {{ $attributes->merge($custom_attributes) }}
                />
            @endforeach

            <input type="hidden" name="{{ $name }}" value="{{ $checked }}" />
        </div>

        @if (! $attributes->has('error') && ! empty($error->contents))
            {!! $error ?? '' !!}
        @else
            <x-form.error name="{{ $name }}" {{ $attributes }} />
        @endif
    </div>
@stack($name . '_input_end')
