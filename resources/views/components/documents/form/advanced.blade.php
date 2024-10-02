<x-form.accordion type="advanced">
    <x-slot name="head">
        <x-form.accordion.head
            title="{{ trans_choice($textSectionAdvancedTitle, 1) }}"
            description="{{ trans($textSectionAdvancedDescription, ['type' => $type]) }}"
        />
    </x-slot>

    <x-slot name="body">
        @stack('footer_start')

        @if (! $hideFooter)
            <div class="{{ $classFooter }}">
                <x-form.group.textarea name="footer" label="{{ trans('general.footer') }}" class="h-full" :value="$footer" not-required rows="7" />
            </div>
        @endif

        <div class="sm:col-span-4 grid gap-x-8 gap-y-1">
            @stack('category_start')

            @if (! $hideCategory)
                <div class="{{ $classCategory }}">
                    <x-form.group.category :type="$typeCategory" :selected="$categoryId" />
                </div>
            @else
                <x-form.input.hidden name="category_id" :value="$categoryId" />
            @endif

            @stack('attachment_end')

            @if (! $hideAttachment)
                <div class="{{ $classAttachment }}">
                    <x-form.group.attachment />
                </div>
            @endif

            @if (! $hideTemplate)
                <x-form.group.select
                    name="template"
                    label="{{ trans_choice('general.templates', 1) }}"
                    :options="$templates"
                    :selected="$template"
                >
                    <template #option="{option}">
                        <span class="w-full flex h-16 items-center">
                            <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
                                <img src="@{{ option.option.image }}" class="h-60 my-3" alt="Classic" />
                            </div>
                            
                            <div class="flex flex-col text-black text-sm font-medium ml-2 sm:ml-4">
                                <span>@{{ option.option.name }}</span>
                            </div>
                        </span>
                    </template>
                </x-form.group.select>
            @endif

            @if (! $hideBackgroundColor)
                <x-form.group.color name="color" label="{{ trans('general.color') }}" :value="$backgroundColor" />
            @endif
        </div>
    </x-slot>
</x-form.accordion>
