<div class="sm:col-span-6 mb-8">
    <x-form.group.textarea
        name="notes"
        label="{{ trans_choice('general.notes', 2) }}"
        :value="$notes"
        not-required
        class="form-element border-0 bg-transparent px-0 rounded-none resize-none"
        form-label-class="lg:text-lg"
        form-group-class="border-b pb-2 mb-3.5"
        rows="1"
    />
</div>
