<tr id="currency-create">
    <td>
        {{ Form::textGroup('name', trans('general.name'), 'id-card-o', ['required' => 'required'], null, '') }}
    </td>
    <td class="hidden-xs">
        {{ Form::selectGroup('code', trans('currencies.code'), 'code', $codes, null, ['required' => 'required'], '') }}
    </td>
    <td>
        {{ Form::textGroup('rate', trans('currencies.rate'), 'money', ['required' => 'required'], null, '') }}
    </td>
    <td class="hidden-xs">
        {{ Form::radioGroup('enabled', trans('general.enabled'), trans('general.yes'), trans('general.no'), [], 'col-md-12 currency-enabled-radio-group') }}
    </td>
    <td class="text-center">
        {!! Form::button('<span class="fa fa-save"></span>', ['type' => 'button', 'class' => 'btn btn-success  currency-submit', 'data-loading-text' => trans('general.loading'), 'data-href' => url('wizard/currencies/'), 'style' => 'padding: 9px 14px; margin-top: 10px;']) !!}
    </td>
    <td class="hidden">
        {{ Form::numberGroup('precision', trans('currencies.precision'), 'bullseye') }}

        {{ Form::textGroup('symbol', trans('currencies.symbol.symbol'), 'font') }}

        {{ Form::selectGroup('symbol_first', trans('currencies.symbol.position'), 'text-width', ['1' => trans('currencies.symbol.before'), '0' => trans('currencies.symbol.after')]) }}

        {{ Form::textGroup('decimal_mark', trans('currencies.decimal_mark'), 'columns') }}

        {{ Form::textGroup('thousands_separator', trans('currencies.thousands_separator'), 'columns', []) }}

        {{ Form::radioGroup('default_currency', trans('currencies.default')) }}
    </td>
</tr>