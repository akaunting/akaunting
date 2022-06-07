<x-form id="form-edit-customer" method="PATCH" :route="['customers.update', $customer->id]" :model="$customer">
    <div x-data="{ active: 'general' }">
        <div>
            <div>
                <ul class="grid grid-cols-6">
                    <li class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link col-span-2"
                        id="tab-general"
                        data-id="tab-general"
                        data-tabs="general"
                        x-on:click="active = 'general'"
                        x-bind:class="active != 'general' ? '' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                    >
                        {{ trans('general.general') }}

                        <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('name')||form.errors.has('email')||form.errors.has('phone')||form.errors.has('tax_number')||form.errors.has('currency_code')">
                            {{ trans('general.validation_error') }}
                        </span>
                    </li>

                    <li class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link col-span-2"
                        id="tab-address"
                        data-id="tab-address"
                        data-tabs="address"
                        x-on:click="active = 'address'"
                        x-bind:class="active != 'address' ? '' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                    >
                        {{ trans('general.address') }}

                        <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('address')||form.errors.has('city')||form.errors.has('zip_code')||form.errors.has('state')||form.errors.has('country')">
                            {{ trans('general.validation_error') }}
                        </span>
                    </li>

                    <li class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link col-span-2"
                        id="tab-other"
                        data-id="tab-other"
                        data-tabs="other"
                        x-on:click="active = 'other'"
                        x-bind:class="active != 'other' ? '' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                    >
                        {{ trans_choice('general.others', 1) }}

                        <span class="invalid-feedback block text-xs text-red whitespace-normal" v-if="form.errors.has('website')||form.errors.has('reference')">
                            {{ trans('general.validation_error') }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div id="tab-general" data-tabs-content="general" x-show="active === 'general'">
            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                <x-form.group.text name="name" label="{{ trans('general.name') }}" form-group-class="col-span-6" />

                <x-form.group.text name="email" label="{{ trans('general.email') }}" form-group-class="col-span-6" not-required />
        
                <x-form.group.text name="phone" label="{{ trans('general.phone') }}" form-group-class="col-span-6" not-required />

                <x-form.group.text name="tax_number" label="{{ trans('general.tax_number') }}" form-group-class="col-span-6" not-required />
        
                <x-form.group.currency without-add-new form-group-class="col-span-6" />
            </div>
        </div>
        
        <div id="tab-address" data-tabs-content="address" x-show="active === 'address'">
            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">      
                <x-form.group.textarea name="address" label="{{ trans('general.address') }}" form-group-class="col-span-6" not-required />
        
                <x-form.group.text name="city" label="{{ trans_choice('general.cities', 1) }}" form-group-class="col-span-6" not-required />

                <x-form.group.text name="zip_code" label="{{ trans('general.zip_code') }}" form-group-class="col-span-6" not-required />

                <x-form.group.text name="state" label="{{ trans('general.state') }}" form-group-class="col-span-6" not-required />

                <x-form.group.country form-group-class="col-span-6 el-select-tags-pl-38" />
            </div>
        </div>

        <div id="tab-other" data-tabs-content="other" x-show="active === 'other'">
            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                <x-form.group.text name="website" label="{{ trans('general.website') }}" form-group-class="col-span-6" not-required />

                <x-form.group.text name="reference" label="{{ trans('general.reference') }}" form-group-class="col-span-6" not-required />

                <x-form.input.hidden name="type" value="customer" />
                <x-form.input.hidden name="enabled" value="1" />
            </div>
        </div>
    </div>
</x-form>
