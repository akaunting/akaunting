@stack($name . '_input_start')
    <akaunting-money 
        @if (! empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.has('{{ $name }}') }]"
        @endif

        col="{{ $formGroupClass }}"

        @if (! empty($attributes['money-class']))
        money-class="{{ $attributes['money-class'] }}"
        @endif

        @if ($required)
        :required="{{ ($required) ? 'true' : 'false' }}"
        @endif

        @if ($readonly)
        :readonly="{{ $readonly }}"
        @endif

        @if (isset($attributes['v-disabled']))
        :disabled="{{ $attributes['v-disabled'] }}"
        @endif

        @if ($disabled)
        :disabled="{{ $disabled }}"
        @endif

        @if (isset($attributes['v-show']))
        v-if="{{ $attributes['v-show'] }}"
        @endif

        @if (isset($attributes['masked']))
        :masked="{{ ($attributes['masked']) ? 'true' : 'false' }}"
        @endif

        {{-- :error="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }}" --}}

        name="{{ $name }}"
        title="{!! $label !!}"
        :group_class="'{{ $formGroupClass }}'"

        @if (! empty($icon))
        icon="{{ $icon }}"
        @endif

        :currency="{{ json_encode($attributes['currency']) }}"
        :value="{{ $value }}"

        @if (!empty($attributes['dynamic-currency']))
        :dynamic-currency="{{ $attributes['dynamic-currency'] }}"
        @else
        :dynamic-currency="currency"
        @endif

        @if (!empty($attributes['v-model']))
        v-model="{{ $attributes['v-model'] }}"
        @endif

        @if (!empty($attributes['change']))
        @change="{{ $attributes['change'] }}($event)"
        @endif

        @if (!empty($attributes['input']))
        @input="{{ $attributes['input'] }}"
        @endif

        @if (!empty($attributes['v-model']))
        @interface="form.errors.clear('{{ $attributes['v-model'] }}'); {{ $attributes['v-model'] . ' = $event' }}"
        @elseif (!empty($attributes['data-field']))
        @interface="form.errors.clear('{{ 'form.' . $attributes['data-field'] . '.' . $name }}'); {{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.errors.clear('{{ $name }}'); form.{{ $name }} = $event"
        @endif

        @if (isset($attributes['v-error-message']))
        :error="{{ $attributes['v-error-message'] }}"
        @else
        :error="form.errors.get('{{ $name }}')"
        @endif

        @if (isset($attributes['row-input']))
        :row-input="{{ $attributes['row-input'] }}"
        @endif
    ></akaunting-money>
@stack($name . '_input_end')
