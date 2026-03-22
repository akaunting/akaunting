/**
 * OAuth Tokens JavaScript
 */

import Form from './../plugins/form';
import Notification from './../plugins/notification';

let createTokenModal;
let tokenCreatedModal;
let newTokenValue = '';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize forms
    if (typeof create_personal_token_form !== 'undefined') {
        var form = new Form('create-personal-token');

        form.onSuccess(function(response) {
            if (response.data && response.data.access_token) {
                newTokenValue = response.data.access_token;
                showTokenCreatedModal();
            }
        });
    }

    // Load scopes
    loadScopes();
});

window.showCreateTokenModal = function() {
    if (!createTokenModal) {
        createTokenModal = document.getElementById('create-token-modal');
    }
    
    // Show modal (depends on Akaunting modal implementation)
    if (createTokenModal) {
        createTokenModal.classList.remove('hidden');
    }
};

function showTokenCreatedModal() {
    // Hide create modal
    if (createTokenModal) {
        createTokenModal.classList.add('hidden');
    }

    // Show success modal
    tokenCreatedModal = document.getElementById('token-created-modal');
    if (tokenCreatedModal) {
        tokenCreatedModal.classList.remove('hidden');
        
        // Fill token value
        const tokenTextarea = document.getElementById('new-token-value');
        if (tokenTextarea) {
            tokenTextarea.value = newTokenValue;
        }
    }
}

window.closeTokenModal = function() {
    if (tokenCreatedModal) {
        tokenCreatedModal.classList.add('hidden');
    }
    
    // Reload page to show new token in list
    window.location.reload();
};

window.copyToken = function() {
    const tokenTextarea = document.getElementById('new-token-value');
    if (tokenTextarea) {
        tokenTextarea.select();
        document.execCommand('copy');
        
        Notification.show({
            type: 'success',
            message: window.trans('oauth.token_copied')
        });
    }
};

function loadScopes() {
    // Load available scopes from API
    fetch(window.url + '/' + window.locale + '/oauth/scopes')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                updateScopesSelect(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading scopes:', error);
        });
}

function updateScopesSelect(scopes) {
    const scopeSelect = document.querySelector('select[name="scopes[]"]');
    if (scopeSelect && scopes.length > 0) {
        scopeSelect.innerHTML = '';
        
        scopes.forEach(scope => {
            const option = document.createElement('option');
            option.value = scope.id;
            option.textContent = scope.description || scope.id;
            scopeSelect.appendChild(option);
        });
    }
}
