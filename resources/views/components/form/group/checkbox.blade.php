@stack($name . '_input_start')
    <div
        @class([
            'relative',
            $formGroupClass,
            'required' => $required,
            'readonly' =>  $readonly,
            'disabled' => $disabled,
        ])

        @if (isset($attributes['v-show']))
        v-if="{{ $attributes['v-show'] }}"
        @endif

        @if (isset($attributes['v-disabled']))
        :class="[
            {'disabled' : {{ $attributes['v-disabled'] }}}, 
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
                'flex items-center justify-start lg:justify-center grid sm:grid-cols-6',
                $inputGroupClass,
            ])
        >
            @php
                $option_id = $attributes[':id'];
            @endphp
            @foreach($options as $option)
                @php
                if (! empty($attributes[':id'])) {
                    $attributes[':id'] = str_replace(':item_id', $option->$optionKey, $option_id);
                }
                @endphp
                <div class="{{ ! empty($attributes['checkbox-class']) ? $attributes['checkbox-class'] : 'sm:col-span-3' }}">
                    <div class="custom-control custom-checkbox">
                        <x-form.input.checkbox
                            name="{{ $name }}"
                            label="{{ $option->$optionValue }}"
                            id="{{ 'checkbox-' . $name . '-' . $option->$optionKey }}"
                            :checked="(is_array($checked) && count($checked)) ? (in_array($option->$optionKey, $checked) ? true : 'n/a') : $checked"
                            value="{{ $option->$optionKey }}"
                            data-type="{{ (is_array($checked)) ? 'multiple' : 'single' }}"
                            v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.' . $name : 'form.' . $name) }}"
                            :option="$option"
                            optionKey="{{ $optionKey }}"
                            optionValue="{{ $optionValue }}"
                            disabled="{{ $disabled }}"
                            {{ $attributes->merge($custom_attributes) }}
                        />
                    </div>
                </div>
            @endforeach
        </div>

        @if (! $attributes->has('error') && ! empty($error->contents))
            {!! $error ?? '' !!}
        @else
            <x-form.error name="{{ $name }}" {{ $attributes }} />
        @endif
    </div>
@stack($name . '_input_end')
