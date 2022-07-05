@php
    $class= 'w-full h-24 text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple';

    if ($attributes->has('override')) {
        $class = $attributes->get('override');
    } elseif ($attributes->get('class')) {
        $class .= ' ' . $attributes->get('class');
    }
@endphp
<textarea
    name="{{ $name }}"
    id="{{ $id }}"
    class="{{ $class }}"
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
