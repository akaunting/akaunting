<tr class="d-flex flex-nowrap">
    @stack('name_td_start')
        @if (!$hideItems || (!$hideName && !$hideDescription))
            <td class="col-xs-4 col-sm-5 pl-5">
                @if (!$hideName)
                    {{ $item->name }}
                @endif

                @if (!$hideDescription)
                    @if (!empty($item->item->description))
                        <br><small class="text-pre-nowrap">{!! \Illuminate\Support\Str::limit($item->item->description, 500) !!}<small>
                    @endif
                @endif
            </td>
        @endif
    @stack('name_td_end')

    @stack('quantity_td_start')
        @if (!$hideQuantity)
            <td class="col-xs-4 col-sm-1 text-center">{{ $item->quantity }}</td>
        @endif
    @stack('quantity_td_end')

    @stack('price_td_start')
        @if (!$hidePrice)
            <td class="col-sm-3 text-right d-none d-sm-block">@money($item->price, $document->currency_code, true)</td>
        @endif
    @stack('price_td_end')

    @if (!$hideDiscount)
        @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
            @stack('discount_td_start')
                <td class="col-sm-1 text-center d-none d-sm-block">{{ $item->discount }}</td>
            @stack('discount_td_end')
        @endif
    @endif

    @stack('total_td_start')
        @if (!$hideAmount)
            <td class="col-xs-4 col-sm-3 text-right pr-5">@money($item->total, $document->currency_code, true)</td>
        @endif
    @stack('total_td_end')
</tr>