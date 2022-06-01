<x-form id="form-create-vendor" route="vendors.store">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

        <x-form.group.text name="email" label="{{ trans('general.email') }}" not-required />

        <x-form.group.text name="tax_number" label="{{ trans('general.tax_number') }}" not-required />

        <x-form.group.currency without-add-new />

        <x-form.group.textarea name="address" label="{{ trans('general.address') }}" />

        <x-form.group.country />

        <x-form.input.hidden name="type" value="vendor" />
        <x-form.input.hidden name="enabled" value="1" />
    </div>
</x-form>
