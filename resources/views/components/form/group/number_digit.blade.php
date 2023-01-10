<x-form.group.select
    :name="$name"
    :label="$label"
    :options="$number_digits"
    :selected="$selected"

    form-group-class="{{ $formGroupClass }}"
    :required="$required"
    :readonly="$readonly"
    sort-options="false"

    {{ $attributes }}
/>
