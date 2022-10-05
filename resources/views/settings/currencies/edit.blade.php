<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('general.currencies', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="currency" method="PATCH" :route="['currencies.update', $currency->id]" :model="$currency">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('currencies.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.select name="code" label="{{ trans('currencies.code') }}" :options="$codes" searchable change="onChangeCode" />

                        <x-form.group.text name="rate" label="{{ trans('currencies.rate') }}" @input="onChangeRate" ::disabled="form.default_currency == 1" />

                        <x-form.group.select name="precision" label="{{ trans('currencies.precision') }}" :options="$precisions" model="form.precision" />

                        <x-form.group.text name="symbol" label="{{ trans('currencies.symbol.symbol') }}" />

                        <x-form.group.select name="symbol_first" label="{{ trans('currencies.symbol.position') }}" :options="['1' => trans('currencies.symbol.before'), '0' => trans('currencies.symbol.after')]" not-required model="form.symbol_first" />

                        <x-form.group.text name="decimal_mark" label="{{ trans('currencies.decimal_mark') }}" />

                        <x-form.group.text name="thousands_separator" label="{{ trans('currencies.thousands_separator') }}" not-required />

                        <x-form.group.toggle name="default_currency" label="{{ trans('currencies.default') }}" :value="$currency->default_currency" :disabled="(default_currency() == $currency->code)" />
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                @can('update-settings-currencies')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="currencies.index" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="currencies" />
</x-layouts.admin>
