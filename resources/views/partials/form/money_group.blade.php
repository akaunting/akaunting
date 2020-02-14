@stack($name . '_input_start')

    <akaunting-money :col="'{{ $col }}'"
        @if (isset($attributes['required']))
        :required="{{ ($attributes['required']) ? 'true' : 'false' }}"
        @endif

        @if (isset($attributes['readonly']))
        :readonly="'{{ $attributes['readonly'] }}'"
        @endif

        @if (isset($attributes['disabled']))
        :disabled="'{{ $attributes['disabled'] }}'"
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
        @endif

        @if (!empty($attributes['v-model']))
        v-model="{{ $attributes['v-model'] }}"
        @endif

        @if (!empty($attributes['change']))
        @change="{{ $attributes['change'] }}($event)"
        @endif

        @if (!empty($attributes['v-model']))
        @interface="{{ $attributes['v-model'] . ' = $event' }}"
        @elseif (!empty($attributes['data-field']))
        @interface="{{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.{{ $name }} = $event"
        @endif
    ></akaunting-money>

@stack($name . '_input_end')
