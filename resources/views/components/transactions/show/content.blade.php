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
