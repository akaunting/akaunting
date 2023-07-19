<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.invite', ['type' => trans_choice('general.users', 1)]) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.invite', ['type' => trans_choice('general.users', 1)]) }}"
        icon="people"
        route="users.create"
    ></x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="user" route="users.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('auth.personal_information') }}" description="{{ trans('auth.form_description.personal') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-3 grid gap-x-8 gap-y-6 grid-rows-2">
                            <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                            <x-form.group.email name="email" label="{{ trans('general.email') }}" />
                        </div>

                        <div class="sm:col-span-3">
                            @if (setting('default.use_gravatar', '0') == '1')
                                <x-form.group.text name="fake_picture" label="{{ trans_choice('general.pictures', 1) }}" disabled placeholder="{{ trans('settings.default.use_gravatar') }}" />
                            @else
                                <x-form.group.file name="picture" label="{{ trans_choice('general.pictures', 1) }}" not-required />
                            @endif
                        </div>
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.assign') }}" description="{!! trans('auth.form_description.assign', ['url' => $roles_url]) !!}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select multiple remote name="companies" label="{{ trans_choice('general.companies', 2) }}" :options="$companies" remote_action="{{ route('companies.index') }}" form-group-class="sm:col-span-6" />

                        @if (module_is_enabled('roles'))
                            @can('read-roles-roles')
                                <x-form.group.select name="roles" label="{{ trans_choice('general.roles', 1) }}" :options="$roles" change="onChangeRole" />
                            @endcan
                        @else
                            @role('admin|manager')
                                <x-form.group.select name="roles" label="{{ trans_choice('general.roles', 1) }}" :options="$roles" change="onChangeRole" />
                            @endrole
                        @endif
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.preferences') }}" description="{{ trans('auth.form_description.preferences') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select name="landing_page" label="{!! trans('auth.landing_page') !!}" :options="$landing_pages" dynamicOptions="landing_pages" selected="dashboard" />

                        <x-form.group.locale />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="users.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="auth" file="users" />
</x-layouts.admin>
