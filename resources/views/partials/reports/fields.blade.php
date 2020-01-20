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
    @elseif ($type == 'selectGroup')
        {{ Form::$type($field['name'], $field['title'], $field['icon'], $field['values'], $field['selected'], array_merge([
                'data-field' => 'settings'
            ],
            $field['attributes'])
        ) }}
    @elseif ($type == 'radioGroup')
        {{ Form::$type($field['name'], $field['title'], 1, $field['enable'], $field['disable'], array_merge([
                'data-field' => 'settings'
            ],
            $field['attributes'])
        ) }}
    @elseif ($type == 'checkboxGroup')
        {{ Form::$type($field['name'], $field['title'], $field['items'], $field['value'], $field['id'], array_merge([
                'data-field' => 'settings'
            ],
            $field['attributes'])
        ) }}
    @endif
@endforeach
