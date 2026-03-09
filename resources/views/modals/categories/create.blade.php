<x-form id="form-create-category" route="categories.store">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.text name="name" label="{{ trans('general.name') }}" form-group-class="col-span-6" />

        @if ($has_code)
            <x-form.group.text name="code" label="{{ trans('general.code') }}" form-group-class="col-span-6" />
        @endif

        <x-form.group.color name="color" label="{{ trans('general.color') }}" form-group-class="col-span-6" />

        <x-form.group.select name="parent_id" label="{{ trans('general.parent') . ' ' . trans_choice('general.categories', 1) }}" :options="$categories" not-required sort-options="false" searchable form-group-class="col-span-6" />

        @if (!empty($types) && count($types) > 1)
            <x-form.group.select name="type" label="{{ trans_choice('general.types', 1) }}" :options="$types" value="{{ $type }}" form-group-class="col-span-6" />
        @else
            <x-form.input.hidden name="type" value="{{ $type }}" />
        @endif

        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />

        <x-form.input.hidden name="enabled" value="1" />
    </div>
</x-form>
