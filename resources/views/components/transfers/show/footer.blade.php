<div class="row">
    @stack('row_footer_histories_start')
        @if (!$hideFooterHistories)
            <div class="{{ $classFooterHistories }}">
                <x-transfers.show.histories
                    :transfer="$transfer"
                    :histories="$histories"
                    text-histories="{{ $textHistories }}"
                />
            </div>
        @endif
    @stack('row_footer_histories_end')
</div>
