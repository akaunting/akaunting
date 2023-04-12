<div class="relative sm:col-span-6 overflow-x-scroll large-overflow-unset">
    <div style="table-layout: fixed;">
        <div class="overflow-x-visible overflow-y-hidden">
            <table class="small-table-width" id="items">
                <colgroup>
                    <col class="small-col" style="width: 24px;">
                    <col class="small-col name-col" style="width: 20%;">
                    <col class="small-col description-col" style="width: 30%;">
                    <col class="small-col" style="width: 12%;">
                    <col class="small-col" style="width: 15%;">
                    <col class="small-col amount-col" style="width: 20%;">
                    <col class="small-col" style="width: 24px;">
                </colgroup>

                <thead class="border-b">
                    <tr>
                        @stack('move_th_start')

                        <th class="w-6 block text-left border-t-0 border-r-0 border-b-0" style="vertical-align:bottom;">
                            @if (! $hideEditItemColumns)
                                <x-documents.form.item-columns :type="$type" />
                            @endif
                        </th>

                        @stack('move_th_end')

                        @if (! $hideItems)
                            @stack('name_th_start')

                                <th class="px-3 py-1 ltr:pl-2 rtl:pr-2 ltr:text-left rtl:text-right text-xs font-normal border-t-0 border-r-0 border-b-0" style="vertical-align:bottom;">
                                    @if (! $hideItemName)
                                        {{ (trans_choice($textItemName, 2) != $textItemName) ? trans_choice($textItemName, 2) : trans($textItemName) }}
                                    @endif
                                </th>

                            @stack('name_th_end')

                            @stack('description_th_start')

                            <th class="px-3 py-1 text-left text-xs font-normal border-t-0 border-r-0 border-b-0" style=" vertical-align:bottom;">
                                @if (! $hideItemDescription)
                                    {{ trans($textItemDescription) }}
                                @endif
                            </th>
                            
                            @stack('description_th_end')
                        @endif

                        @stack('quantity_th_start')

                        <th class="px-3 py-1 ltr:text-left rtl:text-right text-xs font-normal border-t-0 border-r-0 border-b-0" style="vertical-align:bottom;">
                            @if (! $hideItemQuantity)
                                {{ trans($textItemQuantity) }}
                            @endif
                        </th>

                        @stack('quantity_th_end')

                        @stack('price_th_start')

                        <th class="px-3 py-1 ltr:text-left rtl:text-right text-xs font-normal border-t-0 border-r-0 border-b-0 pr-1" style="vertical-align:bottom;">
                            {{ trans($textItemPrice) }}

                            @if ($hideItemPrice)
                                &nbsp;&nbsp;
                                <x-tooltip id="tooltip-item-price" placement="top" message="{{ trans('documents.item_price_hidden', ['type' => config('type.document.' . $type . '.translation.prefix')]) }}">
                                    <x-icon icon="visibility_off" class="text-sm font-normal"></x-icon>
                                </x-tooltip>
                            @endif
                        </th>
                        
                        @stack('price_th_end')

                        @stack('total_th_start')

                        <th class="px-3 py-1 ltr:text-right rtl:text-left text-xs font-normal border-t-0 border-b-0 item-total" style="vertical-align:bottom;">
                            @if (! $hideItemAmount)
                                {{ trans($textItemAmount) }}
                            @endif
                        </th>
                        
                        @stack('total_th_end')

                        @stack('remove_th_start')

                        <th class="border-t-0 border-r-0 border-b-0 align-bottom" style="width:24px; display:block;">
                            <div></div>
                        </th>

                        @stack('remove_th_end')
                    </tr>
                </thead>

                <tbody id="{{ (! $hideDiscount && in_array(setting('localisation.discount_location', 'total'), ['item', 'both'])) ? 'invoice-item-discount-rows' : 'invoice-item-rows' }}" class="table-padding-05">
                    <x-documents.form.line-item :type="$type" />

                    @stack('add_item_td_start')

                    <tr id="addItem">
                        <td colspan="7">
                            <x-documents.form.item-button
                                type="{{ $type }}"
                                is-sale="{{ $isSalePrice }}"
                                is-purchase="{{ $isPurchasePrice }}"
                                search-char-limit="{{ $searchCharLimit }}"
                            />
                        </td>
                    </tr>

                    @stack('add_item_td_end')
                </tbody>
            </table>
        </div>
    </div>
</div>
