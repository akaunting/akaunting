<tr v-for="(row, index) in form.items"
    :index="index">
    @stack('actions_td_start')
        <td class="text-center border-right-0 border-bottom-0">
            @stack('actions_button_start')
                <button type="button"
                    @click="onDeleteItem(index)"
                    data-toggle="tooltip"
                    title="{{ trans('general.delete') }}"
                    class="btn btn-icon btn-outline-danger btn-lg">
                    <i class="fa fa-trash"></i>
                </button>
            @stack('actions_button_end')
        </td>
    @stack('actions_td_end')

    @stack('name_td_start')
        <td class="border-right-0 border-bottom-0"
            :class="[{'has-error': form.errors.has('items.' + index + '.name') }]">
            @stack('name_input_start')
            <akaunting-select-remote
                :form-classes="[{'has-error': form.errors.has('items.' + index + '.name')}]"
                :placeholder="'{{ trans('general.type_item_name') }}'"
                :name="'item_id'"
                :options="{{ json_encode($items) }}"
                :value="form.items[index].item_id"
                :add-new="{{ json_encode([
                    'status' => true,
                    'text' => trans('general.add_new'),
                    'path' => route('modals.items.store'),
                    'type' => 'inline',
                    'field' => [
                        'key' => 'id',
                        'value' => 'name'
                    ],
                    'new_text' => trans('modules.new'),
                ])}}"
                @interface="row.item_id = $event"
                @label="row.name = $event"
                @option="onSelectItem($event, index)"
                @change="form.errors.clear('items.' + index + '.name')"
                :remote-action="'{{ route('items.autocomplete') }}'"
                remote-type="bill"
                :currency-code="form.currency_code"
                :form-error="form.errors.get('items.' + index + '.name')"
                :loading-text="'{{ trans('general.loading') }}'"
                :no-data-text="'{{ trans('general.no_data') }}'"
                :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
            ></akaunting-select-remote>
            <input type="hidden"
                data-item="name"
                v-model="row.name"
                @input="onCalculateTotal"
                name="item[][name]">

            <div class="invalid-feedback d-block"
                v-if="form.errors.has('items.' + index + '.name')"
                v-html="form.errors.get('items.' + index + '.name')">
            </div>
            @stack('name_input_end')
        </td>
    @stack('name_td_end')

    @stack('quantity_td_start')
        <td class="border-right-0 border-bottom-0 w-10"
            :class="[{'has-error': form.errors.has('items.' + index + '.quantity') }]">
            @stack('quantity_input_start')
                <input type="text"
                    class="form-control text-center"
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
        </td>
    @stack('quantity_td_end')

    @stack('price_td_start')
        <td class="border-right-0 border-bottom-0 pb-0"
            :class="[{'has-error': form.errors.has('items.' + index + '.price') }]">
            @stack('price_input_start')
                {{ Form::moneyGroup('name', '', '', ['required' => 'required', 'v-model' => 'row.price', 'v-error' => 'form.errors.get(\'items.\' + index + \'.price\')', 'v-error-message' => 'form.errors.get(\'items.\' + index + \'.price\')' , 'data-item' => 'price', 'currency' => $currency, 'dynamic-currency' => 'currency', 'change' => 'row.price = $event; form.errors.clear(\'items.\' + index + \'.price\'); onCalculateTotal'], 0.00, 'text-right input-price') }}

                <input :name="'items.' + index + '.currency'"
                    data-item="currency"
                    v-model="row.currency"
                    @input="onCalculateTotal"
                    type="hidden">
            @stack('price_input_end')
        </td>
    @stack('price_td_end')

    @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
        @stack('discount_td_start')
        <td class="border-right-0 border-bottom-0 w-12"
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
                    class="form-control text-center"
                    :name="'items.' + index + '.discount'"
                    autocomplete="off"
                    required="required"
                    data-item="quantity"
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

    @stack('taxes_td_start')
        <td class="border-right-0 border-bottom-0"
            :class="[{'has-error': form.errors.has('items.' + index + '.tax_id') }]">
            @stack('tax_id_input_start')
            <akaunting-select
                class="mb-0 select-tax"
                :form-classes="[{'has-error': form.errors.has('items.' + index + '.tax_id') }]"
                :icon="''"
                :title="''"
                :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}'"
                :name="'tax_id'"
                :options="{{ json_encode($taxes->pluck('title', 'id')) }}"
                :value="row.tax_id"
                :multiple="true"
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
                :collapse="true"
                @interface="row.tax_id = $event"
                @change="onCalculateTotal()"
                @new="taxes.push($event)"
                :form-error="form.errors.get('items.' + index + '.tax_id')"
                :no-data-text="'{{ trans('general.no_data') }}'"
                :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
            ></akaunting-select>
            <input id="taxes" name="taxes" type="hidden" data-value="{{ json_encode($taxes) }}" v-model="taxes">
            @stack('tax_id_input_end')
        </td>
    @stack('taxes_td_end')

    @stack('total_td_start')
        <td class="text-right total-column border-bottom-0 long-texts">
            <akaunting-money :col="'d-none'"
                :masked="true"
                :error="{{ 'form.errors.get("total")' }}"
                :name="'total'"
                :currency="{{ json_encode($currency) }}"
                :dynamic-currency="currency"
                v-model="row.total"
                @interface="row.total = $event"
            ></akaunting-money>
            @stack('total_input_start')
                <span id="item-total" v-if="row.total" v-html="row.total"></span>
                @if (empty($item) || !isset($item->total))
                    <span id="item-total" v-else>@money(0, $currency->code, true)</span>
                @else
                    <span id="item-total" v-else>@money($item->total, $bill->currency_code, true)</span>
                @endif
            @stack('total_input_end')
        </td>
    @stack('total_td_end')
</tr>
