<x-layouts.auth>
    <x-slot name="title">
        {{ trans('oauth.authorize_application') }}
    </x-slot>

    <x-slot name="content">
        <div>
            <img src="{{ asset('public/img/akaunting-logo-green.svg') }}" class="w-16" alt="Akaunting" />

            <h1 class="text-lg my-3">
                {{ trans('oauth.authorize_title') }}
            </h1>

            <p class="text-sm text-gray-600 mb-4">
                <strong>{{ $client->name }}</strong> {{ trans('oauth.requests_access') }}
            </p>
        </div>

        <!-- User Info -->
        <div class="mb-6 p-4 bg-gray-100 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if (setting('default.use_gravatar', '0') == '1')
                        <img src="{{ $user->picture }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full" title="{{ $user->name }}">
                    @elseif (is_object($user->picture))
                        <img src="{{ Storage::url($user->picture->id) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full" title="{{ $user->name }}">
                    @else
                        <img src="{{ asset('public/img/user.svg') }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full" title="{{ $user->name }}">
                    @endif
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        @if (count($companies) > 0)
            <form id="authorize" method="POST" action="{{ route('oauth.authorize.approve') }}">
                @csrf
                <input type="hidden" name="auth_token" value="{{ $authToken }}">

                @if (count($companies) > 1)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            {{ trans('oauth.select_company') }}
                        </label>

                        <!-- Scrollable company list (max 3 visible) -->
                        <div class="space-y-2 {{ count($companies) > 3 ? 'max-h-80 overflow-y-auto pr-2' : '' }}" id="company-selection" style="{{ count($companies) > 3 ? 'scrollbar-width: thin; scrollbar-color: #9333ea #f3f4f6;' : '' }}">
                            @foreach($companies as $companyId => $companyData)
                                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-purple-500 transition-colors company-option {{ $selectedCompanyId == $companyId ? 'border-purple-500 bg-purple-50' : 'border-gray-200 bg-white' }}">
                                    <input 
                                        type="radio" 
                                        name="company_id" 
                                        value="{{ $companyId }}" 
                                        required
                                        {{ $selectedCompanyId == $companyId ? 'checked' : '' }}
                                        class="hidden company-radio"
                                        onchange="updateCompanySelection(this)"
                                    >

                                    <div class="flex items-center flex-1">
                                        <!-- Company Logo -->
                                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center overflow-hidden">
                                            @if(!empty($companyData['logo']))
                                                <img src="{{ $companyData['logo'] }}" alt="{{ $companyData['name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Company Info -->
                                        <div class="ml-4 flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $companyData['name'] }}
                                            </p>
                                            @if(!empty($companyData['email']))
                                                <p class="text-xs text-gray-500">
                                                    {{ $companyData['email'] }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Checkmark -->
                                        <div class="flex-shrink-0">
                                            <div class="w-5 h-5 rounded-full checkmark {{ $selectedCompanyId == $companyId ? 'bg-purple-600' : 'bg-white border-2 border-gray-300' }} flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white {{ $selectedCompanyId == $companyId ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 12 12">
                                                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L5 6.586 3.707 5.293z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <!-- Custom scrollbar styles -->
                        @if(count($companies) > 3)
                            <style>
                                #company-selection::-webkit-scrollbar {
                                    width: 6px;
                                }
                                #company-selection::-webkit-scrollbar-track {
                                    background: #f3f4f6;
                                    border-radius: 3px;
                                }
                                #company-selection::-webkit-scrollbar-thumb {
                                    background: #9333ea;
                                    border-radius: 3px;
                                }
                                #company-selection::-webkit-scrollbar-thumb:hover {
                                    background: #7e22ce;
                                }
                            </style>
                        @endif

                        <p class="mt-2 text-xs text-gray-500">
                            {{ trans('oauth.company_selection_info') }}
                        </p>
                    </div>

                    <script>
                        function updateCompanySelection(radio) {
                            // Remove selection from all options
                            document.querySelectorAll('.company-option').forEach(option => {
                                option.classList.remove('border-purple-500', 'bg-purple-50');
                                option.classList.add('border-gray-200', 'bg-white');
                            });

                            // Remove checkmark from all
                            document.querySelectorAll('.checkmark').forEach(check => {
                                check.classList.remove('bg-purple-600');
                                check.classList.add('bg-white', 'border-2', 'border-gray-300');
                                check.querySelector('svg').classList.add('hidden');
                            });

                            // Add selection to clicked option
                            const label = radio.closest('.company-option');
                            label.classList.add('border-purple-500', 'bg-purple-50');
                            label.classList.remove('border-gray-200', 'bg-white');

                            // Show checkmark for selected
                            const checkmark = label.querySelector('.checkmark');
                            checkmark.classList.add('bg-purple-600');
                            checkmark.classList.remove('bg-white', 'border-2', 'border-gray-300');
                            checkmark.querySelector('svg').classList.remove('hidden');
                        }
                    </script>
                @else
                    <input type="hidden" name="company_id" value="{{ $selectedCompanyId }}">
                @endif

                <!-- Scopes -->
                @if (count($scopes) > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">
                            {{ trans('oauth.will_be_able_to') }}:
                        </h3>
                        <ul class="space-y-2">
                            @foreach ($scopes as $scope)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ is_object($scope) && isset($scope->description) ? $scope->description : $scope }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <button 
                        type="button"
                        onclick="window.history.back()"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors"
                    >
                        {{ trans('general.cancel') }}
                    </button>

                    <button
                        type="submit"
                        class="relative flex-1 inline-flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100 sm:col-span-6 transition-colors"
                        id="authorize-button"
                    >
                        {{ trans('oauth.authorize') }}
                    </button>
                </div>

                <!-- Client Redirect Info -->
                <div class="mt-6 pt-6 border-t border-gray-200 hidden">
                    <p class="text-xs text-gray-500 text-center">
                        {{ trans('oauth.redirect_info', ['url' => $client->redirect]) }}
                    </p>
                </div>
            </form>
        @endif
    </x-slot>

    <x-script folder="auth" file="common" />
</x-layouts.auth>
