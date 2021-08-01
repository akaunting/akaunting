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

<div class="row">
    <div class="col-58">
        <div class="text company">
            @stack('company_logo_start')
            @if (!$hideCompanyLogo)
                @if (!empty($document->contact->logo) && !empty($document->contact->logo->id))
                    <img  class="c-logo" src="{{ $logo }}" alt="{{ $document->contact_name }}"/>
                @else
                    <img  class="c-logo" src="{{ $logo }}" alt="{{ setting('company.name') }}" />
                @endif
            @endif
            @stack('company_logo_end')
        </div>
    </div>

    <div class="col-42">
        <div class="text company">
            @stack('company_details_start')
            @if (!$hideCompanyDetails)
                @if (!$hideCompanyName)
                    <strong>{{ setting('company.name') }}</strong><br>
                @endif

                @if (!$hideCompanyAddress)
                    <p>{!! nl2br(setting('company.address')) !!}</p>
                @endif

                @if (!$hideCompanyTaxNumber)
                    <p>
                        @if (setting('company.tax_number'))
                            {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                        @endif
                    </p>
                @endif

                @if (!$hideCompanyPhone)
                    <p>
                        @if (setting('company.phone'))
                            {{ setting('company.phone') }}
                        @endif
                    </p>
                @endif

                @if (!$hideCompanyEmail)
                    <p>{{ setting('company.email') }}</p>
                @endif
            @endif
            @stack('company_details_end')
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-33">
        <hr class="invoice-classic-line mb-1 mt-4" style="background-color:{{ $backgroundColor }};">
        <hr class="invoice-classic-line" style="background-color:{{ $backgroundColor }};">
    </div>

    <div class="col-33">
        <div class="invoice-classic-frame ml-1">
            <div class="invoice-classic-inline-frame text-center">
                @stack('invoice_number_input_start')
                @if (!$hideDocumentNumber)
                    <div class="text company">
                        <strong>{{ trans($textDocumentNumber) }}:</strong><br>
                        {{ $document->document_number }}
                    </div>
                @endif
                @stack('invoice_number_input_end')
            </div>
        </div>
    </div>

    <div class="col-33">
        <hr class="invoice-classic-line mb-1 mt-4" style="background-color:{{ $backgroundColor }};">
        <hr class="invoice-classic-line" style="background-color:{{ $backgroundColor }};">
    </div>
</div>

<div class="row mt-2">
    <div class="col-58">
        <div class="text company">
            @if (!$hideContactInfo)
                <strong>{{ trans($textContactInfo) }}</strong><br>
            @endif

            @stack('name_input_start')
                @if (!$hideContactName)
                    <strong>{{ $document->contact_name }}</strong><br>
                @endif
            @stack('name_input_end')

            @stack('address_input_start')
                @if (!$hideContactAddress)
                    <p>{!! nl2br($document->contact_address) !!}</p>
                @endif
            @stack('address_input_end')

            @stack('tax_number_input_start')
                @if (!$hideContactTaxNumber)
                    <p>
                        @if ($document->contact_tax_number)
                            {{ trans('general.tax_number') }}: {{ $document->contact_tax_number }}
                        @endif
                    </p>
                @endif
            @stack('tax_number_input_end')

            @stack('phone_input_start')
                @if (!$hideContactPhone)
                    <p>
                        @if ($document->contact_phone)
                            {{ $document->contact_phone }}
                        @endif
                    </p>
                @endif
            @stack('phone_input_end')

            @stack('email_start')
                @if (!$hideContactEmail)
                    <p>{{ $document->contact_email }}</p>
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

            @stack('issued_at_input_start')
                @if (!$hideIssuedAt)
                    <strong>{{ trans($textIssuedAt) }}:</strong>
                    <span class="float-right">@date($document->issued_at)</span><br><br>
                @endif
            @stack('issued_at_input_end')

            @stack('due_at_input_start')
                @if (!$hideDueAt)
                    <strong>{{ trans($textDueAt) }}:</strong>
                    <span class="float-right">@date($document->due_at)</span><br><br>
                @endif
            @stack('due_at_input_end')

                @foreach ($document->totals_sorted as $total)
                    @if ($total->code == 'total')
                        <strong>{{ trans($total->name) }}:</strong>
                        <span class="float-right">@money($total->amount - $document->paid, $document->currency_code, true)</span><br><br>
                    @endif
                @endforeach
        </div>
    </div>
</div>

@if (!$hideItems)
    <div class="row">
        <div class="col-100">
            <div class="text">
                <table class="c-lines">
                    <thead>
                        <tr>
                            @stack('name_th_start')
                                @if (!$hideItems || (!$hideName && !$hideDescription))
                                    <th class="text-left item">{{ (trans_choice($textItems, 2) != $textItems) ? trans_choice($textItems, 2) : trans($textItems) }}</th>
                                @endif
                            @stack('name_th_end')

                            @stack('quantity_th_start')
                                @if (!$hideQuantity)
                                    <th class="quantity">{{ trans($textQuantity) }}</th>
                                @endif
                            @stack('quantity_th_end')

                            @stack('price_th_start')
                                @if (!$hidePrice)
                                    <th class="price">{{ trans($textPrice) }}</th>
                                @endif
                            @stack('price_th_end')

                            @if (!$hideDiscount)
                                @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                    @stack('discount_td_start')
                                        <th class="discount">{{ trans('invoices.discount') }}</th>
                                    @stack('discount_td_end')
                                @endif
                            @endif

                            @stack('total_th_start')
                                @if (!$hideAmount)
                                    <th class="total">{{ trans($textAmount) }}</th>
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

<div class="row mt-4 clearfix">
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
                    <div class="border-top-dashed py-2">
                        <strong class="float-left">{{ trans($total->title) }}:</strong>
                        <span>@money($total->amount, $document->currency_code, true)</span>
                    </div>
                    @stack($total->code . '_total_tr_end')
                @else
                    @if ($document->paid)
                        @stack('paid_total_tr_start')
                        <div class="border-top-dashed py-2">
                            <strong class="float-left">{{ trans('invoices.paid') }}:</strong>
                            <span>- @money($document->paid, $document->currency_code, true)</span>
                        </div>
                        @stack('paid_total_tr_end')
                    @endif
                    @stack('grand_total_tr_start')
                    <div class="border-top-dashed py-2">
                        <strong class="float-left">{{ trans($total->name) }}:</strong>
                        <span>@money($document->amount_due, $document->currency_code, true)</span>
                    </div>
                    @stack('grand_total_tr_end')
                @endif
            @endforeach
        </div>
    </div>
</div>

@if (!$hideFooter)
    @if ($document->footer)
        <div class="row mt-1">
            <div class="col-100">
                <div class="text company">
                    <strong>{!! nl2br($document->footer) !!}</strong>
                </div>
            </div>
        </div>
    @endif
@endif
