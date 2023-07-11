@php $grand_total = array_sum($class->footer_totals[$table_key]); @endphp

<tfoot>
    <tr class="px-3">
        <th class="{{ $class->column_name_width }} text-uppercase text-left">{{ trans_choice('general.totals', 1) }}</th>
        @foreach($class->footer_totals[$table_key] as $total)
            <th class="{{ $class->column_value_width }} text-right px-0">{{ $class->has_money ? money($total) : $total }}</th>
        @endforeach
        <th class="{{ $class->column_name_width }} text-right pl-0 pr-4">{{ $class->has_money ? money($grand_total) : $grand_total }}</th>
    </tr>
</tfoot>
