<div class="print-template">
    <div class="row">
        <div class="col-100">
            <div class="text text-dark">
                @stack('title_input_start')
                <h3>
                    {{ $textDocumentTitle }}
                </h3>
                @stack('title_input_end')
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-58">
            <div class="text">
                @stack('company_logo_input_start')
                @if (! $hideCompanyLogo)
                    @if (!empty($document->contact->logo) && !empty($document->contact->logo->id))
                        <img  class="c-logo" src="{{ $logo }}" alt="{{ $document->contact_name }}"/>
                    @else
                        <img  class="c-logo" src="{{ $logo }}" alt="{{ setting('company.name') }}" />
                    @endif
                @endif
                @stack('company_logo_input_end')
            </div>
        </div>

        <div class="col-42">
            <div class="text right-column">
                @stack('company_details_start')
                @if ($textDocumentSubheading)
                    @stack('subheading_input_start')
                    <p class="text-normal font-semibold">
                        {{ $textDocumentSubheading }}
                    </p>
                    @stack('subheading_input_end')
                @endif

                @if (! $hideCompanyDetails)
                    @if (! $hideCompanyName)
                        <p>{{ setting('company.name') }}</p>
                    @endif

                    @if (! $hideCompanyAddress)
                        <p>
                            {!! nl2br(setting('company.address')) !!}
                            {!! $document->company->location !!}
                        </p>
                    @endif

                    @if (! $hideCompanyTaxNumber)
                        @if (setting('company.tax_number'))
                            <p>
                                <span class="font-semibold">
                                    {{ trans('general.tax_number') }}:
                                </span>
                                {{ setting('company.tax_number') }}
                            </p>
                        @endif
                    @endif

                    @if (! $hideCompanyPhone)
                        @if (setting('company.phone'))
                            <p>
                                {{ setting('company.phone') }}
                            </p>
                        @endif
                    @endif

                    @if (! $hideCompanyEmail)
                        <p class="small-text">
                            {{ setting('company.email') }}
                        </p>
                    @endif
                @endif
                @stack('company_details_end')
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-33">
            <div class="invoice-classic-line mb-1 mt-4" style="background-color:{{ $backgroundColor }}; -webkit-print-color-adjust: exact;"></div>
            <div class="invoice-classic-line" style="background-color:{{ $backgroundColor }}; -webkit-print-color-adjust: exact;"></div>
        </div>

        <div class="col-33">
            <div class="invoice-classic-frame ml-1 mt-1" style="border: 1px solid {{ $backgroundColor }}">
                <div class="invoice-classic-inline-frame text-center" style="border: 1px solid {{ $backgroundColor }}">
                    @stack('document_number_input_start')
                    @if (! $hideDocumentNumber)
                        <div class="text small-text font-semibold mt-classic">
                            <span>
                                {{ trans($textDocumentNumber) }}:
                            </span>

                            <br>

                            {{ $document->document_number }}
                        </div>
                    @endif
                    @stack('document_number_input_end')
                </div>
            </div>
        </div>

        <div class="col-33">
            <div class="invoice-classic-line mb-1 mt-4" style="background-color:{{ $backgroundColor }}; -webkit-print-color-adjust: exact;"></div>
            <div class="invoice-classic-line" style="background-color:{{ $backgroundColor }}; -webkit-print-color-adjust: exact;"></div>
        </div>
    </div>

    <div class="row top-spacing">
        <div class="col-60">
            <div class="text p-index-left break-words">
                @if (! $hideContactInfo)
                    <p class="font-semibold mb-0">
                        {{ trans($textContactInfo) }}
                    </p>
                @endif

                @stack('name_input_start')
                    @if (! $hideContactName)
                        @if ($print)
                            <p>
                                {{ $document->contact_name }}
                            </p>
                        @else
                            <x-link href="{{ route($showContactRoute, $document->contact_id) }}"
                                override="class"
                                class="py-1.5 mb-3 sm:mb-0 text-xs bg-transparent hover:bg-transparent font-medium leading-6"
                            >
                                <x-link.hover>
                                    {{ $document->contact_name }}
                                </x-link.hover>
                            </x-link>
                        @endif
                    @endif
                @stack('name_input_end')

                @stack('address_input_start')
                    @if (! $hideContactAddress)
                        <p>
                            {!! nl2br($document->contact_address) !!}
                            <br>
                            {!! $document->contact_location !!}
                        </p>
                    @endif
                @stack('address_input_end')

                @stack('tax_number_input_start')
                    @if (! $hideContactTaxNumber)
                        @if ($document->contact_tax_number)
                            <p>
                                <span class="font-semibold">
                                    {{ trans('general.tax_number') }}:
                                </span>
                                {{ $document->contact_tax_number }}
                            </p>
                        @endif
                    @endif
                @stack('tax_number_input_end')

                @stack('phone_input_start')
                    @if (! $hideContactPhone)
                        @if ($document->contact_phone)
                            <p>
                                {{ $document->contact_phone }}
                            </p>
                        @endif
                    @endif
                @stack('phone_input_end')

                @stack('email_input_start')
                    @if (! $hideContactEmail)
                        <p class="small-text">
                            {{ $document->contact_email }}
                        </p>
                    @endif
                @stack('email_input_end')
            </div>
        </div>

        <div class="col-40">
            <div class="text p-index-right">
                @stack('order_number_input_start')
                    @if (! $hideOrderNumber)
                        @if ($document->order_number)
                            <p class="mb-0">
                                <span class="font-semibold spacing">
                                    {{ trans($textOrderNumber) }}:
                                </span>

                                <span class="float-right spacing">
                                    {{ $document->order_number }}
                                </span>
                            </p>
                        @endif
                    @endif
                @stack('order_number_input_end')

                @stack('issued_at_input_start')
                    @if (! $hideIssuedAt)
                        <p class="mb-0">
                            <span class="font-semibold spacing">
                                {{ trans($textIssuedAt) }}:
                            </span>

                            <span class="float-right spacing">
                                @date($document->issued_at)
                            </span>
                        </p>
                    @endif
                @stack('issued_at_input_end')

                @stack('due_at_input_start')
                    @if (! $hideDueAt)
                        <p class="mb-0">
                            <span class="font-semibold spacing">
                                {{ trans($textDueAt) }}:
                            </span>

                            <span class="float-right spacing">
                                @date($document->due_at)
                            </span>
                        </p>
                    @endif
                @stack('due_at_input_end')

                @foreach ($document->totals_sorted as $total)
                    @if ($total->code == 'total')
                        <p class="mb-0">
                            <span class="font-semibold spacing">
                                {{ trans($total->name) }}:
                            </span>

                            <span class="float-right spacing">
                                <x-money :amount="$total->amount - $document->paid" :currency="$document->currency_code" />
                            </span>
                        </p>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    @if (! $hideItems)
        <div class="row">
            <div class="col-100">
                <div class="text extra-spacing">
                    <table class="c-lines">
                        <thead>
                            <tr>
                                @stack('name_th_start')
                                    @if (! $hideItems || (! $hideName && ! $hideDescription))
                                        <td class="item text font-semibold text-alignment-left text-left">
                                            {{ (trans_choice($textItems, 2) != $textItems) ? trans_choice($textItems, 2) : trans($textItems) }}
                                        </td>
                                    @endif
                                @stack('name_th_end')

                                @stack('quantity_th_start')
                                    @if (! $hideQuantity)
                                        <td class="quantity text font-semibold text-alignment-right text-right">
                                            {{ trans($textQuantity) }}
                                        </td>
                                    @endif
                                @stack('quantity_th_end')

                                @stack('price_th_start')
                                    @if (! $hidePrice)
                                        <td class="price text font-semibold text-alignment-right text-right">
                                            {{ trans($textPrice) }}
                                        </td>
                                    @endif
                                @stack('price_th_end')

                                @if (! $hideDiscount)
                                    @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                        @stack('discount_td_start')
                                            <td class="discount text font-semibold text-alignment-right text-right">
                                                {{ trans('invoices.discount') }}
                                            </td>
                                        @stack('discount_td_end')
                                    @endif
                                @endif

                                @stack('total_th_start')
                                    @if (! $hideAmount)
                                        <td class="total text font-semibold text-alignment-right text-right">
                                            {{ trans($textAmount) }}
                                        </td>
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
                                    <td colspan="5" class="text-center text empty-items">
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
        <div class="col-60 float-left">
            <div class="text p-index-right break-words">
                @stack('notes_input_start')
                    @if ($hideNote)
                        @if ($document->notes)
                            <strong>
                                {{ trans_choice('general.notes', 2) }}
                            </strong>

                            {!! nl2br($document->notes) !!}
                        @endif
                    @endif
                @stack('notes_input_end')
            </div>
        </div>

        <div class="col-40 float-right text-right">
            @foreach ($document->totals_sorted as $total)
                @if ($total->code != 'total')
                    @stack($total->code . '_total_tr_start')
                    <div class="text border-bottom-dashed py-1">
                        <strong class="float-left font-semibold">
                            {{ trans($total->title) }}:
                        </strong>

                        <span>
                            <x-money :amount="$total->amount" :currency="$document->currency_code" />
                        </span>
                    </div>
                    @stack($total->code . '_total_tr_end')
                @else
                    @if ($document->paid)
                        @stack('paid_total_tr_start')
                        <div class="text border-bottom-dashed py-1">
                            <span class="float-left font-semibold">
                                {{ trans('invoices.paid') }}:
                            </span>

                            <span>
                                - <x-money :amount="$document->paid" :currency="$document->currency_code" />
                            </span>
                        </div>
                        @stack('paid_total_tr_end')
                    @endif

                    @stack('grand_total_tr_start')
                    <div class="text border-bottom-dashed py-1">
                        <span class="float-left font-semibold">
                            {{ trans($total->name) }}:
                        </span>

                        <span>
                            <x-money :amount="$document->amount_due" :currency="$document->currency_code" />
                        </span>
                    </div>
                    @stack('grand_total_tr_end')
                @endif
            @endforeach
        </div>
    </div>

    @if (! $hideFooter)
        @if ($document->footer)
        @stack('footer_input_start')
            <div class="row mt-1">
                <div class="col-100">
                    <div class="text company">
                        <strong>
                            {!! nl2br($document->footer) !!}
                        </strong>
                    </div>
                </div>
            </div>
        @stack('footer_input_start')
        @endif
    @endif
</div>
