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
                <input class="form-control"
                       data-item="name"
                       required="required"
                       name="items[][name]"
                       v-model="row.name"
                       @input="onGetItem($event, index)"
                       type="text"
                       autocomplete="off">
                <div class="dropdown-menu item-show dropdown-menu-center" ref="menu" :class="[{show: row.show}]">
                    <div class="list-group list-group-flush">
                        <a class="list-group-item list-group-item-action" v-for="(item, item_index) in items" @click="onSelectItem(item, index)">
                            <div class="row align-items-center">
                                <div class="col ml--2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="name" v-text="item.name"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <input name="items[][show]"
                       value="false"
                       v-model="row.show"
                       data-item="show"
                       type="hidden">
                <input name="items[][item_id]"
                       v-model="row.item_id"
                       data-item="item_id"
                       type="hidden">
                {!! $errors->first('item.name', '<p class="help-block">:message</p>') !!}
            @stack('name_input_end')
        </td>
    @stack('name_td_end')

    @stack('quantity_td_start')
        <td class="col-md-2 border-right-0 border-bottom-0">
            @stack('quantity_input_start')
                <input class="form-control text-center"
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
        <td class="col-md-2 border-right-0 border-bottom-0">
            @stack('tax_id_input_start')
                {{ Form::multiSelectAddNewGroup('tax_id', '', '', $taxes, '', [
                    'data-item' => 'tax_id',
                    'v-model' => 'row.tax_id',
                    'change' => 'onCalculateTotal',
                    'class' => 'form-control'
                ], 'mb-0 select-tax') }}
            @stack('tax_id_input_end')
        </td>
    @stack('taxes_td_end')

    @stack('total_td_start')
        <td class="col-md-2 text-right total-column border-bottom-0">
            <input name="item[][total]"
                data-item="total"
                v-model.lazy="row.total"
                v-money="money"
                type="hidden">
            @stack('total_input_start')
                @if (empty($item) || !isset($item->total))
                    <span id="item-total" v-html="row.total">0</span>
                @else
                    <span id="item-total" v-html="row.total">@money($item->total, $bill->currency_code, true)</span>
                @endif
            @stack('total_input_end')
        </td>
    @stack('total_td_end')
</tr>
