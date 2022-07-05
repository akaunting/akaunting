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
        </div>
    </x-slot>
</x-form.accordion>
