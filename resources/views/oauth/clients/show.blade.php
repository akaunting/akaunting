<x-layouts.admin>
    <x-slot name="title">{{ $client->name }}</x-slot>

    <x-slot name="favorite"
        title="{{ $client->name }}"
        icon="key"
        :route="['oauth.clients.show', $client->id]"
    ></x-slot>

    <x-slot name="buttons">
        @can('update-auth-users')
            <x-link href="{{ route('oauth.clients.edit', $client->id) }}" kind="secondary">
                {{ trans('general.edit') }}
            </x-link>
        @endcan

        @can('delete-auth-users')
            <x-delete-button 
                :model="$client" 
                :route="['oauth.clients.destroy', $client->id]"
                text="{{ trans('general.delete') }}"
            />
        @endcan
    </x-slot>

    <x-slot name="content">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Client Details -->
                <x-show.container>
                    <x-show.summary>
                        <x-slot name="title">{{ trans('oauth.client_details') }}</x-slot>

                        <x-slot name="body">
                            <x-show.summary.item 
                                label="{{ trans('general.name') }}"
                                :value="$client->name"
                            />

                            <x-show.summary.item 
                                label="{{ trans('oauth.client_id') }}"
                            >
                                <div class="flex items-center space-x-2">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $client->id }}</code>
                                    <button 
                                        onclick="navigator.clipboard.writeText('{{ $client->id }}')"
                                        class="text-blue-600 hover:text-blue-800 text-sm"
                                    >
                                        {{ trans('general.copy') }}
                                    </button>
                                </div>
                            </x-show.summary.item>

                            <x-show.summary.item 
                                label="{{ trans('oauth.redirect_url') }}"
                            >
                                <code class="text-sm">{{ $client->redirect }}</code>
                            </x-show.summary.item>

                            <x-show.summary.item 
                                label="{{ trans('oauth.grant_type') }}"
                            >
                                @if ($client->personal_access_client)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ trans('oauth.personal_access') }}
                                    </span>
                                @elseif ($client->password_client)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ trans('oauth.password_grant') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ trans('oauth.authorization_code') }}
                                    </span>
                                @endif
                            </x-show.summary.item>

                            <x-show.summary.item 
                                label="{{ trans('general.created_at') }}"
                            >
                                <x-date :date="$client->created_at" />
                            </x-show.summary.item>

                            <x-show.summary.item 
                                label="{{ trans('general.updated_at') }}"
                            >
                                <x-date :date="$client->updated_at" />
                            </x-show.summary.item>
                        </x-slot>
                    </x-show.summary>
                </x-show.container>

                <!-- Client Secret -->
                @if (!empty($client->secret))
                    <x-show.container>
                        <x-show.summary>
                            <x-slot name="title">{{ trans('oauth.client_secret') }}</x-slot>

                            <x-slot name="body">
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <span class="material-icons h-5 w-5 text-yellow-400">error</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                {{ trans('oauth.secret_hidden_message') }}
                                            </p>
                                            <div class="mt-3">
                                                <x-form 
                                                    id="regenerate-secret-{{ $client->id }}" 
                                                    method="POST" 
                                                    :route="['oauth.clients.secret', $client->id]"
                                                >
                                                    <x-button type="submit" kind="danger" size="sm">
                                                        {{ trans('oauth.regenerate_secret') }}
                                                    </x-button>
                                                </x-form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </x-slot>
                        </x-show.summary>
                    </x-show.container>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <x-show.container>
                    <x-show.summary>
                        <x-slot name="title">{{ trans('general.status') }}</x-slot>

                        <x-slot name="body">
                            <x-show.summary.item 
                                label="{{ trans('general.revoked') }}"
                            >
                                @if ($client->revoked)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ trans('general.yes') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ trans('general.no') }}
                                    </span>
                                @endif
                            </x-show.summary.item>
                        </x-slot>
                    </x-show.summary>
                </x-show.container>

                <!-- Owner -->
                @if ($client->user)
                    <x-show.container>
                        <x-show.summary>
                            <x-slot name="title">{{ trans('oauth.owner') }}</x-slot>

                            <x-slot name="body">
                                <div class="flex items-center">
                                    @if (setting('default.use_gravatar', '0') == '1')
                                        <img src="{{ $client->user->picture }}" alt="{{ $client->user->name }}" class="w-10 h-10 rounded-full" title="{{ $client->user->name }}">
                                    @elseif (is_object($client->user->picture))
                                        <img src="{{ Storage::url($client->user->picture->id) }}" alt="{{ $client->user->name }}" class="w-10 h-10 rounded-full" title="{{ $client->user->name }}">
                                    @else
                                        <img src="{{ asset('public/img/user.svg') }}" alt="{{ $client->user->name }}" class="w-10 h-10 rounded-full" title="{{ $client->user->name }}">
                                    @endif
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $client->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $client->user->email }}</p>
                                    </div>
                                </div>
                            </x-slot>
                        </x-show.summary>
                    </x-show.container>
                @endif
            </div>
        </div>
    </x-slot>
</x-layouts.admin>
