<tr>
    @stack('name_td_start')
        @if (! $hideItems || (! $hideName && ! $hideDescription))
            <td class="item text text-alignment-left text-left max-w-0">
                @if (! $hideName)
                    {{ $item->name }} <br/>
                @endif

                @if (! $hideDescription)
                    @if (! empty($item->description))
                        <div class="small-text break-words">
                            {!! \Illuminate\Support\Str::limit(nl2br($item->description), 500) !!}
                        </div>
                    @endif
                @endif

                @stack('item_custom_fields')
                @stack('item_custom_fields_' . $item->id)
            </td>
        @endif
    @stack('name_td_end')

    @stack('quantity_td_start')
        @if (! $hideQuantity)
            <td class="quantity text text-alignment-right text-right">
                {{ $item->quantity }}
            </td>
        @endif
    @stack('quantity_td_end')

    @stack('price_td_start')
        @if (! $hidePrice)
            <td class="price text text-alignment-right text-right">
                <x-money :amount="$item->price" :currency="$document->currency_code" />
            </td>
        @endif
    @stack('price_td_end')

    @if (! $hideDiscount)
        @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
            @stack('discount_td_start')
                @if ($item->discount_type === 'percentage')
                    <td class="discount text text-alignment-right text-right">
                        @php
                            $text_discount = '';

                            if (setting('localisation.percent_position') == 'before') {
                                $text_discount .= '%';
                            }

                            $text_discount .= $item->discount;

                            if (setting('localisation.percent_position') == 'after') {
                                $text_discount .= '%';
                            }
                        @endphp

                        {{ $text_discount }}
                    </td>
                @else
                    <td class="discount text text-alignment-right text-right">
                        <x-money :amount="$item->discount" :currency="$document->currency_code" />
                    </td>
                @endif
            @stack('discount_td_end')
        @endif
    @endif

    @stack('total_td_start')
        @if (! $hideAmount)
            <td class="total text text-alignment-right text-right">
                <x-money :amount="$item->total" :currency="$document->currency_code" />
            </td>
        @endif
    @stack('total_td_end')
</tr>
