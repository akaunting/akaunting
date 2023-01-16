<x-form.group.select
    name="payment_method"
    label="{!! trans_choice('general.payment_methods', 1) !!}"
    :options="$payment_methods"
    :selected="$selected"

    form-group-class="{{ $formGroupClass }}"
    :required="$required"
    :readonly="$readonly"

    {{ $attributes }}
/>
