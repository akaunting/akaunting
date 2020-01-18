@stack($name . '_input_start')

<akaunting-select
    class="{{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
    :form-classes="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }]"
    :title="'{{ $text }}'"
    :placeholder="'{{ trans('general.form.select.field', ['field' => $text]) }}'"
    :name="'{{ $name }}'"
    :options="{{ json_encode($values) }}"
    :value="{{ json_encode(old($name, $selected)) }}"
    :icon="'{{ $icon }}'"
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
    @interface="{{ !empty($attributes['v-model']) ? $attributes['v-model'] . ' = $event' : 'form.' . $name . ' = $event' }}"
    @if (!empty($attributes['change']))
    @change="{{ $attributes['change'] }}($event)"
    @endif
    @if(isset($attributes['v-error-message']))
    :form-error="{{ $attributes['v-error-message'] }}"
    @else
    :form-error="form.errors.get('{{ $name }}')"
    @endif
    :no-data="'{{ trans('general.no_data') }}'"
    :no-matching-data="'{{ trans('general.no_matching_data') }}'"
></akaunting-select>

@stack($name . '_input_end')
