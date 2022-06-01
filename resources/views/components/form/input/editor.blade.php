<akaunting-html-editor
    name="{{ $name }}"

    @if (! empty($value))
    :value="`{!! $value !!}`"
    @else
    :value="''"
    @endif

    @if (! empty($attributes['model']))
    :model="{{ $attributes['model'] }}"
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
></akaunting-html-editor>
