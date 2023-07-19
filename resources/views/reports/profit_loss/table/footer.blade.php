@php $grand_total = array_sum($class->footer_totals[$table_key]); @endphp

<tfoot>
    <tr>
        <td class="{{ $class->column_name_width }} w-24 py-4 ltr:text-left rtl:text-right text-black-400 font-bold uppercase">
            {{ trans_choice('general.totals', 1) }}
        </td>

        @foreach($class->footer_totals[$table_key] as $total)
        <td class="{{ $class->column_value_width }} py-4 ltr:text-right rtl:text-left text-black-400 font-medium text-xs print-alignment">
            <x-money :amount="$total" />
        </td>
        @endforeach

        <td class="{{ $class->column_name_width }} py-4 ltr:text-right rtl:text-left text-black-400 font-medium text-xs print-alignment">
            <x-money :amount="$grand_total" />
        </td>
    </tr>
</tfoot>
