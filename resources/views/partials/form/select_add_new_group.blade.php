@stack($name . '_input_start')

    <akaunting-select
        class="{{ $col }}{{ isset($attributes['required']) ? ' required' : '' }}{{ isset($attributes['disabled']) ? ' disabled' : '' }}"

        @if (!empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.has('{{ $name }}') }]"
        @endif

        icon="{{ $icon }}"
        title="{{ $text }}"
        placeholder="{{ trans('general.form.select.field', ['field' => $text]) }}"
        name="{{ $name }}"
        :options="{{ json_encode($values) }}"

        @if (isset($attributes['disabledOptions']))
        :disabled-options="{{ json_encode($attributes['disabledOptions']) }}"
        @endif

        @if (isset($attributes['dynamicOptions']))
        :dynamic-options="{{ $attributes['dynamicOptions'] }}"
        @endif

        @if (isset($selected) || old($name))
        value="{{ old($name, $selected) }}"
        @endif

        @if (!empty($attributes['model']))
        :model="{{ $attributes['model'] }}"
        @endif

        :add-new="{{ json_encode([
            'status' => true,
            'text' => trans('general.add_new'),
            'path' => isset($attributes['path']) ? $attributes['path']: false,
            'type' => isset($attributes['type']) ? $attributes['type'] : 'modal',
            'field' => [
                'key' => isset($attributes['field']['key']) ? $attributes['field']['key'] : 'id',
                'value' => isset($attributes['field']['value']) ? $attributes['field']['value'] : 'name'
            ],
            'new_text' => trans('modules.new'),
            'buttons' => [
                'cancel' => [
                    'text' => trans('general.cancel'),
                    'class' => 'btn-outline-secondary'
                ],
                'confirm' => [
                    'text' => trans('general.save'),
                    'class' => 'btn-success'
                ]
            ]
        ])}}"

        @if (!empty($attributes['v-model']))
        @interface="form.errors.clear('{{ $attributes['v-model'] }}'); {{ $attributes['v-model'] . ' = $event' }}"
        @elseif (!empty($attributes['data-field']))
        @interface="form.errors.clear('{{ 'form.' . $attributes['data-field'] . '.' . $name }}'); {{ 'form.' . $attributes['data-field'] . '.' . $name . ' = $event' }}"
        @else
        @interface="form.{{ $name }} = $event; form.errors.clear('{{ $name }}');"
        @endif

        @if (!empty($attributes['change']))
        @change="{{ $attributes['change'] }}($event)"
        @endif

        @if (!empty($attributes['visible-change']))
        @visible-change="{{ $attributes['visible-change'] }}"
        @endif

        @if (isset($attributes['readonly']))
        :readonly="{{ $attributes['readonly'] }}"
        @endif

        @if (isset($attributes['clearable']))
        :clearable="{{ $attributes['clearable'] }}"
        @else
        clearable
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

        no-data-text="{{ trans('general.no_data') }}"
        no-matching-data-text="{{ trans('general.no_matching_data') }}"
    ></akaunting-select>

@stack($name . '_input_end')
