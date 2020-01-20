@stack($name . '_input_start')

<akaunting-date
    class="{{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"

    @if (!empty($attributes['v-error']))
    :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
    @else
    :form-classes="[{'has-error': form.errors.get('{{ $name }}') }]"
    @endif

    :icon="'fa fa-{{ $icon }}'"
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

    @if (!empty($attributes['v-model']))
    @interface="{{ $attributes['v-model'] . ' = $event' }}"
    @elseif (!empty($attributes['data-field']))
    @interface="{{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
    @else
    @interface="form.{{ $name }} = $event"
    @endif

    @if(isset($attributes['v-error-message']))
    :form-error="{{ $attributes['v-error-message'] }}"
    @else
    :form-error="form.errors.get('{{ $name }}')"
    @endif
    ></akaunting-date>

@stack($name . '_input_end')
