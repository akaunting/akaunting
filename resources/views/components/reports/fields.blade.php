@foreach($fields as $field)
    @php $type = $field['type']; @endphp

    @switch($type)
        @case('text')
        @case('textGroup')
            <x-form.group.text
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                data-field="settings"
                :dynamic-attributes="$field['attributes']"
            />
            @break

        @case('email')
        @case('emailGroup')
            <x-form.group.email
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                data-field="settings"
                :dynamic-attributes="$field['attributes']"
            />
            @break

        @case('password')
        @case('passwordGroup')
            <x-form.group.email
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                data-field="settings"
                :dynamic-attributes="$field['attributes']"
            />
            @break

        @case('textarea')
        @case('textareaGroup')
            <x-form.group.textarea
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                data-field="settings"
                :dynamic-attributes="$field['attributes']"
            />
            @break

        @case('date')
        @case('dateGroup')
            <x-form.group.date
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                data-field="settings"
                :dynamic-attributes="array_merge([
                    'model' => 'form.settings'.'.'.$field['name'],
                    'show-date-format' => company_date_format(),
                ], $field['attributes'])"

            />
            @break

        @case('select')
        @case('selectGroup')
            <x-form.group.select
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                :options="$field['values']"
                sort-options="false"
                :selected="$field['selected']"
                data-field="settings"
                :dynamic-attributes="$field['attributes']"
            />
            @break

        @case('radio')
        @case('radioGroup')
            <x-form.group.radio
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                :dynamic-attributes="array_merge([
                    'data-field' => 'settings'
                ], $field['attributes'])"
            />
            @break

        @case('checkbox')
        @case('checkboxGroup')
            <x-form.group.checkbox
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                :dynamic-attributes="array_merge([
                    'data-field' => 'settings'
                ], $field['attributes'])"
            />
            @break

        @default
            <x-form.group.text
                name="{{ $field['name'] }}"
                label="{{ $field['title'] }}"
                :dynamic-attributes="array_merge([
                    'data-field' => 'settings'
                ], $field['attributes'])"
            />
    @endswitch
@endforeach
