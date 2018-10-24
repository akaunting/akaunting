<tr>
    <td><a href="javascript:void(0);" data-href="{{ url('wizard/currencies/' . $item->id . '/edit') }}" class="currency-edit">{{ $item->name }}</a></td>
    <td class="hidden-xs">{{ $item->code }}</td>
    <td>{{ $item->rate }}</td>
    <td class="hidden-xs">
        @if ($item->enabled)
            <span class="label label-success">{{ trans('general.enabled') }}</span>
        @else
            <span class="label label-danger">{{ trans('general.disabled') }}</span>
        @endif
    </td>
    <td class="text-center">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="javascript:void(0);" data-href="{{ url('wizard/currencies/' . $item->id . '/edit') }}" class="currency-edit">{{ trans('general.edit') }}</a></li>
                @if ($item->enabled)
                    <li><a href="javascript:void(0);" data-href="{{ url('wizard/currencies/' . $item->id . '/disable') }}" class="currency-disable">{{ trans('general.disable') }}</a></li>
                @else
                    <li><a href="javascript:void(0);" data-href="{{ url('wizard/currencies/' . $item->id . '/enable') }}" class="currency-enable">{{ trans('general.enable') }}</a></li>
                @endif
                @permission('delete-settings-currencies')
                <li class="divider"></li>
                <li>
                    {!! Form::button(trans('general.delete'), array(
                        'type'    => 'button',
                        'class'   => 'delete-link',
                        'title'   => trans('general.delete'),
                        'onclick' => 'confirmDelete("' . '#currencies-' . $item->id . '", "' . trans_choice('general.currencies', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . $item->name . '</strong>', 'type' => mb_strtolower(trans_choice('general.currencies', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                    )) !!}
                </li>
                @endpermission
            </ul>
        </div>
    </td>
</tr>