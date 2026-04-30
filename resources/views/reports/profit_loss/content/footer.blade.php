<div class="overflow-x-scroll large-overflow-unset mb-8">
    <table class="w-full small-table-width rp-border-collapse">
        <tbody>
            <tr>
                <td class="{{ $class->column_name_width }} w-24 ltr:text-left rtl:text-right text-black-400 uppercase font-bold">
                    {{ trans('reports.net_profit_loss') }}
                </td>

                @foreach($class->net_profit as $profit)
                <td class="{{ $class->column_value_width }} ltr:text-right rtl:text-left font-medium text-xs print-alignment {{ $profit < 0 ? 'text-red-500' : 'text-black-400' }}">
                    @if ($profit < 0)
                        ({{ money(abs($profit)) }})
                    @else
                        <x-money :amount="$profit" />
                    @endif
                </td>
                @endforeach

                @php $np_total = array_sum($class->net_profit); @endphp
                <td class="{{ $class->column_name_width }} ltr:text-right rtl:text-left font-medium text-xs print-alignment {{ $np_total < 0 ? 'text-red-500' : 'text-black-400' }}">
                    @if ($np_total < 0)
                        ({{ money(abs($np_total)) }})
                    @else
                        <x-money :amount="$np_total" />
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
