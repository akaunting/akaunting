<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.categories', 1)]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.categories', 1)]) }}"
        icon="folder"
        route="categories.create"
    ></x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="category" route="categories.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('categories.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.color name="color" label="{{ trans('general.color') }}" />

                        <x-form.group.select name="type" label="{{ trans_choice('general.types', 1) }}" :options="$types" :selected="config('general.types')" change="updateParentCategories" />

                        <x-form.group.select name="parent_id" label="{{ trans('general.parent') . ' ' . trans_choice('general.categories', 1) }}" :options="[]" not-required dynamicOptions="categoriesBasedTypes" sort-options="false" v-disabled="selected_type" />

                        <x-form.input.hidden name="categories" value="{{ json_encode($categories) }}" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="categories.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="categories" />
</x-layouts.admin>
