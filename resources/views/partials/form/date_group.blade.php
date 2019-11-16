@stack($name . '_input_start')

    <akaunting-date
        class="{{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
        :form-classes="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get(' . $name . ')' }} }]"
        :title="'{{ $text }}'"
        :placeholder="'{{ trans('general.form.select.field', ['field' => $text]) }}'"
        :name="'{{ $name }}'"
        :value="'{{ old($name, $value) }}'"
        :config="{
            allowInput: true,
            @if (!empty($attributes['show-date-format']))
            altInput: true,
            altFormat: '{{ $attributes['show-date-format'] }}',
            @endif
            @if (!empty($attributes['date-format']))
            dateFormat: '{{ $attributes['date-format'] }}'
            @endif
         }"
        :icon="'fa fa-{{ $icon }}'"
        @interface="{{ !empty($attributes['v-model']) ? $attributes['v-model'] . ' = $event' : 'form.' . $name . ' = $event' }}"
        :form-error="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get(' . $name . ')' }}"
    ></akaunting-date>

@stack($name . '_input_end')
