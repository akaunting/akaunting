<tr v-for="(row, index) in form.items"
    :index="index">
    @stack('actions_td_start')
        <td class="text-center border-right-0 border-bottom-0">
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
        <td class="border-right-0 border-bottom-0">
            @stack('name_input_start')
            <akaunting-select-remote
                :form-classes="[{'has-error': form.errors.get('name') }]"
                :placeholder="'{{ trans('general.type_item_name') }}'"
                :name="'item_id'"
                :options="{{ json_encode($items) }}"
                :value="form.items[index].item_id"
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
                :remote-type="'bill'"
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
        <td class="border-right-0 border-bottom-0">
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
        <td class="border-right-0 border-bottom-0 pb-0">
            @stack('price_input_start')
                {{ Form::moneyGroup('name', '', '', ['required' => 'required', 'v-model' => 'row.price', 'data-item' => 'price', 'currency' => $currency, 'dynamic-currency' => 'currency', 'change' => 'row.price = $event; onCalculateTotal'], 0.00, 'text-right input-price') }}
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
        <td class="border-right-0 border-bottom-0">
            @stack('tax_id_input_start')
            <akaunting-select
                class="mb-0 select-tax"
                :form-classes="[{'has-error': form.errors.get('tax_id') }]"
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
                    'field' => 'name',
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
                :collapse="false"
                @interface="row.tax_id = $event"
                @change="onCalculateTotal($event)"
                :form-error="form.errors.get('tax_id')"
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
