<x-form.section>
    <x-slot name="head">
        <x-form.section.head
            title="{{ trans($textSectionGeneralTitle) }}"
            description="{{ trans($textSectionGeneralDescription) }}"
        />
    </x-slot>

    <x-slot name="body">
        @if (! $hideName)
            <x-form.group.text name="name" label="{{ trans($textName) }}" form-group-class="{{ $classNameFromGroupClass }}" />
        @endif

        <div class="sm:col-span-3">
            <div class="relative sm:col-span-6 grid gap-x-8 gap-y-6">
                @if (! $hideEmail)
                    <x-form.group.text name="email" label="{{ trans($textEmail) }}" form-group-class="sm:col-span-6" not-required />
                @endif

                @if (! $hidePhone)
                    <x-form.group.text name="phone" label="{{ trans($textPhone) }}" form-group-class="sm:col-span-6" not-required />
                @endif

                @if (! $hideWebsite)
                    <x-form.group.text name="website" label="{{ trans($textWebsite) }}" form-group-class="sm:col-span-6" not-required />
                @endif

                @if (! $hideReference)
                    <x-form.group.text name="reference" label="{{ trans($textReference) }}" form-group-class="sm:col-span-6" not-required />
                @endif
            </div>
        </div>

        <div class="sm:col-span-3">
            <div class="relative sm:col-span-6 grid gap-x-8 gap-y-6">
                @if (! $hideCanLogin)
                    <div class="sm:col-span-6 mt-9 mb-2">
                        @if (empty($contact))
                            <x-tooltip id="tooltip-client_portal-text" placement="bottom" message="{{ trans('customers.can_login_description') }}">
                                <x-form.group.checkbox
                                    name="create_user"
                                    :options="['1' => trans('customers.can_login')]"
                                    @input="onCanLogin($event)"
                                />
                            </x-tooltip>
                        @else
                            @if ($contact->user_id)
                                <x-form.group.checkbox
                                    name="create_user"
                                    :options="['1' => trans('customers.user_created')]"
                                    checked
                                    disabled
                                />
                            @else
                                <x-tooltip id="tooltip-client_portal-text" placement="bottom" message="{{ trans('customers.can_login_description') }}">
                                    <x-form.group.checkbox
                                        name="create_user"
                                        :options="['1' => trans('customers.can_login')]"
                                        @input="onCanLogin($event)"
                                    />
                                </x-tooltip>
                            @endif
                        @endif
                    </div>
                @endif

                @if (! $hideLogo)
                    <x-form.group.file name="logo" label="{{ trans_choice('general.pictures', 1) }}" :value="! empty($contact) ? $contact->logo : false" form-group-class="sm:col-span-6" not-required />
                @endif
            </div>
        </div>
    </x-slot>
</x-form.section>
