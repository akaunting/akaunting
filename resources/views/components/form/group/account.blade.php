@if (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new'))
    <x-form.group.select
        add-new
        :path="$path"

        name="{{ $name }}"
        label="{{ trans_choice('general.accounts', 1) }}"
        :options="$accounts"
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"
        :selected="$selected"
        :required="$required"
        change="onChangeAccount"
        form-group-class="{{ $formGroupClass }}"
        option-style="height: 4rem;"
        {{ $attributes }}
    >
        <template #option="{option}">
            <span class="w-full flex h-16 items-center">
                <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
                    @{{ option.option.initials }}
                </div>
                <div class="flex flex-col text-black text-sm font-medium ml-2 sm:ml-4">
                    <span>@{{ option.option.title }}</span>
                    <span>@{{ option.option.number }}</span>
                </div>
            </span>
        </template>
    </x-form.group.select>
@else
    <x-form.group.select
        name="{{ $name }}"
        label="{{ trans_choice('general.accounts', 1) }}"
        :options="$accounts"
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"
        :selected="$selected"
        :required="$required"
        change="onChangeAccount"
        form-group-class="{{ $formGroupClass }}"
        option-style="height: 4rem;"
        {{ $attributes }}
    >
        <template #option="{option}">
            <span class="w-full flex h-16 items-center">
                <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
                    @{{ option.option.initials }}
                </div>
                <div class="flex flex-col text-black text-sm font-medium ml-2 sm:ml-4">
                    <span>@{{ option.option.title }}</span>
                    <span>@{{ option.option.number }}</span>
                </div>
            </span>
        </template>
    </x-form.group.select>
@endif
