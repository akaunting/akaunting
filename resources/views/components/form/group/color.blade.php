@stack($name . '_input_start')
    <akaunting-color
        @class([
            'relative',
            $formGroupClass,
            'required' => $required,
            'readonly' => $readonly,
            'disabled' => $disabled,
        ])

        id="form-select-{{ $name }}"

        @if (isset($attributes['v-show']))
        v-if="{{ $attributes['v-show'] }}"
        @endif

        @if (! empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.get('{{ $name }}') }]"
        @endif

        @if (! $attributes->has('icon') && ! empty($icon->contents))
            {!! $icon ?? '' !!}
        @elseif (! empty($icon))
            <x-form.icon icon="{{ $icon }}" />
        @endif

        title="{!! $label !!}"

        @if (isset($attributes['placeholder']))
        placeholder="{{ $attributes['placeholder'] }}"
        @else
        placeholder="{{ trans('general.form.select.field', ['field' => $label]) }}"
        @endif

        name="{{ $name }}"

        value="{{ $value }}"

        @if (! empty($attributes['model']))
        :model="{{ $attributes['model'] }}"
        @endif

        @if (! empty($attributes['small']))
        small="{{ $attributes['small'] }}"
        @endif

        @if (!$required)
        :not-required={{ $required ? 'false' : 'true' }}
        @endif

        @if (! empty($attributes['v-model']))
        @interface="form.errors.clear('{{ $attributes['v-model'] }}'); {{ $attributes['v-model'] . ' = $event' }}"
        @elseif (! empty($attributes['data-field']))
        @interface="form.errors.clear('{{ 'form.' . $attributes['data-field'] . '.' . $name }}'); {{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.errors.clear('{{ $name }}'); form.{{ $name }} = $event;"
        @endif

        @if (! empty($attributes['change']))
        @change="{{ $attributes['change'] }}($event)"
        @endif

        @if (isset($attributes['readonly']))
        :readonly="{{ $attributes['readonly'] }}"
        @endif

        @if (isset($attributes['disabled']))
        :disabled="{{ $attributes['disabled'] }}"
        @endif

        @if (isset($attributes['v-error-message']))
        :form-error="{{ $attributes['v-error-message'] }}"
        @else
        :form-error="form.errors.get('{{ $name }}')"
        @endif
    ></akaunting-color>
@stack($name . '_input_end')
