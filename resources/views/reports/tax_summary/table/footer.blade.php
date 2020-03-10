<tfoot>
    <tr class="row rp-border-bottom-1 font-size-unset px-3">
        <th class="{{ $class->column_name_width }} text-left">{{ trans('reports.net') }}</th>
        @php $grand_total = 0; @endphp
        @foreach($class->footer_totals[$table] as $total)
            @php $grand_total += $total; @endphp
            <th class="{{ $class->column_value_width }} text-right px-0">@money($total, setting('default.currency'), true)</th>
        @endforeach
        <th class="{{ $class->column_name_width }} text-right pl-0 pr-4">@money($grand_total, setting('default.currency'), true)</th>
    </tr>
</tfoot>
