<div class="row" style="font-size: inherit !important">
    @stack('header_account_start')
        @if (!$hideHeaderFromAccount)
        <div class="{{ $classHeaderFromAccount }}">
            {{ trans_choice($textHeaderFromAccount, 1) }}
            <br>

            <strong>
                <span class="float-left long-texts mwpx-200 transaction-head-text">
                    <a href="{{ route($routeFromAccountShow, $transfer->expense_transaction->account->id) }}">
                        {{ $transfer->expense_transaction->account->name }}
                    </a>
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_account_end')

    @stack('header_category_start')
        @if (!$hideHeaderToAccount)
        <div class="{{ $classHeaderToAccount }}">
            {{ trans_choice($textHeaderToAccount, 1) }}
            <br>

            <strong>
                <span class="float-left long-texts mwpx-300 transaction-head-text">
                    <a href="{{ route($routeToAccountShow, $transfer->income_transaction->account->id) }}">
                        {{ $transfer->income_transaction->account->name }}
                    </a>
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_category_end')

    @stack('header_amount_start')
        @if (!$hideHeaderAmount)
        <div class="{{ $classHeaderAmount }}">
            <span class="float-right">
                {{ trans($textHeaderAmount) }}
            </span>
            <br>

            <strong>
                <span class="float-right long-texts mwpx-100 transaction-head-text">
                    @money($transfer->expense_transaction->amount, $transfer->expense_transaction->currency_code, true)
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_amount_end')

    @stack('header_paid_at_start')
        @if (!$hideHeaderPaidAt)
        <div class="{{ $classHeaderPaidAt }}">
            <span class="float-right">
                {{ trans($textHeaderPaidAt) }}
            </span>
            <br>

            <strong>
                <span class="float-right long-texts mwpx-100 transaction-head-text">
                    @date($transfer->transferred_at)
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_paid_at_end')
</div>
