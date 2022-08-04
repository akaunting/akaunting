<x-form id="form-create-category" route="categories.store">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.text name="name" label="{{ trans('general.name') }}" form-group-class="col-span-6" />

        <x-form.group.color name="color" label="{{ trans('general.color') }}" form-group-class="col-span-6" />

        <x-form.group.select name="parent_id" label="{{ trans('general.parent') . ' ' . trans_choice('general.categories', 1) }}" :options="$categories" not-required sort-options="false" searchable form-group-class="col-span-6" />

        <x-form.input.hidden name="type" value="{{ $type }}" />
        <x-form.input.hidden name="enabled" value="1" />
    </div>
</x-form>
