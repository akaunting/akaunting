<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans_choice('general.users', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="user" method="PATCH" :route="[$route, $user->id]" :model="$user">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('auth.personal_information') }}" description="{{ trans('auth.form_description.personal') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-3 grid gap-x-8 gap-y-6 {{ user()->id == $user->id ? 'grid-rows-3' : 'grid-rows-2' }}">
                            <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                            <x-form.group.email name="email" label="{{ trans('general.email') }}" ::disabled="{{ $user->hasPendingInvitation() ? 'true' : 'false' }}" />

                            @if (user()->id == $user->id)
                            <x-form.group.checkbox name="change_password" :options="['1' => trans('auth.change_password')]" form-group-class="sm:col-span-3" checkbox-class="sm:col-span-6" @input="onChangePassword($event)" />

                            <x-form.group.password name="password" :label="trans('auth.password.new')" v-show="show_password" />
                            @endif
                        </div>

                        <div class="sm:col-span-3 grid gap-x-8 gap-y-6 {{ user()->id == $user->id ? 'grid-rows-3' : 'grid-rows-2' }}">
                            @if (setting('default.use_gravatar', '0') == '1')
                                <x-form.group.text name="fake_picture" label="{{ trans_choice('general.pictures', 1) }}" disabled placeholder="{{ trans('settings.default.use_gravatar') }}" form-group-class="sm:col-span-3 sm:row-span-2" />
                            @else
                                <x-form.group.file name="picture" :value="$user->picture" label="{{ trans_choice('general.pictures', 1) }}" not-required form-group-class="sm:col-span-3 sm:row-span-2" />
                            @endif

                            @if (user()->id == $user->id)
                            <x-form.group.password name="current_password" :label="trans('auth.password.current')" v-show="show_password" />

                            <x-form.group.password name="password_confirmation" :label="trans('auth.password.new_confirm')" v-show="show_password" />
                            @endif
                        </div>
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.assign') }}" description="{!! trans('auth.form_description.assign', ['url' => $roles_url]) !!}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select multiple remote name="companies" label="{{ trans_choice('general.companies', 2) }}" :options="$companies" selected-key="company_ids" :remote_action="route('companies.index')" form-group-class="sm:col-span-6" />

                        @if (module_is_enabled('roles'))
                            @can('read-roles-roles')
                                <x-form.group.select name="roles" label="{{ trans_choice('general.roles', 1) }}" :options="$roles" change="onChangeRole" selected-key="roles.id" />
                            @endcan
                        @else
                            @role('admin|manager')
                                <x-form.group.select name="roles" label="{{ trans_choice('general.roles', 1) }}" :options="$roles" change="onChangeRole" selected-key="roles.id" />
                            @endrole
                        @endif
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.preferences') }}" description="{!! trans('auth.form_description.preferences') !!}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select name="landing_page" label="{{ trans('auth.landing_page') }}" :options="$landing_pages" dynamicOptions="landing_pages" force-dynamic-option-value />

                        <x-form.group.locale />
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                @canany(['update-auth-users', 'update-auth-profile'])
                    <x-form.section>
                        <x-slot name="foot">
                            @if (user()->can('read-auth-users'))
                                <x-form.buttons cancel-route="users.index" />
                            @else
                                <x-form.buttons cancel-route="dashboard" />
                            @endif
                        </x-slot>
                    </x-form.section>
                @endcanany
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="auth" file="users" />
</x-layouts.admin>
