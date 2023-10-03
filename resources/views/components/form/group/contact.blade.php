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
        option-style="height: 4rem;"

        {{ $attributes }}
    >
        @if (! empty($option))
            <x-slot name="option">
                {!! $option !!}
            </x-slot>
        @else
            <template #option="{option}">
                <span class="w-full flex h-16 items-center">
                    <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
                        <span class="w-12 h-12 flex items-center justify-center text-2xl font-regular p-6" v-if="! option.option.logo">
                            @{{ option.option.initials }}
                        </span>

                        <span class="w-12 h-12 flex items-center justify-center text-2xl font-regular p-6" v-else>
                            <img v-if="option.option.logo"
                                :src="'{{ url("/" . company_id()) }}/uploads/' + option.option.logo.id"
                                class="absolute w-12 h-12 rounded-full hidden lg:block"
                                :alt="option.option.name"
                                :title="option.option.name"
                            >
                            <img v-else
                                :src="'{{ url("/") }}' + '/public/img/user.svg'"
                                class="absolute w-12 h-12 rounded-full hidden lg:block"
                                :alt="option.option.name"
                            />
                        </span>
                    </div>

                    <div class="flex flex-col text-black text-sm font-medium ml-2 sm:ml-4">
                        <span>@{{ option.option.name }}</span>
                        <span>@{{ option.option.email }}</span>
                    </div>
                </span>
            </template>
        @endif
    </x-form.group.select>
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
        option-style="height: 4rem;"

        {{ $attributes }}
    >
        @if (! empty($option))
            <x-slot name="option">
                {!! $option !!}
            </x-slot>
        @else
            <template #option="{option}">
                <span class="w-full flex h-16 items-center">
                    <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
                        <span class="w-12 h-12 flex items-center justify-center text-2xl font-regular p-6" v-if="! option.option.logo">
                            @{{ option.option.initials }}
                        </span>

                        <span class="w-12 h-12 flex items-center justify-center text-2xl font-regular p-6" v-else>
                            <img v-if="option.option.logo"
                                :src="'{{ url("/" . company_id()) }}/uploads/' + option.option.logo.id"
                                class="absolute w-12 h-12 rounded-full hidden lg:block"
                                :alt="option.option.name"
                                :title="option.option.name"
                            >
                            <img v-else
                                :src="'{{ url("/") }}' + '/public/img/user.svg'"
                                class="absolute w-12 h-12 rounded-full hidden lg:block"
                                :alt="option.option.name"
                            />
                        </span>
                    </div>

                    <div class="flex flex-col text-black text-sm font-medium ml-2 sm:ml-4">
                        <span>@{{ option.option.name }}</span>
                        <span>@{{ option.option.email }}</span>
                    </div>
                </span>
            </template>
        @endif
    </x-form.group.select>
@elseif ((! $attributes->has('withoutRemote') && ! $attributes->has('without-remote')) && ($attributes->has('withoutAddNew') && $attributes->has('without-add-new')))
    <x-form.group.select
        remote
        remote_action="{{ $remoteAction }}"

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
        option-style="height: 4rem;"

        {{ $attributes }}
    >
        @if (! empty($option))
        <x-slot name="option">
            {!! $option !!}
        </x-slot>
        @else
            <template #option="{option}">
                <span class="w-full flex h-16 items-center">
                    <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
                        <span class="w-12 h-12 flex items-center justify-center text-2xl font-regular p-6" v-if="! option.option.logo">
                            @{{ option.option.initials }}
                        </span>

                        <span class="w-12 h-12 flex items-center justify-center text-2xl font-regular p-6" v-else>
                            <img v-if="option.option.logo"
                                :src="'{{ url("/" . company_id()) }}/uploads/' + option.option.logo.id"
                                class="absolute w-12 h-12 rounded-full hidden lg:block"
                                :alt="option.option.name"
                                :title="option.option.name"
                            >
                            <img v-else
                                :src="'{{ url("/") }}' + '/public/img/user.svg'"
                                class="absolute w-12 h-12 rounded-full hidden lg:block"
                                :alt="option.option.name"
                            />
                        </span>
                    </div>

                    <div class="flex flex-col text-black text-sm font-medium ml-2 sm:ml-4">
                        <span>@{{ option.option.name }}</span>
                        <span>@{{ option.option.email }}</span>
                    </div>
                </span>
            </template>
        @endif
    </x-form.group.select>
@else
    <x-form.group.select
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
        option-style="height: 4rem;"

        {{ $attributes }}
    >
        @if (! empty($option))
        <x-slot name="option">
            {!! $option !!}
        </x-slot>
        @else
            <template #option="{option}">
                <span class="w-full flex h-16 items-center">
                    <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
                        <span class="w-12 h-12 flex items-center justify-center text-2xl font-regular p-6" v-if="! option.option.logo">
                            @{{ option.option.initials }}
                        </span>

                        <span class="w-12 h-12 flex items-center justify-center text-2xl font-regular p-6" v-else>
                            <img v-if="option.option.logo"
                                :src="'{{ url("/" . company_id()) }}/uploads/' + option.option.logo.id"
                                class="absolute w-12 h-12 rounded-full hidden lg:block"
                                :alt="option.option.name"
                                :title="option.option.name"
                            >
                            <img v-else
                                :src="'{{ url("/") }}' + '/public/img/user.svg'"
                                class="absolute w-12 h-12 rounded-full hidden lg:block"
                                :alt="option.option.name"
                            />
                        </span>
                    </div>

                    <div class="flex flex-col text-black text-sm font-medium ml-2 sm:ml-4">
                        <span>@{{ option.option.name }}</span>
                        <span>@{{ option.option.email }}</span>
                    </div>
                </span>
            </template>
        @endif
    </x-form.group.select>
@endif
