<tr>
    @stack('name_td_start')
        @if (!$hideItems || (!$hideName && !$hideDescription))
            <td class="item">
                @if (!$hideName)
                    {{ $item->name }}
                @endif

                @if (!$hideDescription)
                    @if (!empty($item->description))
                        <br><small>{!! \Illuminate\Support\Str::limit($item->description, 500) !!}</small>
                    @endif
                @endif

                @stack('item_custom_fields')
                @stack('item_custom_fields_' . $item->id)
            </td>
        @endif
    @stack('name_td_end')

    @stack('quantity_td_start')
        @if (!$hideQuantity)
            <td class="quantity">{{ $item->quantity }}</td>
        @endif
    @stack('quantity_td_end')

    @stack('price_td_start')
        @if (!$hidePrice)
            <td class="price">@money($item->price, $document->currency_code, true)</td>
        @endif
    @stack('price_td_end')

    @if (!$hideDiscount)
        @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
            @stack('discount_td_start')
                @if ($item->discount_type === 'percentage')
                    <td class="discount">{{ $item->discount }}</td>
                @else
                    <td class="discount">@money($item->discount, $document->currency_code, true)</td>
                @endif
            @stack('discount_td_end')
        @endif
    @endif

    @stack('total_td_start')
        @if (!$hideAmount)
            <td class="total">@money($item->total, $document->currency_code, true)</td>
        @endif
    @stack('total_td_end')
</tr>


<?php $currency_style  = false; ?>

@if (app()->getLocale() == "zh-CN" )
<?php $currency_style  = true; ?>
@endif

@if (app()->getLocale() == "ja-JP" )
<?php $currency_style  = true; ?>
@endif

@if (app()->getLocale() == "zh-TW" )
<?php $currency_style  = true; ?>
@endif


@if ($currency_style)
    @push('stylesheet')
    <style type="text/css">
        @font-face {
            font-family: 'Firefly Sung';
            font-weight: 'normal';
            src: url("/public/css/fonts/firefly_sung_normal.ttf") format("truetype");
        }

        @font-face {
            font-family: 'Firefly Sung';
            font-weight: 'bold';
            src: url("/public/css/fonts/firefly_sung_normal.ttf") format("truetype");
        }

        @font-face {
            font-family: 'Firefly Sung';
            font-weight: 'italic';
            src: url("/public/css/fonts/firefly_sung_normal.ttf") format("truetype");
        }

        body {
            font-family: 'Firefly Sung', sans-serif !important;
        }
    </style>
    @endpush
@endif
