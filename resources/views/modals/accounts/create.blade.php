<x-form id="form-create-account" route="accounts.store">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.radio
            name="type"
            label="{{ trans_choice('general.types', 1) }}"
            :options="[
                'bank' => trans_choice('accounts.banks', 1),
                'credit_card' => trans_choice('accounts.credit_cards', 1),
            ]"
            checked="bank"
        />

        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

        <x-form.group.text name="number" label="{{ trans('accounts.number') }}" />

        <x-form.group.currency without-add-new />

        <x-form.group.money
            name="opening_balance"
            label="{{ trans('accounts.opening_balance') }}"
            value="0"
            autofocus="autofocus"
            :currency="$currency"
            dynamicCurrency="currency"
        />

        <x-form.input.hidden name="enabled" value="1" />
    </div>
</x-form>
