@stack($name . '_input_start')

    <akaunting-date
        class="{{ $col }}{{ isset($attributes['required']) ? ' required' : '' }}"

        @if (!empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.get('{{ $name }}') }]"
        @endif

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

        :config="{
            allowInput: true,
            wrap: true,
            enableTime: true,
            @if (!empty($attributes['seconds']))
            enableSeconds: true,
            @endif
            noCalendar: true
        }"

        @if (!empty($attributes['v-model']))
        @interface="{{ $attributes['v-model'] . ' = $event' }}"
        @elseif (!empty($attributes['data-field']))
        @interface="{{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.{{ $name }} = $event"
        @endif

        @if (isset($attributes['readonly']))
        :readonly="'{{ $attributes['readonly'] }}'"
        @endif

        @if (isset($attributes['disabled']))
        :disabled="'{{ $attributes['disabled'] }}'"
        @endif

        @if(isset($attributes['v-error-message']))
        :form-error="{{ $attributes['v-error-message'] }}"
        @else
        :form-error="form.errors.get('{{ $name }}')"
        @endif
    ></akaunting-date>

@stack($name . '_input_end')
