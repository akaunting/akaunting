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
        label="{!! $label !!}"
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
            <div class="flex items-center{{ $group ? ' justify-between w-full' : '' }}">
                <div class="{{ (! $group) ? 'ltr:ml-2 ltr:mr-2 rtl:mr-2 rtl:ml-2 ' : '' }}w-4 h-4 rounded-full" :style="{backgroundColor: option.option ? option.option.color_hex_code : ''}"></div>

                @if ($option_field['value'] == 'title')
                <div class="{{ $group ? 'flex-1 ltr:ml-2 rtl:mr-2' : '' }}">@{{ option.option && option.option.title ? option.option.title : option.value }}</div>
                @else
                <div class="{{ $group ? 'flex-1 ltr:ml-2 rtl:mr-2' : '' }}">@{{ option.option && option.option.name ? option.option.name : option.value }}</div>
                @endif

                @if ($group)
                <div class="text-xs text-gray-400 shrink-0 ltr:ml-auto rtl:mr-auto">@{{ option.option && option.option.group ? option.option.group : '' }}</div>
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
        label="{!! $label !!}"
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
            <div class="flex items-center{{ $group ? ' justify-between w-full' : '' }}">
                <div class="{{ (! $group) ? 'ltr:ml-2 ltr:mr-2 rtl:mr-2 rtl:ml-2 ' : '' }}w-4 h-4 rounded-full" :style="{backgroundColor: option.option ? option.option.color_hex_code : ''}"></div>

                @if ($option_field['value'] == 'title')
                <div class="{{ $group ? 'flex-1 ltr:ml-2 rtl:mr-2' : '' }}">@{{ option.option && option.option.title ? option.option.title : option.value }}</div>
                @else
                <div class="{{ $group ? 'flex-1 ltr:ml-2 rtl:mr-2' : '' }}">@{{ option.option && option.option.name ? option.option.name : option.value }}</div>
                @endif

                @if ($group)
                <div class="text-xs text-gray-400 shrink-0 ltr:ml-auto rtl:mr-auto">@{{ option.option && option.option.group ? option.option.group : '' }}</div>
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
        label="{!! $label !!}"
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
            <div class="flex items-center{{ $group ? ' justify-between w-full' : '' }}">
                <div class="{{ (! $group) ? 'ltr:ml-2 ltr:mr-2 rtl:mr-2 rtl:ml-2 ' : '' }}w-4 h-4 rounded-full" :style="{backgroundColor: option.option ? option.option.color_hex_code : ''}"></div>

                @if ($option_field['value'] == 'title')
                <div class="{{ $group ? 'flex-1 ltr:ml-2 rtl:mr-2' : '' }}">@{{ option.option && option.option.title ? option.option.title : option.value }}</div>
                @else
                <div class="{{ $group ? 'flex-1 ltr:ml-2 rtl:mr-2' : '' }}">@{{ option.option && option.option.name ? option.option.name : option.value }}</div>
                @endif

                @if ($group)
                <div class="text-xs text-gray-400 shrink-0 ltr:ml-auto rtl:mr-auto">@{{ option.option && option.option.group ? option.option.group : '' }}</div>
                @endif
            </div>
        </template>
    </x-form.group.select>
@else
    <x-form.group.select
        name="{{ $name }}"
        label="{!! $label !!}"
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
            <div class="flex items-center{{ $group ? ' justify-between w-full' : '' }}">
                <div class="{{ (! $group) ? 'ltr:ml-2 ltr:mr-2 rtl:mr-2 rtl:ml-2 ' : '' }}w-4 h-4 rounded-full" :style="{backgroundColor: option.option ? option.option.color_hex_code : ''}"></div>

                @if ($option_field['value'] == 'title')
                <div class="{{ $group ? 'flex-1 ltr:ml-2 rtl:mr-2' : '' }}">@{{ option.option && option.option.title ? option.option.title : option.value }}</div>
                @else
                <div class="{{ $group ? 'flex-1 ltr:ml-2 rtl:mr-2' : '' }}">@{{ option.option && option.option.name ? option.option.name : option.value }}</div>
                @endif

                @if ($group)
                <div class="text-xs text-gray-400 shrink-0 ltr:ml-auto rtl:mr-auto">@{{ option.option && option.option.group ? option.option.group : '' }}</div>
                @endif
            </div>
        </template>
    </x-form.group.select>
@endif
