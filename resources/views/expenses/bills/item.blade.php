<tr id="item-row-{{ $item_row }}">
    @stack('actions_td_start')
    <td class="text-center" style="vertical-align: middle;">
        @stack('actions_button_start')
        <button type="button" onclick="$(this).tooltip('destroy'); $('#item-row-{{ $item_row }}').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
        @stack('actions_button_end')
    </td>
    @stack('actions_td_end')
    @stack('name_td_start')
    <td {!! $errors->has('item.' . $item_row . '.name') ? 'class="has-error"' : ''  !!}>
        @stack('name_input_start')
        <input value="{{ empty($item) ? '' : $item->name }}" class="form-control typeahead" required="required" placeholder="{{ trans('general.form.enter', ['field' => trans_choice('bills.item_name', 1)]) }}" name="item[{{ $item_row }}][name]" type="text" id="item-name-{{ $item_row }}" autocomplete="off">
        <input value="{{ empty($item) ? '' : $item->item_id }}" name="item[{{ $item_row }}][item_id]" type="hidden" id="item-id-{{ $item_row }}">
        {!! $errors->first('item.' . $item_row . '.name', '<p class="help-block">:message</p>') !!}
        @stack('name_input_end')
    </td>
    @stack('name_td_end')
    @stack('quantity_td_start')
    <td {{ $errors->has('item.' . $item_row . '.quantity') ? 'class="has-error"' : '' }}>
        @stack('quantity_input_start')
        <input value="{{ empty($item) ? 1 : $item->quantity }}" class="form-control text-center" required="required" name="item[{{ $item_row }}][quantity]" type="text" id="item-quantity-{{ $item_row }}">
        {!! $errors->first('item.' . $item_row . '.quantity', '<p class="help-block">:message</p>') !!}
        @stack('quantity_input_end')
    </td>
    @stack('quantity_td_end')
    @stack('price_td_start')
    <td {{ $errors->has('item.' . $item_row . 'price') ? 'class="has-error"' : '' }}>
        @stack('price_input_start')
        <input value="{{ empty($item) ? '' : $item->price }}" class="form-control text-right input-price" required="required" name="item[{{ $item_row }}][price]" type="text" id="item-price-{{ $item_row }}">
        <input value="{{ $currency->code }}" name="item[{{ $item_row }}][currency]" type="hidden" id="item-currency-{{ $item_row }}">
        {!! $errors->first('item.' . $item_row . 'price', '<p class="help-block">:message</p>') !!}
        @stack('price_input_end')
    </td>
    @stack('price_td_end')
    @stack('taxes_td_start')
    <td {{ $errors->has('item.' . $item_row . '.tax_id') ? 'class="has-error"' : '' }}>
        @stack('tax_id_input_start')
        {!! Form::select('item[' . $item_row . '][tax_id][]', $taxes, (empty($item) || empty($item->taxes)) ? setting('general.default_tax') : $item->taxes->pluck('tax_id'), ['id'=> 'item-tax-' . $item_row, 'class' => 'form-control tax-select2', 'multiple' => 'true']) !!}
        {!! $errors->first('item.' . $item_row . '.tax_id', '<p class="help-block">:message</p>') !!}
        @stack('tax_id_input_end')
    </td>
    @stack('taxes_td_end')
    @stack('total_td_start')
    <td class="text-right" style="vertical-align: middle;">
        @stack('total_input_start')
        @if (empty($item) || !isset($item->total))
        <span id="item-total-{{ $item_row }}">0</span>
        @else
        <span id="item-total-{{ $item_row }}">@money($item->total, $bill->currency_code, true)</span>
        @endif
        @stack('total_input_end')
    </td>
    @stack('total_td_end')
</tr>
