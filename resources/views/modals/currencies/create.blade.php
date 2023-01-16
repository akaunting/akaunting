<x-form id="form-create-currency" route="modals.currencies.store">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.text name="name" label="{{ trans('general.name') }}" form-group-class="col-span-6" />

        <x-form.group.select name="code" label="{{ trans('currencies.code') }}" :options="$codes" form-group-class="col-span-6" />

        <x-form.group.text name="rate" label="{{ trans('currencies.rate') }}" @input="onChangeRate" form-group-class="col-span-6" />

        <x-form.input.hidden name="enabled" value="1" />
        <x-form.input.hidden name="symbol_first" value="1" />
        <x-form.input.hidden name="default_currency" value="0" />
    </div>
</x-form>
