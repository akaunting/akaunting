
<x-form id="form-create-tax" route="taxes.store">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

        <x-form.group.text name="rate" label="{{ trans('taxes.rate') }}" />

        <x-form.input.hidden name="type" value="normal" />
        <x-form.input.hidden name="enabled" value="1" />
    </div>
</x-form>
