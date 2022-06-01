<table class="border-bottom-1" style="width: 100%;">
    <tbody>
        <tr>
            <td style="width: 50%; padding: 0 15px 15px 0;" valign="top">
                <table>
                    <tbody>
                        <tr>
                            <td style="padding:0;">
                                <h2 class="mb-1" style="font-size: 14px; font-weight:600; margin-bottom: 15px;">
                                    {{ trans('transfers.from_account') }}
                                </h2>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('accounts.number') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->expense_transaction->account->name }}
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('accounts.number') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->expense_transaction->account->number}}
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('accounts.bank_name') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->expense_transaction->account->bank_name }}
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('general.phone') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->expense_transaction->account->bank_phone }}
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('general.address') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->expense_transaction->account->bank_address }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <td style="width: 50%; padding: 0 0 15px 15px;" valign="top">
                <table>
                    <tbody>
                        <tr>
                            <td style="padding:0;">
                                <h2 class="mb-1" style="font-size: 14px; font-weight:600; margin-bottom: 15px;">
                                    {{ trans('transfers.to_account') }}
                                </h2>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('accounts.number') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->income_transaction->account->name }}
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('accounts.number') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->income_transaction->account->number }}
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('accounts.bank_name') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->income_transaction->account->bank_name }}
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('general.phone') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->income_transaction->account->bank_phone }}
                                        </td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>
                                        <td style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px; font-weight:600;">
                                            {{ trans('general.address') }}:
                                        </td>

                                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                                            {{ $transfer->income_transaction->account->bank_address }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<table style="width: 100%; margin-top:15px;">
    <tr>
        <td style="padding:0 0 15px 0;">
            <h2 class="text-left text-uppercase" style="font-size: 14px; font-weight:600;">
                {{ trans_choice('transfers.details', 2) }}
            </h2>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td valign="top" style="width: 30%; margin: 0px; padding: 0; font-size: 12px; font-weight:600; line-height: 24px;">
            {{ trans('general.date') }}:
        </td>

        <td valign="top" style="width:70%; margin: 0px; padding: 0; font-size: 12px; border-bottom:1px solid; line-height: 24px;">
            <x-date date="{{ $transfer->expense_transaction->paid_at}}" />
        </td>
    </tr>

    <tr>
        <td valign="top" style="width: 30%; margin: 0px; padding: 0; font-size: 12px; font-weight:600; line-height: 24px;">
            {{ trans_choice('general.payment_methods', 1) }}:
        </td>

        <td valign="top" style="width:70%; margin: 0px; padding: 0; font-size: 12px; border-bottom:1px solid; line-height: 24px;">
            @if (! empty($payment_methods[$transfer->expense_transaction->payment_method]))
                {!! $payment_methods[$transfer->expense_transaction->payment_method] !!}
            @else
                <x-empty-data />
            @endif
        </td>
    </tr>

    <tr>
        <td valign="top" style="width: 30%; margin: 0px; padding: 0; font-size: 12px; font-weight:600; line-height: 24px;">
            {{ trans('general.reference') }}:
        </td>

        <td valign="top" style="width:70%; margin: 0px; padding: 0; font-size: 12px; border-bottom:1px solid; line-height: 24px;">
            {{ $transfer->expense_transaction->reference }}
        </td>
    </tr>

    <tr>
        <td valign="top" style="width: 30%; margin: 0px; padding: 0; font-size: 12px; font-weight:600; line-height: 24px;">
            {{ trans('general.description') }}
        </td>

        <td valign="top" style="width:70%; margin: 0px; padding: 0; font-size: 12px; border-bottom:1px solid; line-height: 24px;">
            {{ $transfer->expense_transaction->description }}
        </td>
    </tr>
</table>

<table style="text-align: right;">
    <tr>
        <td valign="center" style="width:80%; display:block; float:right;">
            <table>
                <tr>
                    <td valign="center" style="width: 80%; padding:0; font-size: 14px; font-weight:600;">
                        {{ trans('general.amount') }}:
                    </td>

                    <td valign="center" style="width: 20%; padding:0; font-size: 14px;">
                        <x-money :amount="$transfer->expense_transaction->amount" :currency="$transfer->expense_transaction->currency_code" convert />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
