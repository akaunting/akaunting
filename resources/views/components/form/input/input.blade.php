<input type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id }}"
    @if ($value)
    value="{!! $value !!}"
    @endif
    placeholder="{{ $placeholder }}"
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
