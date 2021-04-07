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

        <akaunting-html-editor
            name="{{ $name }}"

            @if (!empty($value))
            :value="`{{ $value }}`"
            @else
            :value="''"
            @endif

            @if (!empty($attributes['v-model']))
            @input="{{ $attributes['v-model'] . ' = $event' }}"
            @elseif (!empty($attributes['data-field']))
            @input="{{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
            @else
            @input="form.{{ $name }} = $event"
            @endif

            @if (isset($attributes['disabled']))
            :disabled="{{ $attributes['disabled'] }}"
            @endif

            @if (isset($attributes['readonly']))
            :readonly="{{ $attributes['readonly'] }}"
            @endif
        ></akaunting-html-editor>

        <div class="invalid-feedback d-block"
            v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
            v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
        </div>
    </div>

@stack($name . '_input_end')
