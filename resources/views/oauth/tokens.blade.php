<x-layouts.admin>
    <x-slot name="title">{{ trans('oauth.personal_access_tokens') }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans('oauth.personal_access_tokens') }}"
        icon="vpn_key"
        route="oauth.tokens.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-button 
            id="create-token-button"
            onclick="showCreateTokenModal()"
        >
            {{ trans('oauth.create_token') }}
        </x-button>
    </x-slot>

    <x-slot name="content">
        @if (count($tokens) > 0)
            <x-index.container>
                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th class="w-4/12">
                                {{ trans('general.name') }}
                            </x-table.th>

                            <x-table.th class="w-3/12" hidden-mobile>
                                {{ trans('oauth.scopes') }}
                            </x-table.th>

                            <x-table.th class="w-2/12" hidden-mobile>
                                {{ trans('general.created_at') }}
                            </x-table.th>

                            <x-table.th class="w-2/12" hidden-mobile>
                                {{ trans('oauth.expires_at') }}
                            </x-table.th>

                            <x-table.th class="w-1/12" kind="action">
                                {{ trans('general.actions') }}
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($tokens as $token)
                            <x-table.tr>
                                <x-table.td class="w-4/12">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                                <span class="material-icons w-5 h-5 text-green-600">vpn_key</span>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $token->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                @if ($token->revoked)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        {{ trans('oauth.revoked') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        {{ trans('oauth.active') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </x-table.td>

                                <x-table.td class="w-3/12" hidden-mobile>
                                    @if (count($token->scopes) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($token->scopes as $scope)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $scope }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">{{ trans('oauth.all_scopes') }}</span>
                                    @endif
                                </x-table.td>

                                <x-table.td class="w-2/12" hidden-mobile>
                                    <x-date :date="$token->created_at" />
                                </x-table.td>

                                <x-table.td class="w-2/12" hidden-mobile>
                                    @if ($token->expires_at)
                                        <x-date :date="$token->expires_at" />
                                    @else
                                        <span class="text-sm text-gray-500">{{ trans('oauth.never') }}</span>
                                    @endif
                                </x-table.td>

                                <x-table.td class="w-1/12" kind="action">
                                    @if (!$token->revoked)
                                        <x-delete-link
                                            :route="['oauth.tokens.destroy', $token->id]"
                                            text="{{ trans('oauth.revoke') }}"
                                        />
                                    @endif
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>
            </x-index.container>
        @else
            <x-empty-page
                group="oauth"
                page="tokens"
                text="{{ trans('oauth.no_tokens') }}"
                :buttons="[
                    'new' => [
                        'id' => 'empty-create-token-button',
                        'onclick' => 'showCreateTokenModal()',
                        'text' => trans('oauth.create_token'),
                    ],
                ]"
            />
        @endif

        <!-- Create Token Modal -->
        <x-modal id="create-token-modal" title="{{ trans('oauth.create_token') }}">
            <x-form id="create-personal-token" route="oauth.personal-tokens.store">
                <x-form.group.text 
                    name="name" 
                    label="{{ trans('general.name') }}" 
                    placeholder="{{ trans('oauth.token_name_placeholder') }}"
                />

                <x-form.group.select 
                    name="scopes[]" 
                    label="{{ trans('oauth.scopes') }}"
                    :options="[]"
                    multiple
                    not-required
                    placeholder="{{ trans('oauth.all_scopes') }}"
                />

                <x-form.buttons 
                    cancel-route="oauth.tokens.index" 
                    text="{{ trans('oauth.create_token') }}"
                />
            </x-form>
        </x-modal>

        <!-- Token Created Modal -->
        <x-modal id="token-created-modal" title="{{ trans('oauth.token_created') }}">
            <div class="space-y-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons h-5 w-5 text-yellow-400">error</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ trans('oauth.token_warning') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ trans('oauth.access_token') }}
                    </label>
                    <div class="flex items-center space-x-2">
                        <textarea 
                            id="new-token-value" 
                            readonly 
                            rows="3"
                            class="flex-1 bg-gray-100 px-3 py-2 rounded text-sm font-mono"
                        ></textarea>
                        <x-button 
                            type="button" 
                            kind="secondary"
                            onclick="copyToken()"
                        >
                            {{ trans('general.copy') }}
                        </x-button>
                    </div>
                </div>

                <div class="flex justify-end">
                    <x-button type="button" onclick="closeTokenModal()">
                        {{ trans('general.close') }}
                    </x-button>
                </div>
            </div>
        </x-modal>
    </x-slot>

    <x-script folder="oauth" file="tokens" />
</x-layouts.admin>
