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

    <div class="row border-bottom-1">
        <div class="col-58">
            <div class="text">
                @stack('company_logo_input_start')
                @if (! $hideCompanyLogo)
                    @if (! empty($document->contact->logo) && ! empty($document->contact->logo->id))
                        <img class="d-logo" src="{{ $logo }}" alt="{{ $document->contact_name }}"/>
                    @else
                        <img class="d-logo" src="{{ $logo }}" alt="{{ setting('company.name') }}"/>
                    @endif
                @endif
                @stack('company_logo_input_end')
            </div>
        </div>

        <div class="col-42">
            <div class="text right-column">
                @stack('company_details_start')
                @if ($textDocumentSubheading)
                    <p class="text-normal font-semibold">
                        {{ $textDocumentSubheading }}
                    </p>
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
                                {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
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
                        <p class="small-text">{{ setting('company.email') }}</p>
                    @endif
                @endif
                @stack('company_details_end')
            </div>
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

                @stack('email_start')
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
                @stack('document_number_input_start')
                    @if (! $hideDocumentNumber)
                        <p class="mb-0">
                            <span class="font-semibold spacing w-numbers">
                                {{ trans($textDocumentNumber) }}:
                            </span>

                            <span class="float-right spacing">
                                {{ $document->document_number }}
                            </span>
                        </p>
                    @endif
                @stack('document_number_input_end')

                @stack('order_number_input_start')
                    @if (! $hideOrderNumber)
                        @if ($document->order_number)
                            <p class="mb-0">
                                <span class="font-semibold spacing w-numbers">
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
                            <span class="font-semibold spacing w-numbers">
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
                            <span class="font-semibold spacing w-numbers">
                                {{ trans($textDueAt) }}:
                            </span>

                            <span class="float-right spacing">
                                @date($document->due_at)
                            </span>
                        </p>
                    @endif
                @stack('due_at_input_end')
            </div>
        </div>
    </div>

    @if (! $hideItems)
        <div class="row">
            <div class="col-100">
                <div class="text extra-spacing">
                    <table class="lines lines-radius-border">
                        <thead style="background-color:{{ $backgroundColor }} !important; -webkit-print-color-adjust: exact;">
                            <tr>
                                @stack('name_th_start')
                                    @if (! $hideItems || (! $hideName && ! $hideDescription))
                                        <td class="item text font-semibold text-alignment-left text-left text-white">
                                            <span>
                                                {{ (trans_choice($textItems, 2) != $textItems) ? trans_choice($textItems, 2) : trans($textItems) }}
                                            </span>
                                        </td>
                                    @endif
                                @stack('name_th_end')

                                @stack('quantity_th_start')
                                    @if (! $hideQuantity)
                                        <td class="quantity text font-semibold text-alignment-right text-right text-white">
                                            <span>
                                                {{ trans($textQuantity) }}
                                            </span>
                                        </td>
                                    @endif
                                @stack('quantity_th_end')

                                @stack('price_th_start')
                                    @if (! $hidePrice)
                                        <td class="price text font-semibold text-alignment-right text-right text-white">
                                            <span>
                                                {{ trans($textPrice) }}
                                            </span>
                                        </td>
                                    @endif
                                @stack('price_th_end')

                                @if (! $hideDiscount)
                                    @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                        @stack('discount_td_start')
                                            <td class="discount text font-semibold text-alignment-right text-right text-white">
                                                <span>
                                                    {{ trans('invoices.discount') }}
                                                </span>
                                            </td>
                                        @stack('discount_td_end')
                                    @endif
                                @endif

                                @stack('total_th_start')
                                    @if (! $hideAmount)
                                        <td class="total text font-semibold text-white text-alignment-right text-right">
                                            <span>
                                                {{ trans($textAmount) }}
                                            </span>
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
                                    <td colspan="5" class="text text-center empty-items">
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

    <div class="row mt-9 clearfix">
        <div class="col-60 float-left">
            <div class="text p-index-left break-words">
                @stack('notes_input_start')
                    @if ($document->notes)
                        <p class="font-semibold">
                            {{ trans_choice('general.notes', 2) }}
                        </p>

                        {!! nl2br($document->notes) !!}
                    @endif
                @stack('notes_input_end')
            </div>
        </div>

        <div class="col-40 float-right text-right">
            @foreach ($document->totals_sorted as $total)
                @if ($total->code != 'total')
                    @stack($total->code . '_total_tr_start')
                    <div class="text border-bottom-1 py-1">
                        <span class="float-left font-semibold">
                            {{ trans($total->title) }}:
                        </span>

                        <span>
                            <x-money :amount="$total->amount" :currency="$document->currency_code" />
                        </span>
                    </div>
                    @stack($total->code . '_total_tr_end')
                @else
                    @if ($document->paid)
                        @stack('paid_total_tr_start')
                        <div class="text border-bottom-1 py-1">
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
                    <div class="text border-bottom-1 py-1">
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
            <div class="row mt-4">
                <div class="col-100 text-left">
                    <div class="text">
                        <span class="font-bold">
                            {!! nl2br($document->footer) !!}
                        </span>
                    </div>
                </div>
            </div>
        @stack('footer_input_end')
        @endif
    @endif
</div>
