<x-form id="form-create-customer" route="customers.store">
    <x-tabs active="general" class="grid grid-cols-3" override="class">
        <x-slot name="navs">
            <x-tabs.nav id="general">
                {{ trans('general.general') }}

                <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('name') || form.errors.has('email') || form.errors.has('phone') || form.errors.has('tax_number') || form.errors.has('currency_code')">
                    {{ trans('general.validation_error') }}
                </span>
            </x-tabs.nav>

            <x-tabs.nav id="address">
                {{ trans('general.address') }}

                <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('address') || form.errors.has('city') || form.errors.has('zip_code') || form.errors.has('state') || form.errors.has('country')">
                    {{ trans('general.validation_error') }}
                </span>
            </x-tabs.nav>

            <x-tabs.nav id="other">
                {{ trans_choice('general.others', 1) }}

                <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('website') || form.errors.has('reference')">
                    {{ trans('general.validation_error') }}
                </span>
            </x-tabs.nav>
        </x-slot>

        <x-slot name="content">
            <x-tabs.tab id="general">
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                    <x-form.group.text name="name" label="{{ trans('general.name') }}" form-group-class="col-span-6" />

                    <x-form.group.text name="email" label="{{ trans('general.email') }}" form-group-class="col-span-6" not-required />

                    <x-form.group.text name="phone" label="{{ trans('general.phone') }}" form-group-class="col-span-6" not-required />

                    <x-form.group.text name="tax_number" label="{{ trans('general.tax_number') }}" form-group-class="col-span-6" not-required />

                    <x-form.group.currency without-add-new form-group-class="col-span-6" :add-new-text="trans_choice('general.currencies', 1)" />
                </div>
            </x-tabs.tab>

            <x-tabs.tab id="address">
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                    <x-form.group.textarea name="address" label="{{ trans('general.address') }}" form-group-class="col-span-6" rows=2 not-required />

                    <x-form.group.text name="city" label="{{ trans_choice('general.cities', 1) }}" form-group-class="col-span-6" not-required />

                    <x-form.group.text name="zip_code" label="{{ trans('general.zip_code') }}" form-group-class="col-span-6" not-required />

                    <x-form.group.text name="state" label="{{ trans('general.state') }}" form-group-class="col-span-6" not-required />

                    <x-form.group.country form-group-class="col-span-6 el-select-tags-pl-38" not-required />
                </div>
            </x-tabs.tab>

            <x-tabs.tab id="other">
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                    <x-form.group.text name="website" label="{{ trans('general.website') }}" form-group-class="col-span-6" not-required />

                    <x-form.group.text name="reference" label="{{ trans('general.reference') }}" form-group-class="col-span-6" not-required />

                    <x-form.input.hidden name="type" value="customer" />

                    <x-form.input.hidden name="enabled" value="1" />
                </div>
            </x-tabs.tab>
        </x-slot>
    </x-tabs>
</x-form>
