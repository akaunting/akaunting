@stack($name . '_input_start')

    <akaunting-select
        class="{{ $col }}{{ isset($attributes['required']) ? ' required' : '' }}{{ isset($attributes['disabled']) ? ' disabled' : '' }}"

        @if (!empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.get('{{ $name }}') }]"
        @endif

        icon="{{ $icon }}"
        title="{{ $text }}"
        placeholder="{{ trans('general.form.select.field', ['field' => $text]) }}"
        name="{{ $name }}"
        :options="{{ json_encode($values) }}"

        @if(old($name, $selected))
        value="{{ old($name, $selected) }}"
        @endif

        @if (!empty($attributes['model']))
        :model="{{ $attributes['model'] }}"
        @endif

        :group="true"

        @if (!empty($attributes['v-model']))
        @interface="{{ $attributes['v-model'] . ' = $event' }}"
        @elseif (!empty($attributes['data-field']))
        @interface="{{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.{{ $name }} = $event"
        @endif

        @if (!empty($attributes['change']))
        @change="{{ $attributes['change'] }}($event)"
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

        no-data-text="{{ trans('general.no_data') }}"
        no-matching-data-text="{{ trans('general.no_matching_data') }}"
    ></akaunting-select>

@stack($name . '_input_end')
