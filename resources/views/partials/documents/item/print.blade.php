<tr>
    @stack('name_td_start')
        <td class="item">
            {{ $item->name }}
            @if (!empty($item->item->description))
                <br><small>{!! \Illuminate\Support\Str::limit($item->item->description, 500) !!}</small>
            @endif
        </td>
    @stack('name_td_end')

    @stack('quantity_td_start')
        <td class="quantity">{{ $item->quantity }}</td>
    @stack('quantity_td_end')

    @stack('price_td_start')
        <td class="price">@money($item->price, $document->currency_code, true)</td>
    @stack('price_td_end')

    @stack('total_td_start')
        <td class="total">@money($item->total, $document->currency_code, true)</td>
    @stack('total_td_end')
</tr>