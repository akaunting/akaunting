<input type="checkbox"
    name="{{ $name }}"
    class="rounded-sm text-purple border-gray-300 cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent"
    id="{{ ! empty($attributes[':id']) ? $attributes[':id'] : $id }}"
    @if ($value)
    value="{!! $value !!}"
    @endif
    @if (! empty($checked) && (string) $checked != 'n/a')
    checked="checked"
    @endif
    @if ($disabled)
    disabled="disabled"
    @endif
    @if ($required)
    required="required"
    @endif
    @if ($readonly)
    readonly="readonly"
    @endif
    {{ $attributes->except(['placeholder', 'disabled', 'required', 'readonly', 'v-error', 'v-error-message', 'option', 'optionKey', 'optionValue']) }}
/>

<label class="ml-2" for="{{ ! empty($attributes[':id']) ? $attributes[':id'] : $id }}">
    {{ !empty($option) ? $option->$optionValue : $label }}
</label>
