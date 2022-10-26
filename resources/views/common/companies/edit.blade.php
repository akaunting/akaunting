<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans_choice('general.companies', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="company" method="PATCH" :route="['companies.update', $company->id]" :model="$company">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('companies.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.email name="email" label="{{ trans('general.email') }}" />

                        <x-form.group.currency name="currency" :options="$currencies" selected="{{ ! empty($company->currency) ? $company->currency : config('setting.fallback.default.currency') }}" without-add-new />

                        <x-form.group.country />
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                @can('update-common-companies')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="companies.index" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    @push('scripts_end')
        <script type="text/javascript">
            var country_validation_required_message = "{{ trans('validation.required', ['attribute' => trans_choice('general.countries', 1)]) }}";
        </script>
    @endpush

    <x-script folder="common" file="companies" />
</x-layouts.admin>
