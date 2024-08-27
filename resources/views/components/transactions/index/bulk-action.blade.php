@props(['category_and_contact', 'real_type', 'contact_type', 'account_currency_code'])

@if (! $hidePaymentMethod || ! $hideAccount || $category_and_contact || ! $hideCategory || ! $hideContact)
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        @if (! $hidePaymentMethod)
            <x-form.group.payment-method
                selected=""
                not-required
                form-group-class="sm:col-span-6"
            />
        @endif

        @if (! $hideAccount)
            <x-form.group.account
                without-add-new
                selected=""
                not-required
                form-group-class="sm:col-span-6"
            />
        @endif

        @if ($category_and_contact)
            @if (! $hideCategory)
                <x-form.group.category
                    :type="$real_type"
                    without-add-new
                    selected=""
                    not-required
                    form-group-class="sm:col-span-6"
                />
            @endif

            @if (! $hideContact)
                <x-form.group.contact
                    :type="$contact_type"
                    without-add-new
                    selected=""
                    not-required
                    form-group-class="sm:col-span-6"
                />
            @endif
        @endif

        <x-form.input.hidden name="currency_code" :value="$account_currency_code" />
        <x-form.input.hidden name="currency_rate" value="1" />
    </div>
@endif
