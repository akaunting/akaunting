@stack('content_header_start')
@if (!$hideHeader)
    <x-transactions.show.header
        type="{{ $type }}"
        :transaction="$transaction"
        hide-header-account="{{ $hideHeaderAccount }}"
        text-header-account="{{ $textHeaderAccount }}"
        class-header-account="{{ $classHeaderAccount }}"
        hide-header-category="{{ $hideHeaderCategory }}"
        text-header-category="{{ $textHeaderCategory }}"
        class-header-category="{{ $classHeaderCategory }}"
        hide-header-contact="{{ $hideHeaderContact }}"
        text-header-contact="{{ $textHeaderContact }}"
        class-header-contact="{{ $classHeaderContact }}"
        hide-header-amount="{{ $hideHeaderAmount }}"
        text-header-amount="{{ $textHeaderAmount }}"
        class-header-amount="{{ $classHeaderAmount }}"
        hide-header-paid-at="{{ $hideHeaderPaidAt }}"
        text-header-paid-at="{{ $textHeaderPaidAt }}"
        class-header-paid-at="{{ $classHeaderPaidAt }}"
    />
@endif
@stack('content_header_end')

@stack('transaction_start')
    <x-transactions.show.transaction
        type="{{ $type }}"
        :transaction="$transaction"
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
@stack('transaction_end')

@stack('attachment_start')
    @if (!$hideAttachment)
        <x-transactions.show.attachment
            type="{{ $type }}"
            :transaction="$transaction"
            :attachment="$attachment"
        />
    @endif
@stack('attachment_end')

@stack('row_footer_start')
    @if (!$hideFooter)
        <x-transactions.show.footer
            type="{{ $type }}"
            :transaction="$transaction"
            :histories="$histories"
            class-footer-histories="{{ $classFooterHistories }}"
            hide-footer-histories="{{ $hideFooterHistories }}"
            text-histories="{{ $textHistories }}"
        />
    @endif
@stack('row_footer_end')

{{ Form::hidden('transaction_id', $transaction->id, ['id' => 'transaction_id']) }}
{{ Form::hidden($type . '_id', $transaction->id, ['id' => $type . '_id']) }}
