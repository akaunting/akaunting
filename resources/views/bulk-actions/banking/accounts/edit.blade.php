<div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
    <x-form.group.radio
        name="type"
        label="{{ trans_choice('general.types', 1) }}"
        :options="[
            'bank' => trans_choice('accounts.banks', 1),
            'credit_card' => trans_choice('accounts.credit_cards', 1),
        ]"
        not-required
        form-group-class="sm:col-span-6"
        input-group-class="grid grid-cols-2 gap-3 sm:grid-cols-2"
    />

    <x-form.group.currency
        without-add-new
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.text
        name="bank_name"
        label="{{ trans('accounts.bank_name') }}"
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.text
        name="bank_phone"
        label="{{ trans('accounts.bank_phone') }}"
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.textarea
        name="bank_address"
        label="{{ trans('accounts.bank_address') }}"
        not-required
        form-group-class="sm:col-span-6"
    />
</div>
