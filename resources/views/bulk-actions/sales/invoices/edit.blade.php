<div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
    <x-form.group.contact
        type="customer"
        @option="onChangeContactCard($event.option)"
        without-add-new
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.category
        type="income"
        without-add-new
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />

    <x-form.group.currency
        without-add-new
        selected=""
        not-required
        form-group-class="sm:col-span-6"
    />
</div>
