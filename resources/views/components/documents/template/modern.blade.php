<div class="row">
    <div class="col-100">
        <div class="text">
            <h3>
                {{ $textDocumentTitle }}
            </h3>

            @if ($textDocumentSubheading)
                <h5>
                    {{ $textDocumentSubheading }}
                </h5>
            @endif
        </div>
    </div>
</div>

<div class="row" style="background-color:{{ $backgroundColor }} !important; -webkit-print-color-adjust: exact;">
    <div class="col-58">
        <div class="text company pl-2 mb-1 d-flex align-items-center">
            @stack('company_logo_start')
            @if (!$hideCompanyLogo)
                @if (!empty($document->contact->logo) && !empty($document->contact->logo->id))
                    <img src="{{ $logo }}" alt="{{ $document->contact_name }}"/>
                @else
                    <img src="{{ $logo }}" alt="{{ setting('company.name') }}" />
                @endif

                @if (!$hideCompanyName)
                    <strong class="pl-2 text-white">{{ setting('company.name') }}</strong>
                @endif
            @endif
            @stack('company_logo_end')
        </div>
    </div>

    <div class="col-42">
        <div class="text company">
            @stack('company_details_start')
            @if (!$hideCompanyDetails)
                @if (!$hideCompanyAddress)
                    <strong class="text-white">{!! nl2br(setting('company.address')) !!}</strong><br><br>
                @endif

                @if (!$hideCompanyTaxNumber)
                    <strong class="text-white">
                        @if (setting('company.tax_number'))
                            {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                        @endif
                    </strong><br><br>
                @endif

                @if (!$hideCompanyPhone)
                    <strong class="text-white">
                        @if (setting('company.phone'))
                            {{ setting('company.phone') }}
                        @endif
                    </strong><br><br>
                @endif

                @if (!$hideCompanyEmail)
                    <strong class="text-white">{{ setting('company.email') }}</strong><br><br>
                @endif
            @endif
            @stack('company_details_end')
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-58">
        <div class="text company">
            @if (!$hideContactInfo)
                <strong>{{ trans($textContactInfo) }}</strong>
                <br>
            @endif

            @stack('name_input_start')
                @if (!$hideContactName)
                    <strong>{{ $document->contact_name }}</strong>
                    <br><br>
                @endif
            @stack('name_input_end')

            @stack('address_input_start')
                @if (!$hideContactAddress)
                    {!! nl2br($document->contact_address) !!}
                    <br><br>
                @endif
            @stack('address_input_end')

            @stack('tax_number_input_start')
                @if (!$hideContactTaxNumber)
                    @if ($document->contact_tax_number)
                        {{ trans('general.tax_number') }}: {{ $document->contact_tax_number }}
                        <br><br>
                    @endif
                @endif
            @stack('tax_number_input_end')

            @stack('phone_input_start')
                @if (!$hideContactPhone)
                    @if ($document->contact_phone)
                        {{ $document->contact_phone }}
                        <br><br>
                    @endif
                @endif
            @stack('phone_input_end')

            @stack('email_start')
                @if (!$hideContactEmail)
                    {{ $document->contact_email }}
                    <br><br>
                @endif
            @stack('email_input_end')
        </div>
    </div>

    <div class="col-42">
        <div class="text company">
            @stack('order_number_input_start')
                @if (!$hideOrderNumber)
                    @if ($document->order_number)
                        <strong>{{ trans($textOrderNumber) }}:</strong>
                        <span class="float-right">{{ $document->order_number }}</span><br><br>
                    @endif
                @endif
            @stack('order_number_input_end')

            @stack('invoice_number_input_start')
                @if (!$hideDocumentNumber)
                    <strong>{{ trans($textDocumentNumber) }}:</strong>
                    <span class="float-right">{{ $document->document_number }}</span><br><br>
                @endif
            @stack('invoice_number_input_end')

            @stack('issued_at_input_start')
                @if (!$hideIssuedAt)
                    <strong>{{ trans($textIssuedAt) }}:</strong>
                    <span class="float-right">@date($document->issued_at)</span><br><br>
                @endif
            @stack('issued_at_input_end')

            @stack('due_at_input_start')
                @if (!$hideDueAt)
                    <strong>{{ trans($textDueAt) }}:</strong>
                    <span class="float-right">@date($document->due_at)</span>
                @endif
            @stack('due_at_input_end')
        </div>
    </div>
</div>

@if (!$hideItems)
    <div class="row">
        <div class="col-100">
            <div class="text">
                <table class="m-lines">
                    <thead style="background-color:{{ $backgroundColor }} !important; -webkit-print-color-adjust: exact;">
                        <tr>
                            @stack('name_th_start')
                                @if (!$hideItems || (!$hideName && !$hideDescription))
                                    <th class="item text-left text-white">{{ (trans_choice($textItems, 2) != $textItems) ? trans_choice($textItems, 2) : trans($textItems) }}</th>
                                @endif
                            @stack('name_th_end')

                            @stack('quantity_th_start')
                                @if (!$hideQuantity)
                                    <th class="quantity text-white">{{ trans($textQuantity) }}</th>
                                @endif
                            @stack('quantity_th_end')

                            @stack('price_th_start')
                                @if (!$hidePrice)
                                    <th class="price text-white">{{ trans($textPrice) }}</th>
                                @endif
                            @stack('price_th_end')

                            @if (!$hideDiscount)
                                @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                    @stack('discount_td_start')
                                        <th class="discount text-white">{{ trans('invoices.discount') }}</th>
                                    @stack('discount_td_end')
                                @endif
                            @endif

                            @stack('total_th_start')
                                @if (!$hideAmount)
                                    <th class="total text-white">{{ trans($textAmount) }}</th>
                                @endif
                            @stack('total_th_end')
                        </tr>
                    </thead>
                    <tbody>
                        @if ($document->items->count())
                            @foreach($document->items as $item)
                                <x-documents.template.line-item
                                    type="{{ $type }}"
                                    :item="$item"
                                    :document="$document"
                                    hide-items="{{ $hideItems }}"
                                    hide-name="{{ $hideName }}"
                                    hide-description="{{ $hideDescription }}"
                                    hide-quantity="{{ $hideQuantity }}"
                                    hide-price="{{ $hidePrice }}"
                                    hide-discount="{{ $hideDiscount }}"
                                    hide-amount="{{ $hideAmount }}"
                                />
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center empty-items">
                                    {{ trans('documents.empty_items') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="row mt-7">
    <div class="col-58">
        <div class="text company">
            @stack('notes_input_start')
                @if($hideNote)
                    @if ($document->notes)
                        <strong>{{ trans_choice('general.notes', 2) }}</strong><br><br>
                        {!! nl2br($document->notes) !!}
                    @endif
                @endif
            @stack('notes_input_end')
        </div>
    </div>

    <div class="col-42 float-right text-right">
        <div class="text company pr-2">
            @foreach ($document->totals_sorted as $total)
                @if ($total->code != 'total')
                    @stack($total->code . '_total_tr_start')
                    <strong class="float-left">{{ trans($total->title) }}:</strong>
                    <span>@money($total->amount, $document->currency_code, true)</span><br><br>
                    @stack($total->code . '_total_tr_end')
                @else
                    @if ($document->paid)
                        @stack('paid_total_tr_start')
                        <strong class="float-left">{{ trans('invoices.paid') }}:</strong>
                        <span>- @money($document->paid, $document->currency_code, true)</span><br><br>
                        @stack('paid_total_tr_end')
                    @endif
                    @stack('grand_total_tr_start')
                        <strong class="float-left">{{ trans($total->name) }}:</strong>
                        <span>@money($document->amount_due, $document->currency_code, true)</span>
                    @stack('grand_total_tr_end')
                @endif
            @endforeach
        </div>
    </div>
</div>

@if (!$hideFooter)
    @if ($document->footer)
        <div class="row mt-7">
            <div class="col-100 py-2" style="background-color:{{ $backgroundColor }} !important; -webkit-print-color-adjust: exact;">
                <div class="text pl-2">
                    <strong class="text-white">{!! nl2br($document->footer) !!}</strong>
                </div>
            </div>
        </div>
    @endif
@endif
