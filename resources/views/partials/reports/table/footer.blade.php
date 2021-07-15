@php $grand_total = array_sum($class->footer_totals[$table]); @endphp

<tfoot>
    <tr class="row rp-border-top-1 font-size-unset px-3">
        <th class="{{ $class->column_name_width }} text-uppercase text-left">{{ trans_choice('general.totals', 1) }}</th>
        @foreach($class->footer_totals[$table] as $total)
            <th class="{{ $class->column_value_width }} text-right px-0">{{ $class->has_money ? money($total, setting('default.currency'), true) : $total }}</th>
        @endforeach
        <th class="{{ $class->column_name_width }} text-right pl-0 pr-4">{{ $class->has_money ? money($grand_total, setting('default.currency'), true) : $grand_total }}</th>
    </tr>
</tfoot>
