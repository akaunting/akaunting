<x-form.group.select
    name="country"
    label="{!! trans_choice('general.countries', 1) !!}"
    :options="trans('countries')"
    :selected="setting('company.country')"
    not-required
    model="form.country"
    form-group-class="{{ $formGroupClass }}"
/>
