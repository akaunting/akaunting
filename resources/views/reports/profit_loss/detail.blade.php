@include($class->views['detail.content.header'])

@foreach($class->tables as $table_key => $table_name)
    @include($class->views['detail.table'])

    @if($table_key === 'cogs' && !empty($class->gross_profit))
    <div class="overflow-x-scroll large-overflow-unset mb-8">
        <table class="w-full small-table-width rp-border-collapse">
            <tbody>
                <tr class="border-t-2 border-purple">
                    <td class="{{ $class->column_name_width }} w-24 ltr:text-left rtl:text-right text-black-400 uppercase font-bold">
                        {{ trans('reports.gross_profit') }}
                    </td>
                    @foreach($class->gross_profit as $profit)
                    <td class="{{ $class->column_value_width }} ltr:text-right rtl:text-left font-medium text-xs print-alignment {{ $profit < 0 ? 'text-red-500' : 'text-black-400' }}">
                        @if ($profit < 0)
                            ({{ money(abs($profit)) }})
                        @else
                            <x-money :amount="$profit" />
                        @endif
                    </td>
                    @endforeach
                    @php $gp_total = array_sum($class->gross_profit); @endphp
                    <td class="{{ $class->column_name_width }} ltr:text-right rtl:text-left font-medium text-xs print-alignment {{ $gp_total < 0 ? 'text-red-500' : 'text-black-400' }}">
                        @if ($gp_total < 0)
                            ({{ money(abs($gp_total)) }})
                        @else
                            <x-money :amount="$gp_total" />
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
@endforeach

@include($class->views['detail.content.footer'])
