<x-form id="setting" method="PATCH" route="settings.company.update">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5d">
        <x-form.group.text name="name" label="{{ trans('settings.company.name') }}" value="{{ setting('company.name') }}" />

        <x-form.group.text name="email" label="{{ trans('settings.company.email') }}" value="{{ setting('company.email') }}" />

        <x-form.group.text name="tax_number" label="{{ trans('general.tax_number') }}" value="{{ setting('company.tax_number') }}" not-required />

        <x-form.group.text name="phone" label="{{ trans('settings.company.phone') }}" value="{{ setting('company.phone') }}" not-required />

        <x-form.group.textarea name="address" label="{{ trans('settings.company.address') }}" :value="setting('company.address')" />

        <x-form.group.country />

        <x-form.input.hidden name="_prefix" value="company" />
    </div>
</x-form>
