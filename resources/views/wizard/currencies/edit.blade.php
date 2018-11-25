<tr id="currency-edit">
    <td>
        {{ Form::textGroup('name', trans('general.name'), 'id-card-o', [], $item->name, '') }}
    </td>
    <td class="hidden-xs">
        {{ Form::selectGroup('code', trans('currencies.code'), 'code', $codes, $item->code, [], '') }}
    </td>
    <td>
        {{ Form::textGroup('rate', trans('currencies.rate'), 'money', [], $item->rate, '') }}
    </td>
    <td class="hidden-xs">
        {{ Form::radioGroup('enabled', trans('general.enabled'), trans('general.yes'), trans('general.no'), [], 'col-md-12') }}
    </td>
    <td class="text-center">
        {!! Form::button('<span class="fa fa-save"></span>', ['type' => 'button', 'class' => 'btn btn-success  currency-updated', 'data-loading-text' => trans('general.loading'), 'data-href' => url('wizard/currencies/' . $item->id), 'data-id' => $item->id, 'style' => 'padding: 9px 14px; margin-top: 10px;']) !!}
    </td>
    <td class="hidden">
        {{ Form::numberGroup('precision', trans('currencies.precision'), 'bullseye', [], $item->precision) }}

        {{ Form::textGroup('symbol', trans('currencies.symbol.symbol'), 'font', [], $item->symbol, '') }}

        {{ Form::selectGroup('symbol_first', trans('currencies.symbol.position'), 'text-width', ['1' => trans('currencies.symbol.before'), '0' => trans('currencies.symbol.after')], $item->symbol_first) }}

        {{ Form::textGroup('decimal_mark', trans('currencies.decimal_mark'), 'columns', [], $item->decimal_mark, '') }}

        {{ Form::textGroup('thousands_separator', trans('currencies.thousands_separator'), 'columns', [], $item->thousands_separator) }}

        {{ Form::radioGroup('default_currency', trans('currencies.default')) }}

        {{ Form::hidden('id', $item->id) }}
    </td>
</tr>