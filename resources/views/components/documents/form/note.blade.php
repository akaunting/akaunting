<div class="sm:col-span-6 mb-8">
    <x-form.group.textarea
        name="notes"
        label="{{ trans_choice('general.notes', 2) }}"
        :value="$notes"
        not-required
        override="w-full text-sm px-0 py-2.5 mt-1 border-light-gray text-black placeholder-light-gray disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple border-0 bg-transparent rounded-none resize-none"
        form-label-class="lg:text-lg"
        form-group-class="border-b pb-2 mb-3.5"
        rows="1"
        textarea-auto-height
    />
</div>
