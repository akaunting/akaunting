<x-layouts.install>
    <x-slot name="title">
        {{ trans('install.steps.settings') }}
    </x-slot>

    <x-slot name="content">
        <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
            <x-form.group.text name="company_name" label="{{ trans('install.settings.company_name') }}" value="{{ old('company_name') }}" form-group-class="sm:col-span-6" />

            <x-form.group.text name="company_email" label="{{ trans('install.settings.company_email') }}" value="{{ old('company_email') }}" form-group-class="sm:col-span-6" />

            <x-form.group.text name="user_email" label="{{ trans('install.settings.admin_email') }}" value="{{ old('user_email') }}" form-group-class="sm:col-span-6" />

            <x-form.group.password name="user_password" label="{{ trans('install.settings.admin_password') }}" form-group-class="sm:col-span-6" />
        </div>
    </x-slot>
</x-layouts.install>
