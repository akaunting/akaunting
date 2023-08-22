<div class="sm:col-span-6">
    <div class="mb-4 p-0">
        <div class="overflow-y-hidden py-6">
            <table id="totals" class="float-right">
                <colgroup>
                    <col class="small-col" style="width: 47.5%;">
                    <col class="small-col" style="width: 30%;">
                    <col class="small-col" style="width: 18%;">
                    <col class="small-col" style="width: 50px;">
                </colgroup>

                <tbody id="invoice-total-rows">
                    @stack('sub_total_td_start')

                    <tr id="tr-subtotal">
                        <td class="border-b-0 py-0"></td>

                        <td class="font-medium ltr:text-right rtl:text-left border-r-0 border-b-0 align-middle pb-0 pr-0">
                            {{ trans('invoices.sub_total') }}
                        </td>

                        <td class="ltr:text-right rtl:text-left border-b-0 long-texts py-0">
                            <div>
                                <x-form.input.money
                                    name="sub_total"
                                    value="0"
                                    disabled
                                    row-input
                                    v-model="totals.sub"
                                    :currency="$currency"
                                    dynamicCurrency="currency"
                                    money-class="ltr:text-right rtl:text-left disabled-money px-0"
                                    form-group-class="ltr:text-right rtl:text-left disabled-money"
                                />
                            </div>
                        </td>

                        <td class="border-b-0 pb-0" style="width: 40px"></td>
                    </tr>

                    @stack('sub_total_td_end')

                    @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                        @stack('item_discount_td_start')

                        <tr id="tr-line-discount" v-if="totals.item_discount">
                            <td class="border-t-0 py-0"></td>

                            <td class="ltr:text-right rtl:text-left border-t-0 border-r-0 border-b-0 align-middle py-0 pr-0">
                                <span class="font-medium">{{ trans('invoices.item_discount') }}</span>
                            </td>

                            <td class="ltr:text-right rtl:text-left border-t-0 border-b-0 long-texts py-0 pr-0">
                                <div>
                                    <x-form.input.money
                                        name="item_discount"
                                        value="0"
                                        disabled
                                        row-input
                                        v-model="totals.item_discount"
                                        :currency="$currency"
                                        dynamicCurrency="currency"
                                        money-class="ltr:text-right rtl:text-left disabled-money px-0"
                                        form-group-class="ltr:text-right rtl:text-left disabled-money"
                                    />
                                </div>
                            </td>

                            <td class="border-t-0 py-0" style="max-width: 24px"></td>
                        </tr>

                        @stack('item_discount_td_end')
                    @endif

                    @if (in_array(setting('localisation.discount_location', 'total'), ['total', 'both']))
                        @stack('add_discount_td_start')

                        <tr id="tr-discount">
                            <td class="border-t-0 py-0"></td>

                            <td class="ltr:text-right rtl:text-left border-t-0 border-r-0 border-b-0 align-middle py-0 pr-0">
                                <div v-if="show_discount_text" v-if="!totals.discount_text" @click="onAddDiscount()">
                                    <x-button.hover color="to-purple">
                                        {{ trans('invoices.add_discount') }}
                                    </x-button.hover>
                                </div>

                                <span v-if="totals.discount_text" v-html="totals.discount_text"></span>

                                <div class="flex items-center justify-end" v-if="show_discount">
                                    <div class="w-16 flex items-center bg-gray-200 p-1 ltr:mr-2 rtl:ml-2 rounded-lg">
                                        <button type="button"
                                            class="w-7 flex justify-center px-2"
                                            :class="[{'btn-outline-primary' : form.discount_type !== 'percentage'}, {'bg-white rounded-lg' : form.discount_type === 'percentage'}]"
                                            @click="onChangeDiscountType('percentage')"
                                        >
                                            <span class="material-icons text-lg">percent</span>
                                        </button>

                                        <button type="button"
                                            class="w-7 flex text-lg justify-center px-2"
                                            :class="[{'btn-outline-primary' : form.discount_type !== 'fixed'}, {'bg-white rounded-lg' : form.discount_type === 'fixed'}]"
                                            @click="onChangeDiscountType('fixed')"
                                        >
                                            {{ $currency->symbol }}
                                        </button>
                                    </div>

                                    <x-form.group.text name="pre_discount" id="pre-discount" form-group-class="-mt-1" v-model="form.discount" @input="onAddTotalDiscount" />
                                </div>
                            </td>

                            <td class="relative ltr:text-right rtl:text-left border-t-0 border-b-0 py-0 pr-0">
                                <div>
                                    <x-form.input.money
                                        name="discount_total"
                                        value="0"
                                        disabled
                                        row-input
                                        v-model="totals.discount"
                                        :currency="$currency"
                                        dynamicCurrency="currency"
                                        money-class="ltr:text-right rtl:text-left disabled-money px-0"
                                        form-group-class="ltr:text-right rtl:text-left disabled-money"
                                    />
                                </div>

                                <x-form.input.hidden name="discount" v-model="form.discount" />

                                <span v-if="delete_discount" @click="onRemoveDiscountArea()" class="material-icons-outlined absolute w-6 h-7 flex justify-center ltr:-right-10 rtl:-left-10 top-2 text-lg text-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 hover:text-gray-500">delete</span>
                            </td>

                            <td class="border-t-0 py-0" style="max-width: 50px"></td>
                        </tr>

                        @stack('add_discount_td_end')
                    @endif

                    @stack('tax_total_td_start')

                    <tr v-for="(tax, tax_index) in totals.taxes" :index="tax_index">
                        <td class="border-t-0 pt-5 pb-0"></td>

                        <td class="ltr:text-right rtl:text-left border-t-0  border-r-0 border-b-0 align-middle pt-5 pb-0 pr-0">
                            <span class="font-medium" v-html="tax.name"></span>
                        </td>

                        <td class="ltr:text-right rtl:text-left border-t-0 border-b-0 long-texts pt-5 pb-0 pl-3">
                            <div>
                                <x-form.input.money
                                    name="tax_total"
                                    value="0"
                                    disabled
                                    row-input
                                    v-model="tax.total"
                                    :currency="$currency"
                                    dynamicCurrency="currency"
                                    money-class="ltr:text-right rtl:text-left disabled-money px-0"
                                    form-group-class="ltr:text-right rtl:text-left disabled-money"
                                />
                            </div>
                        </td>

                        <td class="border-t-0 pt-5 pb-0" style="max-width: 50px"></td>
                    </tr>

                    @stack('tax_total_td_end')

                    @stack('grand_total_td_start')

                    <tr id="tr-total">
                        <td class="border-t-0 pt-5 pb-0"></td>

                        <td class="flex items-center justify-end pt-5 pb-0">
                            <span class="w-16 ltr:text-right rtl:text-left font-medium mt-2 ltr:mr-2 rtl:ml-2">
                                {{ trans('invoices.total') }}
                            </span>

                            <x-form.group.select
                                name="currency_code"
                                :options="$currencies"
                                selected="{{ $currency->code }}"
                                change="onChangeCurrency"
                                model="form.currency_code"
                                add-new
                                add-new-text="{!! trans('general.title.new', ['type' => trans_choice('general.currencies', 1)]) !!}"
                                :path="route('modals.currencies.create')"
                                :field="[
                                    'key' => 'code',
                                    'value' => 'name'
                                ]"
                                form-group-class="h-8 -mt-2"
                            />

                            <x-form.input.hidden name="currency_rate" :value="(!empty($document)) ? $document->currency_rate : $currency->rate" />
                        </td>

                        <td class="ltr:text-right rtl:text-left border-t-0 long-texts pt-5 pb-0 pr-0">
                            <div>
                                <x-form.input.money
                                    name="grand_total"
                                    value="0"
                                    disabled
                                    row-input
                                    v-model="totals.total"
                                    :currency="$currency"
                                    dynamicCurrency="currency"
                                    money-class="ltr:text-right rtl:text-left disabled-money px-0"
                                    form-group-class="ltr:text-right rtl:text-left disabled-money"
                                />
                            </div>
                        </td>

                        <td class="border-t-0 pt-5 pb-0" style="max-width: 50px"></td>
                    </tr>

                    @stack('grand_total_td_end')

                    @stack('currency_conversion_td_start')

                    <tr id="tr-currency-conversion" :class="[
                        {'hidden': ! (('{{ $currency->code }}' != form.currency_code) && totals.total || dropdown_visible)},
                        {'contents': (('{{ $currency->code }}' != form.currency_code) && totals.total || dropdown_visible)}
                    ]">
                        <td class="border-t-0 pt-5 pb-0"></td>

                        <td colspan="2" class="ltr:text-right rtl:text-left border-t-0 border-r-0 align-middle pt-5 pb-0 pr-0">
                            <akaunting-currency-conversion
                                currency-conversion-text="{{ trans('currencies.conversion') }}"
                                :price="(totals.total / form.currency_rate).toFixed(2)"
                                :currecy-code="form.currency_code"
                                :currency-rate="form.currency_rate"
                                :currency-symbol="currency_symbol"
                                @change="form.currency_rate = $event"
                            ></akaunting-currency-conversion>
                        </td>

                        <td class="border-t-0 pt-5 pb-0" style="max-width: 50px"></td>
                    </tr>

                    @stack('currency_conversion_td_end')
                </tbody>
            </table>
        </div>
    </div>
</div>
