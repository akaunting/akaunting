<tr id="tax-edit">
    <td>
        {{ Form::textGroup('name', trans('general.name'), 'id-card-o', ['required' => 'required'], $item->name, '') }}
    </td>
    <td>
        {{ Form::textGroup('rate', trans('currencies.rate'), 'money', ['required' => 'required'], $item->rate, '') }}
        {{ Form::hidden('type', 'normal') }}
    </td>
    <td class="hidden-xs">
        {{ Form::radioGroup('enabled', trans('general.enabled'), trans('general.yes'), trans('general.no'), [], 'col-md-12') }}
    </td>
    <td class="text-center">
        {!! Form::button('<span class="fa fa-save"></span>', ['type' => 'button', 'class' => 'btn btn-success  tax-updated', 'data-loading-text' => trans('general.loading'), 'data-href' => url('wizard/taxes/' . $item->id), 'data-id' => $item->id, 'style' => 'padding: 9px 14px; margin-top: 10px;']) !!}
    </td>
</tr>