<tr v-for="(row, index) in items"
    :index="index">
    @stack('name_td_start')
        <td class="border-right-0 border-bottom-0 p-0"
            :class="[{'has-error': form.errors.has('items.' + index + '.name') }]" 
            colspan="7">
            <table class="w-100">
                <colgroup>
                    <col style="width: 40px;">
                    <col style="width: 25%;">
                    <col style="width: 30%;">
                    <col style="width: 100px;">
                    <col style="width: 100px;">
                    <col style="width: 250px;">
                    <col style="width: 40px;">
                </colgroup>
                <tbody>
                    <tr>
                        @stack('move_td_start')
                            <td class="pb-4 align-middle" colspan="1" style="color: #8898aa;">
                                <div draggable="true">
                                    <i class="fas fa-grip-vertical"></i>
                                </div>
                            </td>
                        @stack('move_td_end')

                        @stack('items_td_start')
                            @if (!$hideItems || (!$hideName && !$hideDescription))
                                @stack('name_td_start')
                                    @if (!$hideName)
                                        <td class="pb-4 align-middle" colspan="1">
                                            <span class="aka-text aka-text--body" tabindex="0" v-html="row.name"></span>
                                        </td>
                                    @endif
                                @stack('name_td_end')

                                @stack('description_td_start')
                                    @if (!$hideDescription)
                                        <td class="pb-4" colspan="1">
                                            <textarea 
                                                class="form-control"
                                                placeholder="Enter item description"
                                                style="height: 38px;"
                                                :name="'items.' + index + '.description'"
                                                v-model="row.description"
                                                data-item="description"
                                                resize="none"
                                            ></textarea>
                                        </td>
                                    @endif
                                @stack('description_td_end')
                            @endif
                        @stack('items_td_end')

                        @stack('quantity_td_start')
                            @if (!$hideQuantity)
                            <td colspan="1" class="pb-4" style="padding-right: 5px; padding-left: 5px;">
                                <div>
                                    @stack('quantity_input_start')
                                    <input type="text"
                                        class="form-control text-center p-0"
                                        :name="'items.' + index + '.quantity'"
                                        autocomplete="off"
                                        required="required"
                                        data-item="quantity"
                                        v-model="row.quantity"
                                        @input="onCalculateTotal"
                                        @change="form.errors.clear('items.' + index + '.quantity')">
                    
                                    <div class="invalid-feedback d-block"
                                        v-if="form.errors.has('items.' + index + '.quantity')"
                                        v-html="form.errors.get('items.' + index + '.quantity')">
                                    </div>
                                    @stack('quantity_input_end')
                                </div>
                            </td>
                            @endif
                        @stack('quantity_td_end')

                        @stack('price_td_start')
                            @if (!$hidePrice)
                                <td colspan="1" class="pb-4" style="padding-right: 5px; padding-left: 5px;">
                                    <div>
                                        @stack('price_input_start')
                                            {{ Form::moneyGroup('price', '', '', ['required' => 'required', 'row-input' => 'true', 'v-model' => 'row.price', 'v-error' => 'form.errors.get(\'items.\' + index + \'.price\')', 'v-error-message' => 'form.errors.get(\'items.\' + index + \'.price\')' , 'data-item' => 'price', 'currency' => $currency, 'dynamic-currency' => 'currency', 'change' => 'row.price = $event; form.errors.clear(\'items.\' + index + \'.price\'); onCalculateTotal'], 0.00, 'text-right input-price p-0') }}
                                        @stack('price_input_end')
                                    </div>
                                </td>
                            @endif
                        @stack('price_td_end')

                        @if (!$hideDiscount)
                            @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                @stack('discount_td_start')
                                    <td colspan="1" class="pb-4"
                                        :class="[{'has-error': form.errors.has('items.' + index + '.discount') }]">
                                        @stack('discount_input_start')
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="input-discount">
                                                    <i class="fa fa-percent"></i>
                                                </span>
                                            </div>
                            
                                            <input type="number"
                                                max="100"
                                                min="0"
                                                class="form-control text-center p-0"
                                                :name="'items.' + index + '.discount'"
                                                autocomplete="off"
                                                required="required"
                                                data-item="discount"
                                                v-model="row.discount"
                                                @input="onCalculateTotal"
                                                @change="form.errors.clear('items.' + index + '.discount')">
                            
                                            <div class="invalid-feedback d-block"
                                                v-if="form.errors.has('items.' + index + '.discount')"
                                                v-html="form.errors.get('items.' + index + '.discount')">
                                            </div>
                                        </div>
                                        @stack('discount_input_end')
                                    </td>
                                @stack('discount_td_end')
                            @endif
                        @endif

                        @stack('total_td_start')
                            @if (!$hideAmount)
                                <td colspan="1" class="text-right long-texts pb-4">
                                    <div>
                                        {{ Form::moneyGroup('total', '', '', ['required' => 'required', 'disabled' => 'true' , 'row-input' => 'true', 'v-model' => 'row.total', 'data-item' => 'total', 'currency' => $currency, 'dynamic-currency' => 'currency'], 0.00, 'text-right input-price disabled-money') }}
                                    </div>
                                </td>
                            @endif
                        @stack('total_td_end')

                        @stack('delete_td_start')
                            <td colspan="1" class="pb-4 align-middle">
                                <div>
                                    <button type="button" @click="onDeleteItem(index)" class="btn btn-link btn-delete p-0">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        @stack('delete_td_end')
                    </tr>

                    <tr>
                        <td colspan="3">
                            @stack('item_custom_fields')
                        </td>

                        <td colspan="4" class="p-0">
                            <table class="w-100">
                                <colgroup>
                                    <col style="width: 100px;">
                                    <col style="width: 100px;">
                                    <col style="width: 250px;">
                                    <col style="width: 40px;">
                                </colgroup>
                                <tbody>
                                    @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                    <tr v-if="!row.add_tax || !row.add_discount">
                                        <td colspan="1" style="border: 0; max-width: 100px; border: 0px; padding-left: 10px;">
                                            <div style="max-width: 100px;">
                                                <button type="button" class="btn btn-link btn-sm p-0" @click="onAddDiscount(index)" v-if="!discount">Add Discount</button>
                                            </div>
                                        </td>
                                        <td colspan="1" style="border: 0; max-width: 100px; border: 0px; padding-right: 10px; text-align: right;">
                                            <div style="max-width: 100px;">
                                                <button type="button" class="btn btn-link btn-sm p-0" @click="onAddTax(index)" v-if="!tax">Add Tax</button>
                                            </div>
                                        </td>
                                        <td colspan="1" style="border: 0;" class="text-right total-column border-bottom-0 long-texts">
                                        </td>
                                        <td colspan="1" style="border: 0;"  class="w-1">
                                        </td>
                                    </tr>

                                    <tr v-if="row.add_discount">
                                        <td colspan="3" style="border: 0;"></td>
                                        <td colspan="2" style="border: 0;">
                                            <div>
                                            @stack('tax_id_input_start')
                                            <div class="input-group input-group-merge">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="input-discount-rate">
                                                        <i class="fa fa-percent"></i>
                                                    </span>
                                                </div>
                                                <input type="number"
                                                    max="100"
                                                    min="0"
                                                    class="form-control text-center"
                                                    :name="'items.' + index + '.discount-rate'"
                                                    autocomplete="off"
                                                    required="required"
                                                    data-item="discount_rate"
                                                    v-model="row.discount_rate"
                                                    @input="onCalculateTotal"
                                                    @change="form.errors.clear('items.' + index + '.discount_rate')">
                            
                                                <div class="invalid-feedback d-block"
                                                    v-if="form.errors.has('items.' + index + '.discount_rate')"
                                                    v-html="form.errors.get('items.' + index + '.discount_rate')">
                                                </div>
                                            </div>
                                            @stack('tax_id_input_end')
                                            </div>
                                        </td>
                                        <td colspan="1" style="border: 0;" class="text-right total-column border-bottom-0 long-texts">
                                            <div>
                                                {{ Form::moneyGroup('discount', '', '', ['required' => 'required', 'disabled' => 'true' , 'row-input' => 'true', 'v-model' => 'row.discount', 'data-item' => 'discount', 'currency' => $currency, 'dynamic-currency' => 'currency'], 0.00, 'text-right input-price disabled-money') }}
                                            </div>
                                        </td>
                                        <td colspan="1" style="border: 0;"  class="w-1">
                                            <button type="button" @click="onDeleteDiscount(index)" class="btn btn-link btn-sm p-0">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="row.add_tax" v-for="(row_tax, row_tax_index) in row.tax_ids"
                                    :index="row_tax_index">
                                    @else
                                    <tr v-for="(row_tax, row_tax_index) in row.tax_ids"
                                    :index="row_tax_index">
                                    @endif
                                        <td colspan="2" class="pb-0" style="border: 0;  padding-right: 5px; padding-left: 5px;" >
                                            <div style="margin-left: -30px; margin-right: 35px;">
                                            <span style="float: left; margin-right: 10px; margin-top: 15px;">{{ trans_choice('general.taxes', 1) }}</span>

                                            @stack('taxes_input_start')
                                            <akaunting-select
                                                class="mb-0 select-tax"
                                                :form-classes="[{'has-error': form.errors.has('items.' + index + '.taxes') }]"
                                                :icon="''"
                                                :title="''"
                                                :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}'"
                                                :name="'items.' + index + '.taxes.' + row_tax_index"
                                                :options="{{ json_encode($taxes->pluck('title', 'id')) }}"
                                                :disabled-options="form.items[index].tax_ids"
                                                :value="row_tax.id"
                                                @interface="row_tax.id = $event"
                                                @change="onCalculateTotal()"
                                                @new="taxes.push($event)"
                                                :form-error="form.errors.get('items.' + index + '.taxes')"
                                                :no-data-text="'{{ trans('general.no_data') }}'"
                                                :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
                                            ></akaunting-select>
                                            @stack('taxes_input_end')
                                            </div>
                                        </td>
                                        <td colspan="1" style="border: 0;" class="pb-0 text-right long-texts">
                                            <div>
                                                {{ Form::moneyGroup('tax', '', '', ['required' => 'required', 'disabled' => 'true' , 'row-input' => 'true', 'v-model' => 'row_tax.price', 'data-item' => 'total', 'currency' => $currency, 'dynamic-currency' => 'currency'], 0.00, 'text-right input-price disabled-money') }}
                                            </div>
                                        </td>
                                        <td colspan="1" style="border: 0;"  class="pb-0 align-middle">
                                            <button type="button" @click="onDeleteTax(index, row_tax_index)" class="btn btn-link btn-delete p-0">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="row.add_tax">
                                        <td colspan="2" style="border: 0; padding-right: 5px; padding-left: 5px;">
                                            <div style="margin-left: -30px; margin-right:35px;">
                                            <span style="float: left; margin-right: 10px; margin-top: 15px;">{{ trans_choice('general.taxes', 1) }}</span>

                                            @stack('taxes_input_start')
                                            <akaunting-select
                                                class="mb-0 select-tax"
                                                :form-classes="[{'has-error': form.errors.has('items.' + index + '.taxes') }]"
                                                :icon="''"
                                                :title="''"
                                                :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}'"
                                                :name="'items.' + index + '.taxes.999'"
                                                :options="{{ json_encode($taxes->pluck('title', 'id')) }}"
                                                :disabled-options="form.items[index].tax_ids"
                                                :value="tax_id"
                                                :add-new="{{ json_encode([
                                                    'status' => true,
                                                    'text' => trans('general.add_new'),
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
                                                            'class' => 'btn-success'
                                                        ]
                                                    ]
                                                ])}}"
                                                @interface="tax_id = $event"
                                                @visible-change="onSelectedTax(index)"
                                                @new="taxes.push($event)"
                                                :form-error="form.errors.get('items.' + index + '.taxes')"
                                                :no-data-text="'{{ trans('general.no_data') }}'"
                                                :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
                                            ></akaunting-select>
                                            @stack('taxes_input_end')
                                            </div>
                                        </td>
                                        <td colspan="1" style="border: 0;" class="text-right long-texts align-middle">
                                            <div>
                                                __
                                            </div>
                                        </td>
                                        <td colspan="1" style="border: 0;">

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    <tr>
                </tbody>
            </table>
        </td>
    @stack('name_td_end')
</tr>
