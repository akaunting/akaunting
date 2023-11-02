<x-form.group.contact
    type="customer"
    option-style="height: 4rem;"
    {{ $attributes }}
>
    <x-slot name="option">
        <template #option="{option}">
            <span class="w-full flex h-16 items-center">
                <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
                    @{{ option.option.initials }}
                </div>
                <div class="flex flex-col text-black text-sm font-medium ml-2 sm:ml-4">
                    <span>@{{ option.option.name }}</span>
                    <span>@{{ option.option.email }}</span>
                </div>
            </span>
        </template>
    </x-slot>
</x-form.group.contact>
