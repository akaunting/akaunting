<div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
    <x-form.group.select
        name="type"
        label="{{ trans_choice('general.types', 1) }}"
        :options="$types"
        selected=""
        change="updateParentCategories"
        not-required
        form-group-class="sm:col-span-6"
    />
</div>
