@if (! $attributes->has('withoutAddNew'))
    <x-form.group.select
        add-new
        :path="$path"

        name="{{ $name }}"
        label="{{ trans_choice('general.accounts', 1) }}"
        :options="$accounts"
        :selected="$selected"
        change="onChangeAccount"
        form-group-class="{{ $formGroupClass }}"
        {{ $attributes }}
    />
@else
    <x-form.group.select
        name="{{ $name }}"
        label="{{ trans_choice('general.accounts', 1) }}"
        :options="$accounts"
        :selected="$selected"
        change="onChangeAccount"
        form-group-class="{{ $formGroupClass }}"
        {{ $attributes }}
    />
@endif
