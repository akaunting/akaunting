@foreach($fields as $field)
    @php $type = $field['type']; @endphp

    @if (($type == 'textGroup') || ($type == 'emailGroup') || ($type == 'passwordGroup'))
        {{ Form::$type('settings[' . $field['name'] . ']', $field['title'], $field['icon'], $field['attributes']) }}
    @elseif ($type == 'textareaGroup')
        {{ Form::$type('settings[' . $field['name'] . ']', $field['title']) }}
    @elseif ($type == 'selectGroup')
        {{ Form::$type('settings[' . $field['name'] . ']', $field['title'], $field['icon'], $field['values'], $field['selected'], $field['attributes']) }}
    @elseif ($type == 'radioGroup')
        {{ Form::$type('settings[' . $field['name'] . ']', $field['title'], 1, $field['enable'], $field['disable'], $field['attributes']) }}
    @elseif ($type == 'checkboxGroup')
        {{ Form::$type('settings[' . $field['name'] . ']', $field['title'], $field['items'], $field['value'], $field['id'], $field['attributes']) }}
    @endif
@endforeach
