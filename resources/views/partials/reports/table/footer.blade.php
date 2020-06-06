<tfoot>
    @if ($grand_total = array_sum($class->footer_totals[$table]))
        <tr class="row rp-border-top-1 font-size-unset px-3">
            <th class="{{ $class->column_name_width }} text-uppercase">{{ trans_choice('general.totals', 1) }}</th>
            @foreach($class->footer_totals[$table] as $total)
                <th class="{{ $class->column_value_width }} text-right px-0">@money($total, setting('default.currency'), true)</th>
            @endforeach
            <th class="{{ $class->column_name_width }} text-right pl-0 pr-4">@money($grand_total, setting('default.currency'), true)</th>
        </tr>
    @else
        <tr>
            <td colspan="{{ count($class->dates) + 2 }}">
                <div class="text-muted pl-0">{{ trans('general.no_records') }}</div>
            </td>
        </tr>
    @endif
</tfoot>
