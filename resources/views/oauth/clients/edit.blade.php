<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans('oauth.client')]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="oauth-client" method="PATCH" :route="['oauth.clients.update', $client->id]" :model="$client">
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

                        @php
                            // Parse redirect URLs for display
                            $redirectDisplay = $client->redirect;
                            $decoded = json_decode($client->redirect, true);
                            if (is_array($decoded)) {
                                $redirectDisplay = implode("\n", $decoded);
                            }
                        @endphp

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
                            >{{ $redirectDisplay }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ trans('oauth.redirect_urls_help') }}
                            </p>
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('oauth.client_id') }}
                            </label>
                            <div class="flex items-center space-x-2">
                                <code class="flex-1 bg-gray-100 px-3 py-2 rounded text-sm">{{ $client->id }}</code>
                                <x-button 
                                    type="button" 
                                    kind="secondary"
                                    onclick="navigator.clipboard.writeText('{{ $client->id }}')"
                                >
                                    {{ trans('general.copy') }}
                                </x-button>
                            </div>
                        </div>

                        @if (!empty($client->secret))
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ trans('oauth.client_secret') }}
                                </label>
                                <div class="flex items-center space-x-2">
                                    <code class="flex-1 bg-gray-100 px-3 py-2 rounded text-sm">••••••••••••••••</code>
                                    <x-button 
                                        type="button" 
                                        kind="danger"
                                        onclick="confirmRegenerateSecret('{{ $client->id }}', '{{ $client->name }}')"
                                    >
                                        {{ trans('oauth.regenerate_secret') }}
                                    </x-button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ trans('oauth.secret_warning') }}
                                </p>
                            </div>
                        @else
                            <div class="sm:col-span-6">
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <span class="material-icons h-5 w-5 text-yellow-400">warning</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                {{ trans('oauth.public_client_notice') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="oauth.clients.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>

        {{-- Regenerate Secret Confirmation Modal --}}
        <x-modal id="regenerate-secret-modal" title="{{ trans('oauth.confirm_regenerate_secret_title') }}">
            <div class="space-y-4">
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons h-5 w-5 text-red-400">warning</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ trans('oauth.confirm_regenerate_secret') }}
                            </p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-700">
                    <strong>{{ trans('oauth.client') }}:</strong> <span id="regenerate-client-name"></span>
                </p>

                <div class="flex justify-end space-x-2 pt-4">
                    <x-button type="button" kind="secondary" onclick="closeRegenerateModal()">
                        {{ trans('general.cancel') }}
                    </x-button>
                    <x-button type="button" kind="danger" onclick="executeRegenerateSecret()">
                        {{ trans('oauth.regenerate_secret') }}
                    </x-button>
                </div>
            </div>
        </x-modal>

        {{-- New Secret Display Modal --}}
        <x-modal id="new-secret-modal" title="{{ trans('oauth.new_secret_title') }}">
            <div class="space-y-4">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons h-5 w-5 text-green-400">check_circle</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ trans('oauth.secret_regenerated_success') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ trans('oauth.new_client_secret') }}
                    </label>
                    <div class="flex items-center space-x-2">
                        <code id="new-secret-display" class="flex-1 bg-gray-900 text-green-400 px-4 py-3 rounded text-sm font-mono break-all"></code>
                        <x-button 
                            type="button" 
                            kind="secondary"
                            onclick="copyNewSecret()"
                        >
                            {{ trans('general.copy') }}
                        </x-button>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons h-5 w-5 text-yellow-400">warning</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ trans('oauth.secret_copy_warning') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <x-button type="button" kind="primary" onclick="closeNewSecretModal()">
                        {{ trans('general.done') }}
                    </x-button>
                </div>
            </div>
        </x-modal>
    </x-slot>

    @push('scripts_start')
    <script type="text/javascript">
        let currentClientId = null;

        function confirmRegenerateSecret(clientId, clientName) {
            currentClientId = clientId;
            document.getElementById('regenerate-client-name').textContent = clientName;
            
            // Show modal
            const modal = document.getElementById('regenerate-secret-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeRegenerateModal() {
            const modal = document.getElementById('regenerate-secret-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            currentClientId = null;
        }

        function executeRegenerateSecret() {
            if (!currentClientId) return;

            // Close confirmation modal
            closeRegenerateModal();

            // Show loading state
            const loadingMessage = '{{ trans("general.loading") }}';
            
            // Make AJAX request
            fetch(`/{{ company_id() }}/oauth/clients/${currentClientId}/secret`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.secret) {
                    // Show new secret in modal
                    document.getElementById('new-secret-display').textContent = data.data.secret;
                    
                    const modal = document.getElementById('new-secret-modal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                } else {
                    alert(data.message || '{{ trans("general.error") }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ trans("general.error.occurred") }}');
            });
        }

        function copyNewSecret() {
            const secretText = document.getElementById('new-secret-display').textContent;
            navigator.clipboard.writeText(secretText).then(() => {
                // Show success feedback
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = '{{ trans("general.copied") }}';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            });
        }

        function closeNewSecretModal() {
            const modal = document.getElementById('new-secret-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            
            // Reload page to update UI
            window.location.reload();
        }
    </script>
    @endpush

    <x-script folder="oauth" file="clients" />
</x-layouts.admin>
