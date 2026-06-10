@if (
    (! $attributes->has('withoutRemote') && ! $attributes->has('without-remote'))
    && (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new'))
)
    <x-form.group.select
        remote
        remote_action="{{ $remoteAction }}"

        add-new
        path="{{ $path }}"

        name="{{ $name }}"
        label="{!! trans_choice('general.categories', 1) !!}"
        :options="$categories"
        :selected="$selected"
        sort-options="false"
        :option_field="$option_field"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    >
        <template #option="{option}">
            <div class="flex items-center">
                <span class="{{ (! $group) ? 'ltr:ml-2 rtl:mr-2 ' : '' }}w-5 h-4 rounded-full" :style="{backgroundColor: option.option.color_hex_code}"></span>

                @if ($option_field['value'] == 'title')
                <span>@{{ option.option.title }}</span>
                @else
                <span>@{{ option.option.name }}</span>
                @endif
            </div>
        </template>
    </x-form.group.select>
@elseif (
    ($attributes->has('withoutRemote') || $attributes->has('without-remote'))
    && (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new'))
)
    <x-form.group.select
        add-new
        path="{{ $path }}"

        name="{{ $name }}"
        label="{!! trans_choice('general.categories', 1) !!}"
        :options="$categories"
        :selected="$selected"
        sort-options="false"
        :option_field="$option_field"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    >
        <template #option="{option}">
            <div class="flex items-center">
                <span class="{{ (! $group) ? 'ltr:ml-2 rtl:mr-2 ' : '' }}w-5 h-4 rounded-full" :style="{backgroundColor: option.option.color_hex_code}"></span>

                @if ($option_field['value'] == 'title')
                <span>@{{ option.option.title }}</span>
                @else
                <span>@{{ option.option.name }}</span>
                @endif
            </div>
        </template>
    </x-form.group.select>
@elseif (
    (! $attributes->has('withoutRemote') && ! $attributes->has('without-remote'))
    && ($attributes->has('withoutAddNew') || $attributes->has('without-add-new'))
)
    <x-form.group.select
        remote
        remote_action="{{ $remoteAction }}"

        name="{{ $name }}"
        label="{!! trans_choice('general.categories', 1) !!}"
        :options="$categories"
        :selected="$selected"
        sort-options="false"
        :option_field="$option_field"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    >
        <template #option="{option}">
            <div class="flex items-center">
                <span class="{{ (! $group) ? 'ltr:ml-2 rtl:mr-2 ' : '' }}w-5 h-4 rounded-full" :style="{backgroundColor: option.option.color_hex_code}"></span>

                @if ($option_field['value'] == 'title')
                <span>@{{ option.option.title }}</span>
                @else
                <span>@{{ option.option.name }}</span>
                @endif
            </div>
        </template>
    </x-form.group.select>
@else
    <x-form.group.select
        name="{{ $name }}"
        label="{!! trans_choice('general.categories', 1) !!}"
        :options="$categories"
        :selected="$selected"
        sort-options="false"
        :option_field="$option_field"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    >
        <template #option="{option}">
            <div class="flex items-center">
                <span class="{{ (! $group) ? 'ltr:ml-2 rtl:mr-2 ' : '' }}w-5 h-4 rounded-full" :style="{backgroundColor: option.option.color_hex_code}"></span>

                @if ($option_field['value'] == 'title')
                <span>@{{ option.option.title }}</span>
                @else
                <span>@{{ option.option.name }}</span>
                @endif
            </div>
        </template>
    </x-form.group.select>
@endif
