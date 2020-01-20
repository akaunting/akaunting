@stack($name . '_input_start')

<akaunting-select
    class="{{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"

    @if (!empty($attributes['v-error']))
    :form-classes="[{'has-error': {{ $attributes['v-error'] }} }]"
    @else
    :form-classes="[{'has-error': form.errors.get('{{ $name }}') }]"
    @endif

    :icon="'{{ $icon }}'"
    :title="'{{ $text }}'"
    :placeholder="'{{ trans('general.form.select.field', ['field' => $text]) }}'"
    :name="'{{ $name }}'"
    :options="{{ json_encode($values) }}"
    :value="{{ json_encode(old($name, $selected)) }}"
    :multiple="true"
    :add-new="{{ json_encode([
        'status' => true,
        'text' => trans('general.form.add_new', ['field' => $text]),
        'path' => isset($attributes['path']) ? $attributes['path']: false,
        'type' => isset($attributes['type']) ? $attributes['type'] : 'modal',
        'field' => isset($attributes['field']) ? $attributes['field'] : 'name',
        'buttons' => [
            'cancel' => [
                'text' => trans('general.cancel'),
                'icon' => 'fas fa-times',
                'class' => 'btn-outline-secondary'
            ],
            'confirm' => [
                'text' => trans('general.save'),
                'icon' => 'fas fa-save',
                'class' => 'btn-success'
            ]
        ]
    ])}}"

    @if (!empty($attributes['collapse']))
    :collapse="true"
    @endif

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

    @if(isset($attributes['v-error-message']))
    :form-error="{{ $attributes['v-error-message'] }}"
    @else
    :form-error="form.errors.get('{{ $name }}')"
    @endif

    :no-data-text="'{{ trans('general.no_data') }}'"
    :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
></akaunting-select>

@stack($name . '_input_end')
