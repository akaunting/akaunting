<x-layouts.portal>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans('auth.profile')]) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="portal" method="PATCH" :route="['portal.profile.update', $user->id]" :model="$user">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('auth.personal_information') }}" description="{{ trans('auth.form_description.personal') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-3 grid gap-x-8 gap-y-6 {{ user()->id == $user->id ? 'grid-rows-4' : 'grid-rows-3' }}">
                            <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                            <x-form.group.email name="email" label="{{ trans('general.email') }}" />

                            <x-form.group.text name="phone" label="{{ trans('general.phone') }}" value="{{ $user->contact->phone }}" not-required />

                            <x-form.group.checkbox name="change_password" :options="['1' => trans('auth.change_password')]" form-group-class="sm:col-span-3 {{ user()->id == $user->id ? '' : 'hidden' }}" input-group-class="pt-8" @input="onChangePassword($event)" />

                            <x-form.group.password name="password" :label="trans('auth.password.new')" v-show="show_password" />
                        </div>

                        <div class="sm:col-span-3 grid gap-x-8 gap-y-6 {{ user()->id == $user->id ? 'grid-rows-4' : 'grid-rows-3' }}">
                            @if (setting('default.use_gravatar', '0') == '1')
                                <x-form.group.text name="fake_picture" label="{{ trans_choice('general.pictures', 1) }}" disabled placeholder="{{ trans('settings.default.use_gravatar') }}" form-group-class="sm:col-span-3 sm:row-span-3" />
                            @else
                                <x-form.group.file name="picture" label="{{ trans_choice('general.pictures', 1) }}" :value="$user->picture" not-required form-group-class="sm:col-span-3 sm:row-span-3" />
                            @endif

                            <x-form.group.password name="current_password" :label="trans('auth.password.current')" v-show="show_password" />

                            <x-form.group.password name="password_confirmation" :label="trans('auth.password.new_confirm')" v-show="show_password" />
                        </div>
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.preferences') }}" description="{!! trans('auth.form_description.preferences') !!}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="tax_number" label="{{ trans('general.tax_number') }}" value="{{ $user->contact->tax_number }}" not-required />

                        <x-form.group.locale />
                            
                        <x-form.group.textarea name="address" label="{{ trans('general.address') }}" :value="$user->contact->address" v-model="form.address" not-required />

                        <x-form.group.text name="city" label="{{ trans_choice('general.cities', 1) }}" value="{{ $user->contact->city }}" not-required />

                        <x-form.group.text name="zip_code" label="{{ trans('general.zip_code') }}" value="{{ $user->contact->zip_code }}" not-required />

                        <x-form.group.text name="state" label="{{ trans('general.state') }}" value="{{ $user->contact->state }}" not-required />

                        <x-form.group.country :selected="$user->contact->country" not-required />
                    </x-slot>
                </x-form.section>

                @canany(['update-portal-profile'])
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="portal.dashboard" />
                    </x-slot>
                </x-form.section>
                @endcanany
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="portal" file="apps" />
</x-layouts.portal>
