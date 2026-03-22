/**
 * OAuth Clients JavaScript
 */

import Form from './../plugins/form';
import Notification from './../plugins/notification';

if (typeof clients_form !== 'undefined') {
    var form = new Form('oauth-client');

    form.onSuccess(function(response) {
        if (response.data && response.data.client) {
            let client = response.data.client;
            
            // Show client credentials if new client created
            if (client.plainSecret) {
                showClientCredentials(client);
            } else if (response.redirect) {
                window.location.href = response.redirect;
            }
        }
    });
}

function showClientCredentials(client) {
    let html = `
        <div class="space-y-4">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            ${window.trans('oauth.credentials_warning')}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    ${window.trans('oauth.client_id')}
                </label>
                <div class="flex items-center space-x-2">
                    <input type="text" readonly value="${client.id}" class="flex-1 bg-gray-100 px-3 py-2 rounded text-sm font-mono">
                    <button onclick="navigator.clipboard.writeText('${client.id}')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                        ${window.trans('general.copy')}
                    </button>
                </div>
            </div>

            ${client.plainSecret ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ${window.trans('oauth.client_secret')}
                    </label>
                    <div class="flex items-center space-x-2">
                        <textarea readonly rows="2" class="flex-1 bg-gray-100 px-3 py-2 rounded text-sm font-mono">${client.plainSecret}</textarea>
                        <button onclick="navigator.clipboard.writeText('${client.plainSecret}')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            ${window.trans('general.copy')}
                        </button>
                    </div>
                </div>
            ` : ''}
        </div>
    `;

    Notification.show({
        type: 'success',
        title: window.trans('oauth.client_created'),
        message: html,
        duration: 0
    });
}

// Handle secret regeneration
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="regenerate-secret-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm(window.trans('oauth.confirm_regenerate_secret'))) {
                let formElement = new Form(form.id);
                
                formElement.onSuccess(function(response) {
                    if (response.data && response.data.secret) {
                        showNewSecret(response.data.secret);
                    }
                });

                formElement.submit();
            }
        });
    });
});

function showNewSecret(secret) {
    let html = `
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                ${window.trans('oauth.new_client_secret')}
            </label>
            <textarea readonly rows="2" class="w-full bg-gray-100 px-3 py-2 rounded text-sm font-mono">${secret}</textarea>
        </div>
    `;

    Notification.show({
        type: 'success',
        title: window.trans('oauth.secret_regenerated'),
        message: html,  
        duration: 0
    });
}
