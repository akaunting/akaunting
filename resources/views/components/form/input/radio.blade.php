<label
    @click="form.{{ $name }} = '{{ $option->$optionKey }}'"
    class="border rounded-md px-2 py-2.5 flex items-center justify-center text-center text-sm font-medium sm:flex-1 cursor-pointer focus:outline-none transition-all"
    :class="[form.{{ $name }} == '{{ $option->$optionKey }}' ? 'bg-purple border-transparent text-white hover:bg-purple-700' : 'bg-white border-gray-200 text-gray-900 hover:bg-gray-50']"
>
    <input type="radio"
        name="{{ $name }}"
        id="{{ $id . '-' . $option->$optionKey }}"
        aria-labelledby="radio-{{ $option->$optionKey }}"
        @if ($value)
        value="{{ $value }}"
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

    <p id="radio-{{ $option->$optionKey }}">
        {{ $option->$optionValue }}
    </p>
</label>
