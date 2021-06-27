<div class="card show-card" style="padding: 0; padding-left: 15px; padding-right: 15px; border-radius: 0; box-shadow: 0 4px 16px rgba(0,0,0,.2);">
    <div class="card-body show-card-body">
        @if ($transactionTemplate)
            @switch($transactionTemplate)
                @case('classic')
                    @break
                @case('modern')
                    @break  
                @default
                    <x-transactions.template.ddefault
                        type="{{ $type }}"
                        :transaction="$transaction"
                        :payment_methods="$payment_methods"
                        transaction-template="{{ $transactionTemplate }}"
                        logo="{{ $logo }}"

                        hide-company="{{ $hideCompany }}"
                        hide-company-logo="{{ $hideCompanyLogo }}"
                        hide-company-details="{{ $hideCompanyDetails }}"
                        hide-company-name="{{ $hideCompanyName }}"
                        hide-company-address="{{ $hideCompanyAddress }}"
                        hide-company-tax-number="{{ $hideCompanyTaxNumber }}"
                        hide-company-phone="{{ $hideCompanyPhone }}"
                        hide-company-email="{{ $hideCompanyEmail }}"

                        hide-content-title="{{ $hideContentTitle }}"
                        hide-paid-at="{{ $hidePaidAt }}"
                        hide-account="{{ $hideAccount }}"
                        hide-category="{{ $hideCategory }}"
                        hide-payment-methods="{{ $hidePaymentMethods }}"
                        hide-reference="{{ $hideReference }}"
                        hide-description="{{ $hideDescription }}"
                        hide-amount="{{ $hideAmount }}"

                        text-content-title="{{ $textContentTitle }}"
                        text-paid-at="{{ $textPaidAt }}"
                        text-account="{{ $textAccount }}"
                        text-category="{{ $textCategory }}"
                        text-payment-methods="{{ $textPaymentMethods }}"
                        text-reference="{{ $textReference }}"
                        text-description="{{ $textDescription }}"
                        text-paid-by="{{ $textPaidBy }}"

                        hide-contact="{{ $hideContact }}"
                        hide-contact-info="{{ $hideContactInfo }}"
                        hide-contact-name="{{ $hideContactName }}"
                        hide-contact-address="{{ $hideContactAddress }}"
                        hide-contact-tax-number="{{ $hideContactTaxNumber }}"
                        hide-contact-phone="{{ $hideContactPhone }}"
                        hide-contact-email="{{ $hideContactEmail }}"

                        hide-releated="{{ $hideReletad }}"
                        hide-releated-document-number="{{ $hideReletadDocumentNumber }}"
                        hide-releated-contact="{{ $hideReletadContact }}"
                        hide-releated-document-date="{{ $hideReletadDocumentDate }}"
                        hide-releated-document-amount="{{ $hideReletadDocumentAmount }}"
                        hide-releated-amount="{{ $hideReletadAmount }}"

                        text-releated-transaction="{{ $textReleatedTransansaction }}"
                        text-releated-document-number="{{ $textReleatedDocumentNumber }}"
                        text-releated-contact="{{ $textReleatedContact }}"
                        text-releated-document-date="{{ $textReleatedDocumentDate }}"
                        text-releated-document-amount="{{ $textReleatedDocumentAmount }}"
                        text-releated-amount="{{ $textReleatedAmount }}"

                        route-document-show="{{ $routeDocumentShow }}"
                    />
            @endswitch
        @else
            @include($transactionTemplate)
        @endif
    </div>
</div>
