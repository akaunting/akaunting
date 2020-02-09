@stack($name . '_input_start')

    <akaunting-money :col="'{{ $col }}'"
        :required="{{ isset($attributes['required']) ? true : false }}"

        @if (isset($attributes['readonly']))
        :readonly="'{{ $attributes['readonly'] }}'"
        @endif

        @if (isset($attributes['disabled']))
        :disabled="'{{ $attributes['disabled'] }}'"
        @endif

        @if (isset($attributes['masked']))
        :masked="'{{ $attributes['masked'] }}'"
        @endif

        :error="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }}"
        :name="'{{ $name }}'"
        :title="'{{ $text }}'"
        :group_class="'{{ $group_class }}'"
        :icon="'{{ $icon }}'"
        :currency="{{ json_encode($attributes['currency']) }}"
        :value="{{ $value }}"

        @if (!empty($attributes['v-model']))
        @interface="{{ $attributes['v-model'] . ' = $event' }}"
        @elseif (!empty($attributes['data-field']))
        @interface="{{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.{{ $name }} = $event"
        @endif
    ></akaunting-money>

@stack($name . '_input_end')
