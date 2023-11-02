<div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
    @if (module_is_enabled('roles'))
        @can('read-roles-roles')
            <x-form.group.select
                name="roles"
                label="{{ trans_choice('general.roles', 1) }}"
                :options="$roles"
                change="onChangeRole"
                selected=""
                not-required
                form-group-class="sm:col-span-6"
            />
        @endcan
    @else
        @role('admin|manager')
            <x-form.group.select
                name="roles"
                label="{{ trans_choice('general.roles', 1) }}"
                :options="$roles"
                change="onChangeRole"
                selected=""
                not-required
                form-group-class="sm:col-span-6"
            />
        @endrole
    @endif

    <x-form.group.select
        name="landing_page"
        label="{!! trans('auth.landing_page') !!}"
        :options="$landing_pages"
        dynamicOptions="landing_pages"
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.locale
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />
</div>
