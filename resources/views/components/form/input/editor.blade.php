<akaunting-html-editor
    name="{{ $name }}"

    @if (! empty($value))
    {{-- A JS string, as a template literal would run the ${} of the value; the group escaped the value --}}
    :value="{{ \Illuminate\Support\Js::from(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5)) }}"
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
