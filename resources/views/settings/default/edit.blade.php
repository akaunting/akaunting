<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.defaults', 1) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" route="settings.default.update">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('settings.default.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.account name="account" not-required without-change />

                        <x-form.group.currency name="currency" not-required />

                        <x-form.group.select name="tax" label="{{ trans_choice('general.taxes', 1) }}" :options="$taxes" :selected="setting('default.tax')" not-required />

                        <x-form.group.payment-method not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.categories', 1) }}" description="{{ trans('settings.default.form_description.category') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select remote name="income_category" label="{{ trans('settings.default.income_category') }}" :options="$sales_categories" :selected="setting('default.income_category')" remote_action="{{ route('categories.index'). '?search=type:income enabled:1' }}" sort-options="false" />

                        <x-form.group.select remote name="expense_category" label="{{ trans('settings.default.expense_category') }}" :options="$purchases_categories" :selected="setting('default.expense_category')" remote_action="{{ route('categories.index'). '?search=type:expense enabled:1' }}" sort-options="false" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.others', 1) }}" description="{{ trans('settings.default.form_description.other') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.locale not-required />

                        <x-form.group.select name="list_limit" label="{{ trans('settings.default.list_limit') }}" :options="['10' => '10', '25' => '25', '50' => '50', '100' => '100']" :selected="setting('default.list_limit')" not-required />
                    </x-slot>
                </x-form.section>

                @can('update-settings-defaults')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
                @endcan

                <x-form.input.hidden name="_prefix" value="default" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
