@foreach($fields as $field)
    @php $type = $field['type']; @endphp

    @if (($type == 'textGroup') || ($type == 'emailGroup') || ($type == 'passwordGroup'))
        {{ Form::$type($field['name'], $field['title'], $field['icon'], array_merge([
                'data-field' => 'settings'
            ],
            $field['attributes'])
        ) }}
    @elseif ($type == 'textareaGroup')
        {{ Form::$type($field['name'], $field['title']) }}
    @elseif ($type == 'dateGroup')
        {{ Form::$type($field['name'], $field['title'], $field['icon'], array_merge([
               'model' => 'form.settings'.'.'.$field['name'],
               'show-date-format' => company_date_format(),
           ],
           $field['attributes'])
       ) }}
    @elseif ($type == 'selectGroup')
        {{ Form::$type($field['name'], $field['title'], $field['icon'], $field['values'], $field['selected'], array_merge([
                'data-field' => 'settings'
            ],
            $field['attributes'])
        ) }}
    @elseif ($type == 'radioGroup')
        {{ Form::$type($field['name'], $field['title'], $field['selected'] ?? true, $field['enable'], $field['disable'], array_merge([
                'data-field' => 'settings'
            ],
            $field['attributes'])
        ) }}
    @elseif ($type == 'checkboxGroup')
        {{ Form::$type($field['name'], $field['title'], $field['items'], $field['value'], $field['id'], $field['selected'], array_merge([
                'data-field' => 'settings'
            ],
            $field['attributes'])
        ) }}
    @endif
@endforeach
