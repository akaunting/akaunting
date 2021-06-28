@stack('content_header_start')
@if (!$hideHeader)
    <x-documents.show.header
        type="{{ $type }}"
        :document="$document"
        hide-header-status="{{ $hideHeaderStatus }}"
        text-history-status="{{ $textHistoryStatus }}"
        class-header-status="{{ $classHeaderStatus }}"
        hide-header-contact="{{ $hideHeaderContact }}"
        text-header-contact="{{ $textHeaderContact }}"
        class-header-contact="{{ $classHeaderContact }}"
        route-contact-show="{{ $routeContactShow }}"
        hide-header-amount="{{ $hideHeaderAmount }}"
        text-header-amount="{{ $textHeaderAmount }}"
        class-header-amount="{{ $classHeaderAmount }}"
        hide-header-due-at="{{ $hideHeaderDueAt }}"
        text-header-due-at="{{ $textHeaderDueAt }}"
        class-header-due-at="{{ $classHeaderDueAt }}"
    />
@endif
@stack('content_header_end')

@stack('recurring_message_start')
@if (!$hideRecurringMessage)
    <x-documents.show.recurring-message
        type="{{ $type }}"
        :document="$document"
        text-recurring-type="{{ $textRecurringType }}"
     />
@endif
@stack('recurring_message_end')

@stack('status_message_start')
@if (!$hideStatusMessage)
    <x-documents.show.status-message
        type="{{ $type }}"
        :document="$document"
        text-status-message="{{ $textStatusMessage }}"
    />
@endif
@stack('status_message_end')

@stack('timeline_start')
    @if (!$hideTimeline)
        <x-documents.show.timeline
            type="{{ $type }}"
            :document="$document"
            :hide-timeline-statuses="$hideTimelineStatuses"
            hide-timeline-create="{{ $hideTimelineCreate }}"
            text-timeline-create-title="{{ $textTimelineCreateTitle }}"
            text-timeline-create-message="{{ $textTimelineCreateMessage }}"
            hide-button-edit="{{ $hideButtonEdit }}"
            permission-update="{{ $permissionUpdate }}"
            route-button-edit="{{ $routeButtonEdit }}"
            hide-timeline-sent="{{ $hideTimelineSent }}"
            text-timeline-sent-title="{{ $textTimelineSentTitle }}"
            text-timeline-sent-status-draft="{{ $textTimelineSentStatusDraft }}"
            hide-button-sent="{{ $hideButtonSent }}"
            route-button-sent="{{ $routeButtonSent }}"
            text-timeline-sent-status-mark-sent="{{ $textTimelineSentStatusMarkSent }}"
            hide-button-received="{{ $hideButtonReceived }}"
            route-button-received="{{ $routeButtonReceived }}"
            text-timeline-sent-status-received="{{ $textTimelineSentStatusReceived }}"
            hide-button-email="{{ $hideButtonEmail }}"
            route-button-email="{{ $routeButtonEmail }}"
            text-timeline-send-status-mail="{{ $textTimelineSendStatusMail }}"
            hide-button-share="{{ $hideButtonShare }}"
            :signed-url="$signedUrl"
            hide-timeline-paid="{{ $hideTimelinePaid }}"
            text-timeline-get-paid-title="{{ $textTimelineGetPaidTitle }}"
            text-timeline-get-paid-status-await="{{ $textTimelineGetPaidStatusAwait }}"
            text-timeline-get-paid-status-partially-paid="{{ $textTimelineGetPaidStatusPartiallyPaid }}"
            hide-button-paid="{{ $hideButtonPaid }}"
            route-button-paid="{{ $routeButtonPaid }}"
            text-timeline-get-paid-mark-paid="{{ $textTimelineGetPaidMarkPaid }}"
            hide-button-add-payment="{{ $hideButtonAddPayment }}"
            text-timeline-get-paid-add-payment="{{ $textTimelineGetPaidAddPayment }}"
        />
    @endif
@stack('timeline_end')

@stack('document_start')
    <x-documents.show.document
        type="{{ $type }}"
        :document="$document"
        document-template="{{ $documentTemplate }}"
        logo="{{ $logo }}"
        background-color="{{ $backgroundColor }}"
        hide-footer="{{ $hideFooter }}"
        hide-company-logo="{{ $hideCompanyLogo }}"
        hide-company-details="{{ $hideCompanyDetails }}"
        hide-company-name="{{ $hideCompanyName }}"
        hide-company-address="{{ $hideCompanyAddress }}"
        hide-company-tax-number="{{ $hideCompanyTaxNumber }}"
        hide-company-phone="{{ $hideCompanyPhone }}"
        hide-company-email="{{ $hideCompanyEmail }}"
        hide-contact-info="{{ $hideContactInfo }}"
        hide-contact-name="{{ $hideContactName }}"
        hide-contact-address="{{ $hideContactAddress }}"
        hide-contact-tax-number="{{ $hideContactTaxNumber }}"
        hide-contact-phone="{{ $hideContactPhone }}"
        hide-contact-email="{{ $hideContactEmail }}"
        hide-order-number="{{ $hideOrderNumber }}"
        hide-document-number="{{ $hideDocumentNumber }}"
        hide-issued-at="{{ $hideIssuedAt }}"
        hide-due-at="{{ $hideDueAt }}"
        text-contact-info="{{ $textContactInfo }}"
        text-issued-at="{{ $textIssuedAt }}"
        text-document-number="{{ $textDocumentNumber }}"
        text-due-at="{{ $textDueAt }}"
        text-order-number="{{ $textOrderNumber }}"
        text-document-title="{{ $textDocumentTitle }}"
        text-document-subheading="{{ $textDocumentSubheading }}"
        hide-items="{{ $hideItems }}"
        hide-name="{{ $hideName }}"
        hide-description="{{ $hideDescription }}"
        hide-quantity="{{ $hideQuantity }}"
        hide-price="{{ $hidePrice }}"
        hide-amount="{{ $hideAmount }}"
        hide-note="{{ $hideNote }}"
        text-items="{{ $textItems }}"
        text-quantity="{{ $textQuantity }}"
        text-price="{{ $textPrice }}"
        text-amount="{{ $textAmount }}"
    />
@stack('document_end')

@stack('attachment_start')
    @if (!$hideAttachment)
        <x-documents.show.attachment
            type="{{ $type }}"
            :document="$document"
            :attachment="$attachment"
        />
    @endif
@stack('attachment_end')

@stack('row_footer_start')
    @if (!$hideFooter)
        <x-documents.show.footer
            type="{{ $type }}"
            :document="$document"
            :histories="$histories"
            :transactions="$transactions"
            class-footer-histories="{{ $classFooterHistories }}"
            hide-footer-histories="{{ $hideFooterHistories }}"
            text-histories="{{ $textHistories }}"
            text-history-status="{{ $textHistoryStatus }}"
            hide-footer-transactions="{{ $hideFooterTransactions }}"
            class-footer-transactions="{{ $classFooterTransactions }}"
        />
    @endif
@stack('row_footer_end')

{{ Form::hidden('document_id', $document->id, ['id' => 'document_id']) }}
{{ Form::hidden($type . '_id', $document->id, ['id' => $type . '_id']) }}
