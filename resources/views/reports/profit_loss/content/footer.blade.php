<div class="overflow-x-scroll large-overflow-unset mb-8">
    <table class="w-full small-table-width  rp-border-collapse">
        <tbody>
            <tr>
                <td class="{{ $class->column_name_width }} w-24 ltr:text-left rtl:text-right text-black-400 uppercase font-bold">
                    {{ trans('reports.net_profit') }}
                </td>

                @foreach($class->net_profit as $profit)
                <td class="{{ $class->column_value_width }} ltr:text-right rtl:text-left text-black-400 font-medium text-xs print-alignment">
                    <x-money :amount="$profit" />
                </td>
                @endforeach

                <td class="{{ $class->column_name_width }} ltr:text-right rtl:text-left text-black-400 font-medium text-xs print-alignment">
                    <x-money :amount="array_sum($class->net_profit)" />
                </td>
            </tr>
        </tbody>
    </table>
</div>
