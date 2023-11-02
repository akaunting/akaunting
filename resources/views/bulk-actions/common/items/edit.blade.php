<div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
    <x-form.group.radio
        name="type"
        label="{{ trans_choice('general.types', 1) }}"
        :options="[
            'product' => trans_choice('general.products', 1),
            'service' => trans_choice('general.services', 1)
        ]"
        not-required
        form-group-class="sm:col-span-6"
        input-group-class="grid grid-cols-2 gap-3 sm:grid-cols-2"
    />

    <x-form.group.category
        type="item"
        selected=""
        without-add-new
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.tax
        name="tax_ids"
        :selected="[]"
        multiple
        without-add-new
        not-required
        form-group-class="sm:col-span-6"
    />
</div>
