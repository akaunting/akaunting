<div class="row">
    @stack('row_footer_histories_start')
        @if (!$hideFooterHistories)
            <div class="{{ $classFooterHistories }}">
                <x-transactions.show.histories
                    type="{{ $type }}"
                    :transaction="$transaction"
                    :histories="$histories"
                    text-histories="{{ $textHistories }}"
                />
            </div>
        @endif
    @stack('row_footer_histories_end')
</div>
