<x-form id="form-create-category" route="categories.store">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.text name="name" label="{{ trans('general.name') }}" form-group-class="col-span-6" />

        <x-form.group.color name="color" label="{{ trans('general.color') }}" form-group-class="col-span-6" />

        @if (count($category_types) > 1)
            <x-form.group.select name="type" label="{{ trans_choice('general.types', 1) }}" :options="$types" change="updateParentCategories" form-group-class="col-span-6" :group="$type_group" />

            <x-form.group.text name="code" label="{{ trans('general.code') }}" form-group-class="col-span-6" v-show="isCategoryCodeFieldVisible()" />

            <x-form.group.select name="parent_id" label="{{ trans('general.parent') . ' ' . trans_choice('general.categories', 1) }}" :options="[]" not-required dynamicOptions="categoriesBasedTypes" sort-options="false" v-disabled="selected_type" form-group-class="col-span-6" />

            <x-form.input.hidden name="parent_categories" value="{{ json_encode($categories) }}" />
        @else
            @php ($single_type = reset($category_types))

            <x-form.input.hidden name="type" value="{{ $single_type }}" />

            @if (empty($hide_code_types[$single_type]) || ! $hide_code_types[$single_type])
                <x-form.group.text name="code" label="{{ trans('general.code') }}" form-group-class="col-span-6" />
            @endif

            <x-form.group.select name="parent_id" label="{{ trans('general.parent') . ' ' . trans_choice('general.categories', 1) }}" :options="collect($categories[$single_type] ?? [])" not-required sort-options="false" searchable form-group-class="col-span-6" />
        @endif

        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />

        <x-form.input.hidden name="enabled" value="1" />
        <x-form.input.hidden name="type_codes" value="{{ json_encode($hide_code_types) }}" />
    </div>
</x-form>
