<tr class="d-flex flex-nowrap">
    @stack('name_td_start')
        <td class="col-xs-4 col-sm-5 pl-5">
            {{ $item->name }}
            @if (!empty($item->item->description))
                <br><small class="text-pre-nowrap">{!! \Illuminate\Support\Str::limit($item->item->description, 500) !!}<small>
            @endif
        </td>
    @stack('name_td_end')

    @stack('quantity_td_start')
        <td class="col-xs-4 col-sm-1 text-center">{{ $item->quantity }}</td>
    @stack('quantity_td_end')

    @stack('price_td_start')
        <td class="col-sm-3 text-right d-none d-sm-block">@money($item->price, $document->currency_code, true)</td>
    @stack('price_td_end')

    @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
        @stack('discount_td_start')
            <td class="col-sm-1 text-center d-none d-sm-block">{{ $item->discount }}</td>
        @stack('discount_td_end')
    @endif

    @stack('total_td_start')
        <td class="col-xs-4 col-sm-3 text-right pr-5">@money($item->total, $document->currency_code, true)</td>
    @stack('total_td_end')
</tr>