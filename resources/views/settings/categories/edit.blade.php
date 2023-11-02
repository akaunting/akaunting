<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.categories', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="category" method="PATCH" :route="['categories.update', $category->id]" :model="$category">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('categories.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.color name="color" label="{{ trans('general.color') }}" />

                        @if ($type_disabled)
                            <x-form.group.select name="type" label="{{ trans_choice('general.types', 1) }}" :options="$types" v-disabled="true" />

                            <input type="hidden" name="type" value="{{ $category->type }}" />
                        @else
                            <x-form.group.select name="type" label="{{ trans_choice('general.types', 1) }}" :options="$types" change="updateParentCategories" />

                            <x-form.group.select name="parent_id" label="{{ trans('general.parent') . ' ' . trans_choice('general.categories', 1) }}" :options="$parent_categories" not-required dynamicOptions="categoriesBasedTypes" sort-options="false" />

                            <x-form.input.hidden name="parent_category_id" value="{{ $category->parent_id }}" />
                            <x-form.input.hidden name="categories" value="{{ json_encode($categories) }}" />
                        @endif
                    </x-slot>
                </x-form.section>

                @if (! $type_disabled)
                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />
                @endif

                @can('update-settings-categories')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="categories.index" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="categories" />
</x-layouts.admin>
