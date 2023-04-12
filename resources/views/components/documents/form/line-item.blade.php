<tbody is="draggable" tag="tbody" handle=".handle" @start="dragging = true" @end="dragging = false" @update="onItemSortUpdate">
    <tr v-for="(row, index) in items" :index="index">
        @stack('name_td_start')

        <td class="border-r-0 border-b-0 p-0"
            :class="[{'has-error': form.errors.has('items.' + index + '.name') }]"
            colspan="7">
            <table class="w-full border-b pb-3">
                <colgroup>
                    <col class="small-col" style="width: 24px;">
                    <col class="small-col" style="width: 20%;">
                    <col class="small-col description-col" style="width: 30%;">
                    <col class="small-col" style="width: 12%;">
                    <col class="small-col" style="width: 15%;">
                    <col class="small-col amount-col" style="width: 20%;">
                    <col class="small-col" style="width: 24px;">
                </colgroup>

                <tbody>
                    <tr>
                        @stack('move_td_start')

                        <td class="align-middle border-b-0 flex items-center justify-center" style="width:24px; height:100px; color: #8898aa;">
                            <div class="handle mt-2 hidden lg:block cursor-move">
                                <span class="w-6 material-icons">list</span>
                            </div>
                        </td>

                        @stack('move_td_end')

                        @stack('items_td_start')

                        @if (! $hideItems || (! $hideItemName && ! $hideItemDescription))
                            @stack('name_td_start')

                            <td class="px-3 py-3 ltr:pl-2 rtl:pr-2 ltr:text-left rtl:text-right align-middle border-b-0 name">
                                @if (! $hideItemName)
                                    <span class="flex items-center text-sm" tabindex="0" v-if="row.item_id">
                                        <div v-html="row.name"></div>
                                    </span>

                                    <div v-else>
                                        @stack('name_input_start')

                                        <input
                                            type="text"
                                            :ref="'items-' + index + '-name'"
                                            class="w-full text-sm px-3 py-2.5 mt-0 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple mt-0"
                                            :name="'items.' + index + '.name'"
                                            autocomplete="off"
                                            required="required"
                                            data-item="name"
                                            v-model="row.name"
                                            @input="onBindingItemField(index, 'name')"
                                            @change="form.errors.clear('items.' + index + '.name')"
                                        />

                                        <div class="text-red text-sm mt-1 block"
                                            v-if="form.errors.has('items.' + index + '.name')"
                                            v-html="form.errors.get('items.' + index + '.name')"
                                        ></div>

                                        @stack('name_input_end')
                                    </div>
                                @endif
                            </td>

                            @stack('name_td_end')

                            @stack('description_td_start')

                            <td class="px-3 py-3 border-b-0 description">
                                @if (! $hideItemDescription)
                                    <textarea
                                        class="w-full text-sm px-3 py-2.5 mt-1.5 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                                        style="height:42px;"
                                        :ref="'items-' + index + '-description'"
                                        placeholder="{{ trans('items.enter_item_description') }}"
                                        :name="'items.' + index + '.description'"
                                        v-model="row.description"
                                        data-item="description"
                                        resize="none"
                                        @input="onBindingItemField(index, 'description')"
                                    ></textarea>
                                @endif
                            </td>

                            @stack('description_td_end')
                        @endif

                        @stack('items_td_end')

                        @stack('quantity_td_start')

                        <td class="px-3 py-3 border-b-0 quantity">
                            @if (! $hideItemQuantity)
                                <div>
                                    @stack('quantity_input_start')

                                    <input
                                        type="number"
                                        min="0"
                                        :ref="'items-' + index + '-quantity'"
                                        class="w-full text-sm px-3 py-2.5 mt-0 text-right rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple input-number-disabled"
                                        :name="'items.' + index + '.quantity'"
                                        autocomplete="off"
                                        required="required"
                                        data-item="quantity"
                                        v-model="row.quantity"
                                        @input="onCalculateTotal"
                                        @change="form.errors.clear('items.' + index + '.quantity')"
                                    />

                                    <div class="text-red text-sm mt-1 block"
                                        v-if="form.errors.has('items.' + index + '.quantity')"
                                        v-html="form.errors.get('items.' + index + '.quantity')">
                                    </div>

                                    @stack('quantity_input_end')
                                </div>
                            @endif
                        </td>

                        @stack('quantity_td_end')

                        @stack('price_td_start')

                        <td class="px-3 py-3 pr-1 border-b-0 price">
                            <div>
                                @stack('price_input_start')

                                <x-form.input.money
                                    name="price"
                                    value="0"
                                    row-input
                                    data-item="price"
                                    v-model="row.price"
                                    v-error="form.errors.get('items.' + index + '.price')"
                                    v-error-message="form.errors.get('items.' + index + '.price')"
                                    change="row.price = $event; form.errors.clear('items.' + index + '.price'); onCalculateTotal"
                                    :currency="$currency"
                                    dynamicCurrency="currency"
                                    money-class="text-right mt-0"
                                    form-group-class="text-right"
                                />

                                @stack('price_input_end')
                            </div>
                        </td>

                        @stack('price_td_end')

                        @stack('total_td_start')

                        <td class="px-3 py-3 text-right border-b-0 total">
                            @if (! $hideItemAmount)
                                <div>
                                    <x-form.input.money
                                        name="total"
                                        value="0"
                                        disabled
                                        row-input
                                        data-item="total"
                                        v-model="row.total"
                                        :currency="$currency"
                                        dynamicCurrency="currency"
                                        money-class="ltr:text-right rtl:text-left mt-0 disabled-money px-0"
                                        form-group-class="ltr:text-right rtl:text-left disabled-money"
                                    />
                                </div>
                            @endif
                        </td>

                        @stack('total_td_end')

                        @stack('delete_td_start')

                        <td class="text-right group">
                            <button type="button" @click="onDeleteItem(index)" class="w-6 h-7 flex items-center rounded-lg p-0 group-hover:bg-gray-100">
                                <span class="w-full material-icons-outlined text-lg text-gray-300 group-hover:text-gray-500">delete</span>
                            </button>
                        </td>

                        @stack('delete_td_end')
                    </tr>

                    <tr>
                        <td colspan="3">
                            @stack('item_custom_fields')
                        </td>

                        <td colspan="4" class="px-0 pb-3">
                            <div class="relative">
                                <div class="absolute -top-6 ltr:left-3 rtl:right-3 flex items-center">
                                    @if (! $hideDiscount && in_array(setting('localisation.discount_location'), ['item', 'both']))
                                        <div class="text-left border-0 p-0 mr-16" v-if="!row.add_discount">
                                            <x-button type="button" class="text-xs text-purple" @click="onAddLineDiscount(index)" override="class">
                                                <x-button.hover color="to-purple">
                                                    {{ trans('general.title.add', ['type' => trans('invoices.discount')]) }}
                                                </x-button.hover>
                                            </x-button>
                                        </div>
                                    @endif

                                    <div class="text-right border-0 p-0 pr-4">
                                        <x-button type="button" class="text-xs text-purple" @click="onAddTax(index)" override="class">
                                            <x-button.hover color="to-purple">
                                                {{ trans('general.title.add', ['type' => trans_choice('general.taxes', 1)]) }}
                                            </x-button.hover>
                                        </x-button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="row.add_discount" class="flex items-center justify-between pb-3 ml-2">
                                @stack('discount_input_start')

                                <div class="mb-0" style="display: inline-block; position: relative;">
                                    <div class="flex items-center">
                                        <div class="w-16 flex items-center bg-gray-200 p-1 ltr:mr-2 rtl:ml-2 rounded-lg">
                                            <button type="button"
                                                class="w-7 flex justify-center px-2"
                                                :class="[{'btn-outline-primary' : row.discount_type !== 'percentage'}, {'bg-white rounded-lg' : row.discount_type === 'percentage'}]"
                                                @click="onChangeLineDiscountType(index, 'percentage')"
                                            >
                                                <span class="material-icons text-lg">percent</span>
                                            </button>

                                            <button type="button"
                                                class="w-7 px-2"
                                                :class="[{'btn-outline-primary' : row.discount_type !== 'fixed'}, {'bg-white rounded-lg' : row.discount_type === 'fixed'}]"
                                                @click="onChangeLineDiscountType(index, 'fixed')"
                                            >
                                                <span class="text-base">{{ $currency->symbol }}</span>
                                            </button>
                                        </div>

                                        <input type="number"
                                            min="0"
                                            placeholder="Discount"
                                            class="w-full text-sm px-3 py-2.5 mt-0 text-center rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                                            :name="'items.' + index + '.discount'"
                                            autocomplete="off"
                                            required="required"
                                            data-item="discount"
                                            v-model="row.discount"
                                            @input="onCalculateTotal"
                                            @change="form.errors.clear('items.' + index + '.discount')"
                                        />

                                        <div class="text-red text-sm mt-1 block"
                                            v-if="form.errors.has('items.' + index + '.discount')"
                                            v-html="form.errors.get('items.' + index + '.discount')">
                                        </div>
                                    </div>
                                </div>

                                @stack('discount_input_end')

                                <div class="flex items-center lg:absolute ltr:right-0 rtl:left-0">
                                    <div class="text-right">
                                        <x-form.input.money
                                            name="discount_amount"
                                            value="0"
                                            disabled
                                            row-input
                                            data-item="discount_amount"
                                            v-model="row.discount_amount"
                                            :currency="$currency"
                                            dynamicCurrency="currency"
                                            money-class="ltr:text-right rtl:text-left disabled-money px-0"
                                            form-group-class="ltr:text-right rtl:text-left disabled-money"
                                        />
                                    </div>

                                    <div class="ltr:pl-2 rtl:pr-2 group">
                                        <button type="button" @click="onDeleteDiscount(index)" class="w-6 h-7 flex items-center rounded-lg p-0 group-hover:bg-gray-100">
                                            <span class="w-full material-icons-outlined text-lg text-gray-300 group-hover:text-gray-500">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between h-10 ml-3 my-3" v-for="(row_tax, row_tax_index) in row.tax_ids"
                                :index="row_tax_index"
                            >
                                <span class="absolute text-sm ltr:right-1/2 rtl:left-1/2 ltr:-ml-7 rtl:-mr-7">{{ trans_choice('general.taxes', 1) }}</span>

                                <div class="lg:w-1/4 lg:absolute">
                                    @stack('taxes_input_start')

                                    <akaunting-select
                                        class="mb-0 select-tax"
                                        :form-classes="[{'has-error': form.errors.has('items.' + index + '.taxes') }]"
                                        :icon="''"
                                        :title="''"
                                        :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}'"
                                        :name="'items.' + index + '.taxes.' + row_tax_index"
                                        :options="{{ json_encode($taxes->pluck('title', 'id')) }}"
                                        :dynamic-options="dynamic_taxes"
                                        :disabled-options="form.items[index].tax_ids"
                                        :value="row_tax.id"
                                        :add-new="{{ json_encode([
                                            'status' => true,
                                            'text' => trans('general.title.new', ['type' => trans_choice('general.taxes', 1)]),
                                            'path' => route('modals.taxes.create'),
                                            'type' => 'modal',
                                            'field' => [
                                                'key' => 'id',
                                                'value' => 'title'
                                            ],
                                            'new_text' => trans('modules.new'),
                                            'buttons' => [
                                                'cancel' => [
                                                    'text' => trans('general.cancel'),
                                                    'class' => 'btn-outline-secondary'
                                                ],
                                                'confirm' => [
                                                    'text' => trans('general.save'),
                                                    'class' => 'disabled:bg-green-100'
                                                ]
                                            ]
                                        ])}}"
                                        @interface="row_tax.id = $event"
                                        @change="onCalculateTotal()"
                                        @new="dynamic_taxes.push($event)"
                                        :form-error="form.errors.get('items.' + index + '.taxes')"
                                        :no-data-text="'{{ trans('general.no_data') }}'"
                                        :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
                                    ></akaunting-select>

                                    @stack('taxes_input_end')
                                </div>

                                <div class="flex items-center lg:absolute ltr:right-0 rtl:left-0">
                                    <div class="text-right">
                                        <x-form.input.money
                                            name="tax"
                                            value="0"
                                            disabled
                                            row-input
                                            data-item="total"
                                            v-model="row_tax.price"
                                            :currency="$currency"
                                            dynamicCurrency="currency"
                                            money-class="text-right disabled-money px-0"
                                            form-group-class="text-right disabled-money"
                                        />
                                    </div>

                                    <div class="ltr:pl-2 rtl:pr-2 group">
                                        <button type="button" @click="onDeleteTax(index, row_tax_index)" class="w-6 h-7 flex items-center rounded-lg p-0 group-hover:bg-gray-100">
                                            <span class="w-full material-icons-outlined text-lg text-gray-300 group-hover:text-gray-500">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="row.add_tax" class="flex items-center justify-between h-10 ml-3 my-3" :class="{'pt-2' : row.add_discount}">
                                <span class="absolute text-sm ltr:right-1/2 rtl:left-1/2 ltr:-ml-7 rtl:-mr-7">{{ trans_choice('general.taxes', 1) }}</span>

                                <div class="lg:w-1/4 lg:absolute">
                                    @stack('taxes_input_start')

                                    <akaunting-select
                                        class="mb-0 select-tax"
                                        style="margin-left: 1px; margin-right: -2px;"
                                        :form-classes="[{'has-error': form.errors.has('items.' + index + '.taxes') }]"
                                        :icon="''"
                                        :title="''"
                                        :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}'"
                                        :name="'items.' + index + '.taxes.999'"
                                        :options="{{ json_encode($taxes->pluck('title', 'id')) }}"
                                        :dynamic-options="dynamic_taxes"
                                        :disabled-options="form.items[index].tax_ids"
                                        :value="tax_id"
                                        :add-new="{{ json_encode([
                                            'status' => true,
                                            'text' => trans('general.title.new', ['type' => trans_choice('general.taxes', 1)]),
                                            'path' => route('modals.taxes.create'),
                                            'type' => 'modal',
                                            'field' => [
                                                'key' => 'id',
                                                'value' => 'title'
                                            ],
                                            'new_text' => trans('modules.new'),
                                            'buttons' => [
                                                'cancel' => [
                                                    'text' => trans('general.cancel'),
                                                    'class' => 'btn-outline-secondary'
                                                ],
                                                'confirm' => [
                                                    'text' => trans('general.save'),
                                                    'class' => 'disabled:bg-green-100'
                                                ]
                                            ]
                                        ])}}"
                                        @interface="tax_id = $event"
                                        @visible-change="onSelectedTax(index)"
                                        @new="dynamic_taxes.push($event)"
                                        :form-error="form.errors.get('items.' + index + '.taxes')"
                                        :no-data-text="'{{ trans('general.no_data') }}'"
                                        :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
                                    ></akaunting-select>

                                    @stack('taxes_input_end')
                                </div>

                                <div class="flex items-center lg:absolute ltr:right-0 rtl:left-0">
                                    <div class="text-right">
                                        <div class="required disabled text-right input-price disabled-money">
                                            <input type="tel" class="v-money form-control ltr:text-right rtl:text-left" name="discount_amount" disabled="disabled" value="__">
                                        </div>
                                    </div>

                                    <div class="ltr:pl-2 rtl:pr-2 group">
                                        <button type="button" @click="onDeleteTax(index, 999)" class="w-6 h-7 flex items-center rounded-lg p-0 group-hover:bg-gray-100">
                                            <span class="w-full material-icons-outlined text-lg text-gray-300 group-hover:text-gray-500">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>

        @stack('name_td_end')
    </tr>
</tbody>
