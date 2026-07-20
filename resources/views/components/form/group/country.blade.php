@if ($disabled)
    <input type="hidden" name="country" value="{{ $selected }}" />
@endif

<x-form.group.select
    name="country"
    label="{!! trans_choice('general.countries', 1) !!}"
    :options="trans('countries')"
    :selected="$selected"
    required="{{ $required }}"
    not-required="{{ $notRequired }}"
    disabled="{{ $disabled }}"
    model="form.country"
    form-group-class="{{ $formGroupClass }}"
/>
