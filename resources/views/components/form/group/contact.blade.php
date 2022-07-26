@if ((! $attributes->has('withoutRemote') && ! $attributes->has('without-remote')) && (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new')))
    <x-form.group.select
        remote
        remote_action="{{ $remoteAction }}"

        add-new
        path="{{ $path }}"

        name="{{ $name }}"
        label="{!! $label !!}"
        :options="$contacts"
        :selected="$selected"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    />
@elseif (($attributes->has('withoutRemote') && $attributes->has('without-remote')) && (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new')))
    <x-form.group.select
        add-new
        path="{{ $path }}"

        name="{{ $name }}"
        label="{!! $label !!}"
        :options="$contacts"
        :selected="$selected"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    />
@elseif ((! $attributes->has('withoutRemote') && ! $attributes->has('without-remote')) && ($attributes->has('withoutAddNew') && $attributes->has('without-add-new')))
    <x-form.group.select
        remote
        remote_action="{{ $remoteAction }}

        add-new
        name="{{ $name }}"
        label="{!! $label !!}"
        :options="$contacts"
        :selected="$selected"

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
        add-new
        name="{{ $name }}"
        label="{!! $label !!}"
        :options="$contacts"
        :selected="$selected"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    />
@endif
