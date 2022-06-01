
@if (! $attributes->has('withoutAddNew'))
    <x-form.group.select
        add-new
        :path="$path"
        :field="$field"
        name="{{ $name }}"
        label="{{ trans_choice('general.currencies', 1) }}"
        :options="$currencies"
        :selected="$selected"
        change="onChangeCurrency"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    />
@else
    <x-form.group.select
        name="{{ $name }}"
        label="{{ trans_choice('general.currencies', 1) }}"
        :options="$currencies"
        :selected="$selected"
        change="onChangeCurrency"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    />
@endif
