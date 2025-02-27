@if ($attributes->has('withSummary') || $attributes->has('with-summary'))
<div class="relative {{ $formGroupClass }}">
@endif

@if ((! $attributes->has('withoutRemote') && ! $attributes->has('without-remote')) && (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new')))
    <x-form.group.select
        remote
        remote_action="{{ $remoteAction }}"

        add-new
        path="{{ $path }}"
        :field="$field"

        name="{{ $name }}"
        label="{!! trans_choice('general.taxes', 1) !!}"
        :options="$taxes"
        :selected="$selected"
        change="{{ $change }}"
        sort-options="false"
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    >
        <template #option="{option}">
            <span class="tax-group flex items-center">
                <span class="float-left">
                    @{{ option.option.title }}
                </span>

                <span 
                    class="inline-flex items-center h-5 rounded-md bg-gray-50 font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                    style="width: auto; font-size: 0.56rem;"
                >
                    <template v-if="option.option.type == 'normal'">
                        {{ trans('taxes.normal') }}
                    </template>
                    <template v-if="option.option.type == 'fixed'">
                        {{ trans('taxes.fixed') }}
                    </template>
                    <template v-if="option.option.type == 'inclusive'">
                        {{ trans('taxes.inclusive') }}
                    </template>
                    <template v-if="option.option.type == 'withholding'">
                        {{ trans('taxes.withholding') }}
                    </template>
                    <template v-if="option.option.type == 'compound'">
                        {{ trans('taxes.compound') }}
                    </template>
                </span>
            </span>
        </template>
    </x-form.group.select>
@elseif (($attributes->has('withoutRemote') && $attributes->has('without-remote')) && (! $attributes->has('withoutAddNew') && ! $attributes->has('without-add-new')))
    <x-form.group.select
        add-new
        path="{{ $path }}"
        :field="$field"

        name="{{ $name }}"
        label="{!! trans_choice('general.taxes', 1) !!}"
        :options="$taxes"
        :selected="$selected"
        change="{{ $change }}"
        sort-options="false"
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    >
        <template #option="{option}">
            <span class="tax-group flex items-center">
                <span class="float-left">
                    @{{ option.option.title }}
                </span>

                <span 
                    class="inline-flex items-center h-5 rounded-md bg-gray-50 font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                    style="width: auto; font-size: 0.56rem;"
                >
                    <template v-if="option.option.type == 'normal'">
                        {{ trans('taxes.normal') }}
                    </template>
                    <template v-if="option.option.type == 'fixed'">
                        {{ trans('taxes.fixed') }}
                    </template>
                    <template v-if="option.option.type == 'inclusive'">
                        {{ trans('taxes.inclusive') }}
                    </template>
                    <template v-if="option.option.type == 'withholding'">
                        {{ trans('taxes.withholding') }}
                    </template>
                    <template v-if="option.option.type == 'compound'">
                        {{ trans('taxes.compound') }}
                    </template>
                </span>
            </span>
        </template>
    </x-form.group.select>
@elseif ((! $attributes->has('withoutRemote') && ! $attributes->has('without-remote')) && ($attributes->has('withoutAddNew') && $attributes->has('without-add-new')))
    <x-form.group.select
        remote
        remote_action="{{ $remoteAction }}"
        :field="$field"

        name="{{ $name }}"
        label="{!! trans_choice('general.taxes', 1) !!}"
        :options="$taxes"
        :selected="$selected"
        change="{{ $change }}"
        sort-options="false"
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    >
        <template #option="{option}">
            <span class="tax-group flex items-center">
                <span class="float-left">
                    @{{ option.option.title }}
                </span>

                <span 
                    class="inline-flex items-center h-5 rounded-md bg-gray-50 font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                    style="width: auto; font-size: 0.56rem;"
                >
                    <template v-if="option.option.type == 'normal'">
                        {{ trans('taxes.normal') }}
                    </template>
                    <template v-if="option.option.type == 'fixed'">
                        {{ trans('taxes.fixed') }}
                    </template>
                    <template v-if="option.option.type == 'inclusive'">
                        {{ trans('taxes.inclusive') }}
                    </template>
                    <template v-if="option.option.type == 'withholding'">
                        {{ trans('taxes.withholding') }}
                    </template>
                    <template v-if="option.option.type == 'compound'">
                        {{ trans('taxes.compound') }}
                    </template>
                </span>
            </span>
        </template>
    </x-form.group.select>
@else
    <x-form.group.select
        name="{{ $name }}"
        label="{!! trans_choice('general.taxes', 1) !!}"
        :options="$taxes"
        :selected="$selected"
        change="{{ $change }}"
        sort-options="false"
        :field="$field"
        :option_field="[
            'key' => 'id',
            'value' => 'title'
        ]"

        :multiple="$multiple"
        :group="$group"
        form-group-class="{{ $formGroupClass }}"
        :required="$required"
        :readonly="$readonly"
        :disabled="$disabled"

        {{ $attributes }}
    >
        <template #option="{option}">
            <span class="tax-group flex items-center">
                <span class="float-left">
                    @{{ option.option.title }}
                </span>

                <span 
                    class="inline-flex items-center h-5 rounded-md bg-gray-50 font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                    style="width: auto; font-size: 0.56rem;"
                >
                    <template v-if="option.option.type == 'normal'">
                        {{ trans('taxes.normal') }}
                    </template>
                    <template v-if="option.option.type == 'fixed'">
                        {{ trans('taxes.fixed') }}
                    </template>
                    <template v-if="option.option.type == 'inclusive'">
                        {{ trans('taxes.inclusive') }}
                    </template>
                    <template v-if="option.option.type == 'withholding'">
                        {{ trans('taxes.withholding') }}
                    </template>
                    <template v-if="option.option.type == 'compound'">
                        {{ trans('taxes.compound') }}
                    </template>
                </span>
            </span>
        </template>
    </x-form.group.select>
@endif

@if ($attributes->has('withSummary') || $attributes->has('with-summary'))
    <div class="mt-2 text-sm" v-if="tax_summary">
        <div class="flex items-start" v-if="tax_summary_total">
            <div class="ltr:mr-2 rtl:ml-2">
                {{ trans('transactions.included_tax') }}:
            </div>

            <div>
                <x-form.input.money
                    name="tax_summary_total"
                    value="0"
                    disabled
                    row-input
                    v-model="tax_summary_total"
                    :currency="$attributes['currency']"
                    dynamicCurrency="currency"
                    money-class="p-0 m-0 ltr:text-left rtl:text-right disabled-money px-0"
                    form-group-class="ltr:text-right rtl:text-left disabled-money"
                />
            </div>
        </div>
        <div v-else v-html="tax_summary_html"></div>
    </div>
</div>
@endif
