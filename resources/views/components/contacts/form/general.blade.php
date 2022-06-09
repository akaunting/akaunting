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

        <div class="sm:col-span-3 grid gap-x-8 gap-y-6">
            @if (! $hideEmail)
                <x-form.group.text name="email" label="{{ trans($textEmail) }}" not-required />
            @endif

            @if (! $hidePhone)
                <x-form.group.text name="phone" label="{{ trans($textPhone) }}" not-required />
            @endif

            @if (! $hideWebsite)
                <x-form.group.text name="website" label="{{ trans($textWebsite) }}" not-required />
            @endif

            @if (! $hideReference)
                <x-form.group.text name="reference" label="{{ trans($textReference) }}" not-required />
            @endif
        </div>

        <div class="sm:col-span-3">
            @if (! $hideCanLogin)
                <div class="mt-9">
                    @if (empty($contact))
                        <x-tooltip id="tooltip-client_portal-text" placement="bottom" message="{{ trans('customers.can_login_description') }}">
                            <x-form.group.checkbox
                                name="create_user"
                                :options="['1' => trans('customers.can_login')]"
                                @input="onCanLogin($event)"
                                checkbox-class="sm:col-span-6"
                            />
                        </x-tooltip>
                    @else
                        @if ($contact->user_id)
                            <x-form.group.checkbox
                                name="create_user"
                                :options="['1' => trans('customers.user_created')]"
                                checkbox-class="sm:col-span-6"
                                checked
                            />
                        @else
                            <x-tooltip id="tooltip-client_portal-text" placement="bottom" message="{{ trans('customers.can_login_description') }}">
                                <x-form.group.checkbox
                                    name="create_user"
                                    :options="['1' => trans('customers.can_login')]"
                                    checkbox-class="sm:col-span-6"
                                    @input="onCanLogin($event)"
                                />
                            </x-tooltip>
                        @endif
                    @endif
                </div>
            @endif

            @if (! $hideLogo)
                <x-form.group.file name="logo" label="{{ trans_choice('general.pictures', 1) }}"  not-required />
            @endif
        </div>
    </x-slot>
</x-form.section>
