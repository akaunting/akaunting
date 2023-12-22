@stack($name . '_input_start')
    @if (! empty($remote))
    <akaunting-select-remote
    @else
    <akaunting-select
    @endif
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

        placeholder="{{ $placeholder }}"

        name="{{ $name }}"

        :options="{{ json_encode($options) }}"

        @if (isset($attributes['disabledOptions']))
        :disabled-options="{{ json_encode($attributes['disabledOptions']) }}"
        @endif

        @if (isset($attributes['dynamicOptions']))
        :dynamic-options="{{ $attributes['dynamicOptions'] }}"
        @endif

        @if (! empty($attributes['searchable']))
        searchable
        @elseif (! empty($searchable))
        searchable
        @endif

        @if (isset($attributes['fullOptions']) || isset($attributes['full-options']))
        :full-options="{{ json_encode(! empty($attributes['fullOptions']) ? $attributes['fullOptions'] : $attributes['full-options']) }}"
        @else
        :full-options="{{ json_encode($fullOptions) }}"
        @endif

        @if (isset($attributes['searchText']) || isset($attributes['search-text']))
        search-text="{{ ! empty($attributes['searchText']) ? $attributes['searchText'] : $attributes['search-text'] }}"
        @else
        search-text="{{ $searchText }}"
        @endif

        @if (! empty($attributes['force-dynamic-option-value']))
        force-dynamic-option-value
        @elseif (! empty($forceDynamicOptionValue))
        force-dynamic-option-value
        @endif

        @if (empty($multiple))
            @if (isset($selected) || old($name))
            value="{{ old($name, $selected) }}"
            @endif
        @else
            @if (isset($selected) || old($name))
            :value="{{ json_encode(old($name, $selected)) }}"
            @endif

            multiple

            @if (! empty($attributes['collapse']))
            collapse
            @endif
        @endif

        @if (! empty($attributes['model']))
        :model="{{ $attributes['model'] }}"
        @endif

        @if (! empty($addNew))
        :add-new="{{ json_encode([
            'status' => true,
            'text' => isset($attributes['add-new-text']) ? $attributes['add-new-text'] : trans('general.title.new', ['type' => $label ?? '']),
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
                    'class' => 'disabled:bg-green-100'
                ]
            ]
        ])}}"
        @endif

        @if (! empty($group))
        group
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

        @if (! empty($attributes['focus']))
        @focus="{{ $attributes['focus'] }}"
        @endif

        @if (! empty($attributes['visible-change']))
        @visible-change="{{ $attributes['visible-change'] }}"
        @endif

        @if (! empty($attributes['clear']))
        @clear="{{ $attributes['clear'] }}($event)"
        @endif

        @if (isset($attributes['readonly']))
        :readonly="{{ $attributes['readonly'] }}"
        @endif

        @if (isset($attributes['clearable']))
        :clearable="{{ $attributes['clearable'] }}"
        @else
        clearable
        @endif

        @if (isset($attributes['no-arrow']))
        :no-arrow="{{ $attributes['no-arrow'] }}"
        @endif

        @if (!$required)
        :not-required={{ $required ? 'false' : 'true' }}
        @endif

        @if (isset($attributes['v-disabled']))
        :disabled="{{ $attributes['v-disabled'] }}"
        @endif

        @if (isset($attributes['v-error-message']))
        :form-error="{{ $attributes['v-error-message'] }}"
        @else
        :form-error="form.errors.get('{{ $name }}')"
        @endif

        @if (! empty($remote))
        remote-action="{{ $attributes['remote_action'] }}"

        @if (! empty($attributes['currency_code']))
        currency-code="{{ $attributes['currency_code'] }}"
        @endif
        @endif

        loading-text="{{ trans('general.loading') }}"
        no-data-text="{{ trans('general.no_data') }}"
        no-matching-data-text="{{ trans('general.no_matching_data') }}"

        @if (isset($attributes['sort-options']))
        :sort-options="{{ $attributes['sort-options'] }}"
        @endif

        @if (isset($attributes['option-style']))
        option-style="{{ $attributes['option-style'] }}"
        @endif

        @if (isset($attributes['option_field']))
        :option_field="{{ json_encode($attributes['option_field']) }}"
        @endif

        @if (isset($attributes['@index']))
        @index="{{ $attributes['@index'] }}"
        @endif

        @if (isset($attributes['@value']))
        @value="{{ $attributes['@value'] }}"
        @endif

        @if (isset($attributes['@option']))
        @option="{{ $attributes['@option'] }}"
        @endif

        @if (isset($attributes['@label']))
        @label="{{ $attributes['@label'] }}"
        @endif
    >
        {!! $slot ?? "" !!}
    @if (! empty($remote))
    </akaunting-select-remote>
    @else
    </akaunting-select>
    @endif

@stack($name . '_input_end')
