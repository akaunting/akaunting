<tr id="tax-create">
    <td>
        {{ Form::textGroup('name', trans('general.name'), 'id-card-o', ['required' => 'required'], null, '') }}
    </td>
    <td>
        {{ Form::textGroup('rate', trans('currencies.rate'), 'money', ['required' => 'required'], null, '') }}
        {{ Form::hidden('type', 'normal') }}
    </td>
    <td class="hidden-xs">
        {{ Form::radioGroup('enabled', trans('general.enabled'), trans('general.yes'), trans('general.no'), [], 'col-md-12 tax-enabled-radio-group') }}
    </td>
    <td class="text-center">
        {!! Form::button('<span class="fa fa-save"></span>', ['type' => 'button', 'class' => 'btn btn-success  tax-submit', 'data-loading-text' => trans('general.loading'), 'data-href' => url('wizard/taxes'), 'style' => 'padding: 9px 14px; margin-top: 10px;']) !!}
    </td>
</tr>