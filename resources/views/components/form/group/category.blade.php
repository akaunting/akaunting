@if ((! $attributes->has('withoutRemote') && ! $attributes->has('without-remote')) && (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new')))
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
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"

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
                <span class="w-5 h-4 rounded-full" :style="{backgroundColor: option.option.color_hex_code}"></span>
                <span>@{{ option.option.name }}</span>
            </div>
        </template>
    </x-form.group.select>
@elseif (($attributes->has('withoutRemote') && $attributes->has('without-remote')) && (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new')))
    <x-form.group.select
        add-new
        path="{{ $path }}"

        name="{{ $name }}"
        label="{!! trans_choice('general.categories', 1) !!}"
        :options="$categories"
        :selected="$selected"
        sort-options="false"
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"

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
                <span class="w-5 h-4 rounded-full" :style="{backgroundColor: option.option.color_hex_code}"></span>
                <span>@{{ option.option.name }}</span>
            </div>
        </template>
    </x-form.group.select>
@elseif ((! $attributes->has('withoutRemote') && ! $attributes->has('without-remote')) && ($attributes->has('withoutAddNew') && $attributes->has('without-add-new')))
    <x-form.group.select
        remote
        remote_action="{{ $remoteAction }}"

        name="{{ $name }}"
        label="{!! trans_choice('general.categories', 1) !!}"
        :options="$categories"
        :selected="$selected"
        sort-options="false"
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"

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
                <span class="w-5 h-4 rounded-full" :style="{backgroundColor: option.option.color_hex_code}"></span>
                <span>@{{ option.option.name }}</span>
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
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"

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
                <span class="w-5 h-4 rounded-full":style="{backgroundColor: option.option.color_hex_code}"></span>
                <span>@{{ option.option.name }}</span>
            </div>
        </template>
    </x-form.group.select>
@endif
