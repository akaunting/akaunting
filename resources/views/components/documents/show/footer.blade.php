<div class="row">
    @stack('row_footer_histories_start')
        @if (!$hideFooterHistories)
            <div class="{{ $classFooterHistories }}">
                <x-documents.show.histories
                    type="{{ $type }}"
                    :document="$document"
                    :histories="$histories"
                    text-histories="{{ $textHistories }}"
                    text-history-status="{{ $textHistoryStatus }}"
                />
            </div>
        @endif
    @stack('row_footer_histories_end')

    @stack('row_footer_transactions_start')
        @if (!$hideFooterTransactions) 
            <div class="{{ $classFooterTransactions }}">
                <x-documents.show.transactions
                    type="{{ $type }}"
                    :document="$document"
                    :transactions="$transactions"
                />
            </div>
        @endif
    @stack('row_footer_transactions_end')
</div>
