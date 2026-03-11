@php $grand_total = array_sum($class->footer_totals[$table_key]); @endphp

<tfoot>
    <tr class="px-3">
        <th class="{{ $class->column_name_width }} text-uppercase ltr:text-left rtl:text-right">{{ trans_choice('general.totals', 1) }}</th>
        @foreach($class->footer_totals[$table_key] as $total)
            <th class="{{ $class->column_value_width }} ltr:text-right rtl:text-left px-0">{{ $class->has_money ? money($total) : $total }}</th>
        @endforeach
        <th class="{{ $class->column_name_width }} ltr:text-right rtl:text-left ltr:pl-0 rtl:pr-0 ltr:pr-4 rtl:pl-4">{{ $class->has_money ? money($grand_total) : $grand_total }}</th>
    </tr>
</tfoot>
