<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.accounts', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="account" method="PATCH" :route="['accounts.update', $account->id]" :model="$account">
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
                            checked="{{ $account->type }}"
                        />

                        <x-form.group.text name="name" label="{{ trans('general.name') }}" form-group-class="sm:col-span-6" />

                        <x-form.group.text name="number" label="{{ trans('accounts.number') }}" form-group-class="sm:col-span-6" />

                        <x-form.group.currency />

                        <x-form.group.money name="opening_balance" label="{{ trans('accounts.opening_balance') }}" :value="$account->opening_balance" :currency="$currency" dynamicCurrency="currency" />

                        <x-form.group.toggle name="default_account" label="{{ trans('accounts.default_account') }}" :value="$account->default_account" show="form.type != 'credit_card'" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('accounts.banks', 1) }}" description="{{ trans('accounts.form_description.bank') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="bank_name" label="{{ trans('accounts.bank_name') }}" not-required />

                        <x-form.group.text name="bank_phone" label="{{ trans('accounts.bank_phone') }}" not-required />

                        <x-form.group.textarea name="bank_address" label="{{ trans('accounts.bank_address') }}" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                @can('update-banking-accounts')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="accounts.index" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    @push('scripts_start')
        <script type="text/javascript">
            if (typeof aka_currency !== 'undefined') {
                aka_currency = {!! json_encode(! empty($account) ? $account->currency : config('money.currencies.' . company()->currency)) !!};
            } else {
                var aka_currency = {!! json_encode(! empty($account) ? $account->currency : config('money.currencies.' . company()->currency)) !!};
            }
        </script>
    @endpush

    <x-script folder="banking" file="accounts" />
</x-layouts.admin>
