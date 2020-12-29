@stack($name . '_input_start')

    <akaunting-money :col="'{{ $col }}'"
        @if (!empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.has('{{ $name }}') }]"
        @endif

        @if (!empty($attributes['money-class']))
        money-class="{{ $attributes['money-class'] }}"
        @endif

        @if (isset($attributes['required']))
        :required="{{ ($attributes['required']) ? 'true' : 'false' }}"
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

        @if (isset($attributes['masked']))
        :masked="{{ ($attributes['masked']) ? 'true' : 'false' }}"
        @endif

        :error="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }}"
        name="{{ $name }}"
        title="{{ $text }}"
        :group_class="'{{ $group_class }}'"
        icon="{{ $icon }}"
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
        :form-error="{{ $attributes['v-error-message'] }}"
        @else
        :form-error="form.errors.get('{{ $name }}')"
        @endif

        @if (isset($attributes['row-input']))
        :row-input="{{ $attributes['row-input'] }}"
        @endif
    ></akaunting-money>

@stack($name . '_input_end')
