@stack($name . '_input_start')

    <akaunting-date
        class="{{ $col }}{{ isset($attributes['required']) ? ' required' : '' }}"

        @if (!empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.get('{{ $name }}') }]"
        @endif
        :group_class="'{{ $group_class }}'"

        icon="fa fa-{{ $icon }}"
        title="{{ $text }}"
        placeholder="{{ trans('general.form.select.field', ['field' => $text]) }}"
        name="{{ $name }}"

        @if (isset($value) || old($name))
        value="{{ old($name, $value) }}"
        @endif

        @if (!empty($attributes['model']))
        :model="{{ $attributes['model'] }}"
        @endif

        :date-config="{
            wrap: true, // set wrap to true only when using 'input-group'
            allowInput: false,
            @if (!empty($attributes['show-date-format']))
            altInput: true,
            altFormat: '{{ $attributes['show-date-format'] }}',
            @endif
            @if (!empty($attributes['date-format']))
            dateFormat: '{{ $attributes['date-format'] }}',
            @endif
            @if (!empty($attributes['min-date']))
            minDate: {{ $attributes['min-date'] }},
            @endif
            @if (!empty($attributes['max-date']))
            maxDate: {{ $attributes['max-date'] }},
            @endif
        }"

        locale="{{ language()->getShortCode() }}"

        @if (isset($attributes['period']))
        period="{{ $attributes['period'] }}"
        @endif

        @if (!empty($attributes['v-model']))
        @interface="form.errors.clear('{{ $attributes['v-model'] }}'); {{ $attributes['v-model'] . ' = $event' }}"
        @elseif (!empty($attributes['data-field']))
        @interface="form.errors.clear('{{ 'form.' . $attributes['data-field'] . '.' . $name }}'); {{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.errors.clear('{{ $name }}'); form.{{ $name }} = $event"
        @endif

        @if (!empty($attributes['hidden_year']))
        hidden-year
        @endif

        @if (!empty($attributes['min-date-dynamic']))
        :data-value-min="{{ $attributes['min-date-dynamic'] }}"
        @endif

        @if (!empty($attributes['change']))
        @change="{{ $attributes['change'] }}"
        @endif

        @if (isset($attributes['required']))
        :required="{{ ($attributes['required']) ? 'true' : 'false' }}"
        @endif

        @if (isset($attributes['readonly']))
        :readonly="{{ $attributes['readonly'] }}"
        @endif

        @if (isset($attributes['disabled']))
        :disabled="{{ $attributes['disabled'] }}"
        @endif

        @if (isset($attributes['show']))
        v-if="{{ $attributes['show'] }}"
        @endif

        @if (isset($attributes['v-error-message']))
        :form-error="{{ $attributes['v-error-message'] }}"
        @else
        :form-error="form.errors.get('{{ $name }}')"
        @endif
    ></akaunting-date>

@stack($name . '_input_end')
