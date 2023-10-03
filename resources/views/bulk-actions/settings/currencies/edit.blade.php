<div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
    <x-form.group.text
        name="rate"
        label="{{ trans('currencies.rate') }}"
        @input="onChangeRate"
        ::disabled="form.default_currency == 1"
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.select
        name="precision"
        label="{{ trans('currencies.precision') }}"
        :options="$precisions"
        model="form.precision"
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.text
        name="decimal_mark"
        label="{{ trans('currencies.decimal_mark') }}"
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.text
        name="thousands_separator"
        label="{{ trans('currencies.thousands_separator') }}"
        not-required
        form-group-class="sm:col-span-6"
    />
</div>
