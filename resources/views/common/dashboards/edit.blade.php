<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans_choice('general.dashboards', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="dashboard" method="PATCH" :route="['dashboards.update', $dashboard->id]" :model="$dashboard">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('dashboards.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        @can('read-auth-users')
                            <x-form.group.checkbox name="users" label="{{ trans_choice('general.users', 2) }}" :options="$users" :checked="$dashboard->users()->pluck('id')->toArray()" />
                        @endcan
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                @can('update-common-dashboards')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="dashboards.index" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="common" file="dashboards" />
</x-layouts.admin>
