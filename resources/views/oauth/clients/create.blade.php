<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.new', ['type' => trans('oauth.client')]) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans('oauth.clients') }}"
        icon="key"
        route="oauth.clients.create"
    ></x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="oauth-client" route="oauth.clients.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head 
                            title="{{ trans('oauth.client_information') }}" 
                            description="{{ trans('oauth.client_information_description') }}" 
                        />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text 
                            name="name" 
                            label="{{ trans('general.name') }}" 
                            placeholder="{{ trans('oauth.client_name_placeholder') }}"
                            form-group-class="sm:col-span-6"
                        />

                        <div class="sm:col-span-6">
                            <label for="redirect" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ trans('oauth.redirect_urls') }}
                            </label>
                            <textarea 
                                name="redirect" 
                                id="redirect"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                placeholder="https://example.com/callback&#10;https://app2.com/oauth/callback&#10;https://app3.com/auth/redirect"
                                required
                            ></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ trans('oauth.redirect_urls_help') }}
                            </p>
                        </div>

                        <x-form.group.checkbox 
                            name="confidential" 
                            label="{{ trans('oauth.confidential_client') }}"
                            :options="['1' => trans('oauth.confidential_client_description')]"
                            form-group-class="sm:col-span-6"
                        />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head 
                            title="{{ trans('oauth.grant_types') }}" 
                            description="{{ trans('oauth.grant_types_description') }}" 
                        />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-6">
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <span class="material-icons h-5 w-5 text-blue-400">info</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            {{ trans('oauth.grant_type_info') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="oauth.clients.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="oauth" file="clients" />
</x-layouts.admin>
