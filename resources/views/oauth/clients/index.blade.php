<x-layouts.admin>
    <x-slot name="title">{{ trans('oauth.clients') }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans('oauth.clients') }}"
        icon="key"
        route="oauth.clients.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-oauth-clients')
            <x-link href="{{ route('oauth.clients.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans('oauth.client')]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        @if ($clients->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search search-string="App\Models\OAuth\Client" />

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th class="w-5/12">
                                {{ trans('general.name') }}
                            </x-table.th>

                            <x-table.th class="w-4/12" hidden-mobile>
                                {{ trans('oauth.client_id') }}
                            </x-table.th>

                            <x-table.th class="w-2/12" hidden-mobile>
                                {{ trans('general.created_at') }}
                            </x-table.th>

                            <x-table.th class="w-1/12" kind="action">
                                {{ trans('general.actions') }}
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($clients as $client)
                            <x-table.tr href="{{ route('oauth.clients.show', $client->id) }}">
                                <x-table.td class="w-5/12">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-purple-100">
                                                <span class="material-icons w-5 h-5 text-purple-600">key</span>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $client->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                @if ($client->personal_access_client)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ trans('oauth.personal_access') }}
                                                    </span>
                                                @elseif ($client->password_client)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        {{ trans('oauth.password_grant') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ trans('oauth.authorization_code') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </x-table.td>

                                <x-table.td class="w-4/12" hidden-mobile>
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $client->id }}</code>
                                </x-table.td>

                                <x-table.td class="w-2/12" hidden-mobile>
                                    <x-date :date="$client->created_at" />
                                </x-table.td>

                                <x-table.td class="w-1/12" kind="action">
                                    <x-table.actions :model="$client" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$clients" />
            </x-index.container>
        @else
            <x-empty-page
                group="oauth"
                page="clients"
                :buttons="[
                    'new' => [
                        'url' => route('oauth.clients.create'),
                        'permission' => 'create-oauth-clients',
                    ],
                ]"
            />
        @endif
    </x-slot>
</x-layouts.admin>
