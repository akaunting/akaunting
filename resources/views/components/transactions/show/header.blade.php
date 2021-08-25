<div class="row" style="font-size: inherit !important">
    @stack('header_account_start')
        @if (!$hideHeaderAccount)
        <div class="{{ $classHeaderAccount }}">
            {{ trans_choice($textHeaderAccount, 1) }}
            <br>

            <strong>
                <span class="float-left long-texts mwpx-200 transaction-head-text">
                    <a href="{{ route('accounts.show', $transaction->account->id) }}">
                        {{ $transaction->account->name }}
                    </a>
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_account_end')

    @stack('header_category_start')
        @if (!$hideHeaderCategory)
        <div class="{{ $classHeaderCategory }}">
            {{ trans_choice($textHeaderCategory, 1) }}
            <br>

            <strong>
                <span class="float-left long-texts mwpx-300 transaction-head-text">
                    {{ $transaction->category->name }}
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_category_end')

    @stack('header_contact_start')
        @if (!$hideHeaderContact)
        <div class="{{ $classHeaderContact }}">
            {{ trans_choice($textHeaderContact, 1) }}
            <br>

            <strong>
                <span class="float-left long-texts mwpx-300 transaction-head-text">
                    @if (!empty($transaction->contact->id))
                        <a href="{{ route($routeContactShow, $transaction->contact->id) }}">
                            {{ $transaction->contact->name }}
                        </a>
                    @else
                        {{ $transaction->contact->name }}
                    @endif
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_contact_end')

    @stack('header_amount_start')
        @if (!$hideHeaderAmount)
        <div class="{{ $classHeaderAmount }}">
            {{ trans($textHeaderAmount) }}
            <br>

            <strong>
                <span class="float-left long-texts mwpx-100 transaction-head-text">
                    @money($transaction->amount, $transaction->currency_code, true)
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_amount_end')

    @stack('header_paid_at_start')
        @if (!$hideHeaderPaidAt)
        <div class="{{ $classHeaderPaidAt }}">
            {{ trans($textHeaderPaidAt) }}
            <br>

            <strong>
                <span class="float-left long-texts mwpx-100 transaction-head-text">
                    @date($transaction->paid_at)
                </span>
            </strong>
            <br><br>
        </div>
        @endif
    @stack('header_paid_at_end')
</div>
