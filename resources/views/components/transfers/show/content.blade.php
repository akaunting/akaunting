@stack('content_header_start')
@if (!$hideHeader)
    <x-transfers.show.header
        :transfer="$transfer"
        hide-header-from-account="{{ $hideHeaderFromAccount }}"
        text-header-from-account="{{ $textHeaderFromAccount }}"
        class-header-from-account="{{ $classHeaderFromAccount }}"
        hide-header-to-account="{{ $hideHeaderToAccount }}"
        text-header-to-account="{{ $textHeaderToAccount }}"
        class-header-to-account="{{ $classHeaderToAccount }}"
        route-from-account-show="{{ $routeFromAccountShow }}"
        route-to-account-show="{{ $routeToAccountShow }}"
        hide-header-amount="{{ $hideHeaderAmount }}"
        text-header-amount="{{ $textHeaderAmount }}"
        class-header-amount="{{ $classHeaderAmount }}"
        hide-header-paid-at="{{ $hideHeaderPaidAt }}"
        text-header-paid-at="{{ $textHeaderPaidAt }}"
        class-header-paid-at="{{ $classHeaderPaidAt }}"
    />
@endif
@stack('content_header_end')

@stack('transfer_start')
    <x-transfers.show.transfer
        :transfer="$transfer"
        transfer-template="{{ $transferTemplate }}"

        hide-from-account="{{ $hideFromAccount }}"
        hide-from-account-title="{{ $hideFromAccountTitle }}"
        hide-from-account-name="{{ $hideFromAccountName }}"
        hide-from-account-number="{{ $hideFromAccountNumber }}"
        hide-from-account-bank-name="{{ $hideFromAccountBankName }}"
        hide-from-account-bank-phone="{{ $hideFromAccountBankPhone }}"
        hide-from-account-bank-address="{{ $hideFromAccountBankAddress }}"

        text-from-account-title="{{ $textFromAccountTitle }}"
        text-from-account-number="{{ $textFromAccountNumber }}"

        hide-to-account="{{ $hideToAccount }}"
        hide-to-account-title="{{ $hideToAccountTitle }}"
        hide-to-account-name="{{ $hideToAccountName }}"
        hide-to-account-number="{{ $hideToAccountNumber }}"
        hide-to-account-bank-name="{{ $hideToAccountBankName }}"
        hide-to-account-bank-phone="{{ $hideToAccountBankPhone }}"
        hide-to-account-bank-address="{{ $hideToAccountBankAddress }}"

        text-to-account-title="{{ $textToAccountTitle }}"
        text-to-account-number="{{ $textToAccountNumber }}"

        hide-details="{{ $hideDetails }}"
        hide-detail-title="{{ $hideDetailTitle }}"
        hide-detail-date="{{ $hideDetailDate }}"
        hide-detail-payment-method="{{ $hideDetailPaymentMethod }}"
        hide-detail-reference="{{ $hideDetailReference }}"
        hide-detail-description="{{ $hideDetailDescription }}"
        hide-detail-amount="{{ $hideDetailAmount }}"

        text-detail-title="{{ $textDetailTitle }}"
        text-detail-date="{{ $textDetailDate }}"
        text-detail-payment-method="{{ $textDetailPaymentMethod }}"
        text-detail-reference="{{ $textDetailReference }}"
        text-detail-description="{{ $textDetailDescription }}"
        text-detail-amount="{{ $textDetailAmount }}"
    />
@stack('transfer_end')

@stack('attachment_start')
    @if (!$hideAttachment)
        <x-transfers.show.attachment
            :transfer="$transfer"
            :attachment="$attachment"
        />
    @endif
@stack('attachment_end')

@stack('row_footer_start')
    @if (!$hideFooter)
        <x-transfers.show.footer
            :transfer="$transfer"
            :histories="$histories"
            class-footer-histories="{{ $classFooterHistories }}"
            hide-footer-histories="{{ $hideFooterHistories }}"
            text-histories="{{ $textHistories }}"
        />
    @endif
@stack('row_footer_end')

{{ Form::hidden('transfer_id', $transfer->id, ['id' => 'transfer_id']) }}
