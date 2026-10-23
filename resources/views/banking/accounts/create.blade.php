<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.accounts', 1)]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.accounts', 1)]) }}"
        icon="account_balance"
        route="accounts.create"
    ></x-slot>

    <x-slot name="content">
        @if (! $bank_feed_offer || request()->get('without_bank_feed', false))
            <x-form.container>
                <x-form id="account" route="accounts.store">
                    <x-form.section>
                        <x-slot name="head">
                            <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('accounts.form_description.general') }}" />
                        </x-slot>

                        <x-slot name="body">
                            <x-form.group.radio
                                name="type"
                                label="{{ trans_choice('general.types', 1) }}"
                                :options="[
                                    'bank' => trans_choice('accounts.banks', 1),
                                    'credit_card' => trans_choice('accounts.credit_cards', 1),
                                ]"
                                checked="bank"
                            />

                            <x-form.group.text name="name" label="{{ trans('general.name') }}" form-group-class="sm:col-span-6" />

                            <x-form.group.text name="number" label="{{ trans('accounts.number') }}" form-group-class="sm:col-span-6" />

                            <x-form.group.currency />

                            <x-form.group.money name="opening_balance" label="{{ trans('accounts.opening_balance') }}" value="0" :currency="$currency" dynamicCurrency="currency" />

                            <x-form.group.toggle name="default_account" label="{{ trans('accounts.default_account') }}" :value="false" show="form.type != 'credit_card'" />
                        </x-slot>
                    </x-form.section>

                    <x-form.section>
                        <x-slot name="head">
                            <x-form.section.head title="{{ trans_choice('accounts.banks', 1) }}" description="{{ trans('accounts.form_description.bank') }}" />
                        </x-slot>

                        <x-slot name="body">
                            {{-- Filled in where the page was reached from the bank search: the bank
                                 has just been named there, and asking for it again is asking twice --}}
                            <x-form.group.text
                                name="bank_name"
                                label="{{ trans('accounts.bank_name') }}"
                                value="{{ request('bank_name') }}"
                                not-required
                            />

                            <x-form.group.text name="bank_phone" label="{{ trans('accounts.bank_phone') }}" not-required />

                            <x-form.group.textarea name="bank_address" label="{{ trans('accounts.bank_address') }}" not-required />
                        </x-slot>
                    </x-form.section>

                    <x-form.section>
                        <x-slot name="foot">
                            <x-form.buttons cancel-route="accounts.index" />
                        </x-slot>
                    </x-form.section>
                </x-form>
            </x-form.container>
        @else
            <x-empty-page
                page="accounts"
                group="banking"
                title="{{ trans('accounts.bank_feed.title') }}"
                description="{{ trans('accounts.bank_feed.description') }}"
                :buttons="[
                    [
                        'url' => route('apps.app.show', 'bank-feeds'),
                        'permission' => 'read-modules-home',
                        'text' => trans('accounts.bank_feed.action'),
                        'description' => trans('accounts.bank_feed.action_description'),
                        'active_badge' => true,
                    ],
                    [
                        'url' => route('accounts.create', ['without_bank_feed' => 1]),
                        'permission' => 'create-banking-accounts',
                        'text' => trans('accounts.bank_feed.add_without'),
                        'description' => trans('accounts.bank_feed.add_without_description'),
                        'active_badge' => false,
                        'attributes' => ['@click.prevent' => 'onWithoutBankFeed'],
                    ],
                ]"
            />

            <akaunting-modal
                :show="without_bank_feed.modal"
                @cancel="closeWithoutBankFeed"
                title="{{ trans('accounts.bank_feed.add_without') }}"
            >
                <template #modal-body>
                    <div class="px-5 text-sm">
                        <div class="p-4 rounded-lg bg-lilac-100">
                            <p class="font-medium">
                                {{ trans('accounts.bank_feed.manual.bank_feeds') }}
                            </p>

                            <ul class="mt-2 space-y-1">
                                @foreach (['sync', 'match', 'reconcile'] as $benefit)
                                    <li class="flex items-start">
                                        <span class="material-icons text-base text-green ltr:mr-2 rtl:ml-2">check</span>
                                        {{ trans('accounts.bank_feed.benefits.' . $benefit) }}
                                    </li>
                                @endforeach
                            </ul>

                            @can('read-modules-home')
                                <x-link href="{{ route('apps.app.show', 'bank-feeds') }}" class="inline-block mt-3 font-medium text-purple hover:underline" override="class">
                                    {{ trans('accounts.bank_feed.action') }}
                                </x-link>
                            @endcan
                        </div>

                        <p class="mt-4">
                            {{ trans('accounts.bank_feed.manual.reasons') }}
                        </p>

                        <ul class="mt-2 ltr:pl-5 rtl:pr-5 list-disc space-y-1">
                            @foreach (['not_supported', 'cash', 'manual'] as $reason)
                                <li>{{ trans('accounts.bank_feed.manual.reason.' . $reason) }}</li>
                            @endforeach
                        </ul>

                        <p class="mt-4">
                            {{ trans('accounts.bank_feed.manual.import') }}
                        </p>
                    </div>
                </template>

                <template #card-footer>
                    <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                        <button type="button" class="px-6 py-1.5 hover:bg-gray-200 rounded-lg" @click="closeWithoutBankFeed">
                            {{ trans('general.cancel') }}
                        </button>

                        <x-link href="{{ route('accounts.create', ['without_bank_feed' => 1]) }}" class="px-6 py-1.5 bg-green hover:bg-green-700 text-white rounded-lg" override="class">
                            {{ trans('accounts.bank_feed.manual.continue') }}
                        </x-link>
                    </div>
                </template>
            </akaunting-modal>
        @endif
    </x-slot>

    @push('scripts_start')
        <script type="text/javascript">
            if (typeof aka_currency !== 'undefined') {
                aka_currency = {!! json_encode(! empty($currency) ? $currency : config('money.currencies.' . company()->currency)) !!};
            } else {
                var aka_currency = {!! json_encode(! empty($currency) ? $currency : config('money.currencies.' . company()->currency)) !!};
            }
        </script>
    @endpush

    <x-script folder="banking" file="accounts" />
</x-layouts.admin>
