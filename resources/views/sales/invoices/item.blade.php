<tr class="row" v-for="(row, index) in form.items"
    :index="index">
    @stack('actions_td_start')
        <td class="col-md-1 action-column border-right-0 border-bottom-0">
            @stack('actions_button_start')
                <button type="button"
                        @click="onDeleteItem(index)"
                        data-toggle="tooltip"
                        title="{{ trans('general.delete') }}"
                        class="btn btn-icon btn-outline-danger btn-lg"><i class="fa fa-trash"></i>
                </button>
            @stack('actions_button_end')
        </td>
    @stack('actions_td_end')

    @stack('name_td_start')
        <td class="col-md-3 border-right-0 border-bottom-0">
            @stack('name_input_start')
            <akaunting-select-remote
                :form-classes="[{'has-error': form.errors.get('name') }]"
                :placeholder="'{{ trans('general.type_item_name') }}'"
                :name="'item_id'"
                :options="{{ json_encode($items) }}"
                :value="'{{ old('item_id', '') }}'"
                :add-new="{{ json_encode([
                    'status' => true,
                    'text' => trans('general.add_new'),
                    'path' => route('modals.items.store'),
                    'type' => 'inline',
                    'field' => 'name',
                ])}}"
                @interface="row.item_id = $event"
                @label="row.name = $event"
                @option="onSelectItem($event, index)"
                :remote-action="'{{ route('items.autocomplete') }}'"
                :remote-type="'invoice'"
                :currency-code="form.currency_code"
                :form-error="form.errors.get('name')"
                :loading-text="'{{ trans('general.loading') }}'"
                :no-data-text="'{{ trans('general.no_data') }}'"
                :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
            ></akaunting-select-remote>
            <input type="hidden"
                data-item="name"
                v-model="row.name"
                @input="onCalculateTotal"
                name="item[][name]">
            {!! $errors->first('item.name', '<p class="help-block">:message</p>') !!}
            @stack('name_input_end')
        </td>
    @stack('name_td_end')

    @stack('quantity_td_start')
        <td class="col-md-1 border-right-0 border-bottom-0">
            @stack('quantity_input_start')
                <input class="form-control text-center"
                       autocomplete="off"
                       required="required"
                       data-item="quantity"
                       v-model="row.quantity"
                       @input="onCalculateTotal"
                       name="item[][quantity]"
                       type="text">
                {!! $errors->first('item.quantity', '<p class="help-block">:message</p>') !!}
            @stack('quantity_input_end')
        </td>
    @stack('quantity_td_end')

    @stack('price_td_start')
        <td class="col-md-2 border-right-0 border-bottom-0">
            @stack('price_input_start')
                <input class="form-control text-right input-price"
                       autocomplete="off"
                       required="required"
                       data-item="price"
                       v-model.lazy="row.price"
                       v-money="money"
                       @input="onCalculateTotal"
                       name="items[][price]"
                       type="text">
                <input name="items[][currency]"
                       data-item="currency"
                       v-model="row.currency"
                       @input="onCalculateTotal"
                       type="hidden">
                {!! $errors->first('item.price', '<p class="help-block">:message</p>') !!}
            @stack('price_input_end')
        </td>
    @stack('price_td_end')

    @stack('taxes_td_start')
        <td class="col-md-3 border-right-0 border-bottom-0">
            @stack('tax_id_input_start')
                {{ Form::multiSelectAddNewGroup('tax_id', '', '', $taxes, '', [
                    'data-item' => 'tax_id',
                    'v-model' => 'row.tax_id',
                    'change' => 'onCalculateTotal',
                    'class' => 'form-control',
                    'collapse' => 'false',
                    'path' => route('modals.taxes.create')
                ], 'mb-0 select-tax') }}
            @stack('tax_id_input_end')
        </td>
    @stack('taxes_td_end')

    @stack('total_td_start')
        <td class="col-md-2 text-right total-column border-bottom-0 long-texts">
            <input name="item[][total]"
                data-item="total"
                v-model.lazy="row.total"
                v-money="money"
                type="hidden">
            @stack('total_input_start')
                @if (empty($item) || !isset($item->total))
                    <span id="item-total" v-html="row.total">0</span>
                @else
                    <span id="item-total" v-html="row.total">@money($item->total, $invoice->currency_code, true)</span>
                @endif
            @stack('total_input_end')
        </td>
    @stack('total_td_end')
</tr>
