<textarea
    name="{{ $name }}"
    id="{{ $id }}"
    rows="{{ $rows }}"
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
    {{ $attributes->except(['rows', 'placeholder', 'disabled', 'required', 'readonly']) }}
>{!! $value !!}</textarea>
