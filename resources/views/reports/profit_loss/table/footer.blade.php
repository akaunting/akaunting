<tfoot>
    <tr class="row rp-border-top-1 font-size-unset px-3">
        <th class="{{ $class->head_column_width }} text-uppercase">{{ trans_choice('general.totals', 1) }}</th>
        @php $grand_total = 0; @endphp
        @foreach($class->footer_totals[$table] as $date => $total)
            @php $grand_total += $total; @endphp
            <th class="{{ $class->column_width }}">@money($total, setting('default.currency'), true)</th>
        @endforeach
        <th class="{{ $class->head_column_width }}">@money($grand_total, setting('default.currency'), true)</th>
    </tr>
</tfoot>
