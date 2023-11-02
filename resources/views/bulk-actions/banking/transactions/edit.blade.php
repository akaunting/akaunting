<div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
    <x-form.group.payment-method
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.account
        without-add-new
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />

    @if ($category_and_contact)
    <x-form.group.category
        :type="$real_type"
        without-add-new
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.contact
        :type="$contact_type"
        without-add-new
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />
    @endif

    <x-form.input.hidden name="currency_code" :value="$account_currency_code" />
    <x-form.input.hidden name="currency_rate" value="1" />
</div>
