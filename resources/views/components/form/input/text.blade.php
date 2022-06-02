<input type="text"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{!! $value !!}"
    placeholder="{!! $placeholder !!}"
    @if ($disabled)
    disabled="disabled"
    @endif
    @if ($required)
    required="required"
    @endif
    @if ($readonly)
    readonly="readonly"
    @endif
    {{ $attributes->except(['placeholder', 'disabled', 'required', 'readonly', 'v-error', 'v-error-message']) }}
/>