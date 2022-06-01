<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.new', ['type' => trans_choice('general.companies', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="company" route="companies.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('companies.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-3 grid gap-x-8 gap-y-6 grid-rows-3">
                            <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                            <x-form.group.email name="email" label="{{ trans('general.email') }}" />

                            <x-form.group.text name="phone" label="{{ trans('settings.company.phone') }}" value="{{ setting('company.phone') }}" not-required />
                        </div>

                        <div class="sm:col-span-3">
                            <x-form.group.file name="logo" label="{{ trans('companies.logo') }}" not-required />
                        </div>
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('items.billing') }}" description="{{ trans('companies.form_description.billing') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="tax_number" label="{{ trans('general.tax_number') }}" not-required />

                        <x-form.group.currency name="currency" />

                        <x-form.group.locale not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.address') }}" description="{{ trans('companies.form_description.address') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.textarea name="address" label="{{ trans('general.address') }}" v-model="form.address" />

                        <x-form.group.text name="city" label="{{ trans_choice('general.cities', 1) }}" value="{{ setting('company.city') }}" not-required />

                        <x-form.group.text name="zip_code" label="{{ trans('general.zip_code') }}" value="{{ setting('company.zip_code') }}" not-required />

                        <x-form.group.text name="state" label="{{ trans('general.state') }}" value="{{ setting('company.state') }}" not-required />

                        <x-form.group.country />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="companies.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="common" file="companies" />
</x-layouts.admin>
