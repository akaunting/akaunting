@stack($name . '_input_start')
<div
    @class([
        'grid sm:grid-cols-6 lg:gap-8 col-span-3',
        $formGroupClass,
        'required' => $required,
        'readonly' => $readonly,
        'disabled' => $disabled,
    ])

    @if (isset($attributes['v-show']))
    v-if="{{ $attributes['v-show'] }}"
    @endif

    :class="[
        {'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }
    ]"
>
    <akaunting-select
        @class([
            'relative',
            $formGroupClass,
            'required' => $required,
            'readonly' => $readonly,
            'disabled' => $disabled,
        ])

        ref="{{ $name }}"

        id="form-invoice-{{ $name }}"

        @if (isset($attributes['v-show']))
        v-if="{{ $attributes['v-show'] }}"
        @endif

        @if (!empty($attributes['v-error']))
        :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
        @else
        :form-classes="[{'has-error': form.errors.has('{{ $name }}') }]"
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

        :options="{{ json_encode($options) }}"

        @if (isset($attributes['disabledOptions']))
        :disabled-options="{{ json_encode($attributes['disabledOptions']) }}"
        @endif

        @if (isset($attributes['dynamicOptions']))
        :dynamic-options="{{ $attributes['dynamicOptions'] }}"
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

        @if (! empty($attributes['visible-change']))
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

        @if (isset($attributes['v-disabled']))
        :disabled="{{ $attributes['v-disabled'] }}"
        @endif

        @if (!$required)
        :not-required={{ $required ? 'false' : 'true' }}
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

        :sort-options="false"
    ></akaunting-select>

    @foreach ($options as $option)
        @if ($option->id == 'custom')
            <div class="col-span-3" v-if="{{ $attributes['input-name'] }}">
                <div class="h-6"></div>
                <x-form.group.text name="{{ $attributes['input-name'] }}" value="{{ $attributes['input-value'] }}" />
            </div>
        @endif
    @endforeach
</div>
@stack($name . '_input_end')