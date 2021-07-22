@if (!$hideDetails)
    @if (!$hideDetailTitle)
        <table>
            <tr>
                <td style="padding-bottom: 0; padding-top: 32px;">
                    <h2 class="text-center text-uppercase" style="font-size: 16px;">
                        {{ trans_choice($textDetailTitle, 2) }}
                    </h2>
                </td>
            </tr>
        </table>
    @endif

    <table class="border-bottom-1">
        <tr>
            <td style="width: 70%; padding-top:0; padding-bottom:45px;">
                <table>
                    @if (!$hideDetailDate)
                        <tr>
                            <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">
                                {{ trans($textDetailDate) }}:
                            </td>

                            <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">
                                @date($transfer->expense_transaction->paid_at)
                            </td>
                        </tr>
                    @endif

                    @if (!$hideDetailPaymentMethod)
                        <tr>
                            <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">
                                {{ trans_choice($textDetailPaymentMethod, 1) }}:
                            </td>

                            <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">
                                {{ !empty($payment_methods[$transfer->expense_transaction->payment_method]) ? $payment_methods[$transfer->expense_transaction->payment_method] : trans('general.na') }}
                            </td>
                        </tr>
                    @endif

                    @if (!$hideDetailReference)
                        <tr>
                            <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">
                                {{ trans($textDetailReference) }}:
                            </td>

                            <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">
                                {{ $transfer->expense_transaction->reference }}
                            </td>
                        </tr>
                    @endif

                    @if (!$hideDetailDescription)
                        <tr>
                            <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">
                                {{ trans($textDetailDescription) }}
                            </td>

                            <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">
                                {{ $transfer->expense_transaction->description }}
                            </td>
                        </tr>
                    @endif
                </table>
            </td>

            @if (!$hideDetailAmount)
                <td style="width:30%; padding-top:32px; padding-left: 25px;" valign="top">
                    <table>
                        <tr>
                            <td style="background-color: #6da252; -webkit-print-color-adjust: exact; font-weight:bold !important; display:block;">
                                <h5 class="text-muted mb-0 text-white" style="font-size: 20px; color:#ffffff; text-align:center; margin-top: 16px;">
                                    {{ trans($textDetailAmount) }}:
                                </h5>

                                <p class="font-weight-bold mb-0 text-white" style="font-size: 26px; color:#ffffff; text-align:center;">
                                    @money($transfer->expense_transaction->amount, $transfer->expense_transaction->currency_code, true)
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            @endif
        </tr>
    </table>
@endif

@if (!$hideFromAccount)
    <table class="border-bottom-1" style="width: 100%;">
        <tbody>
            <tr>
                <td style="width: 60%; padding-bottom: 15px;">
                    @if (!$hideFromAccountTitle)
                        <h2 class="mb-1" style="font-size: 16px;">
                            {{ trans($textFromAccountTitle) }}
                        </h2>
                    @endif

                    @if (!$hideFromAccountName)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ $transfer->expense_transaction->account->name }}
                        </p>
                    @endif

                    @if (!$hideFromAccountNumber)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ trans($textFromAccountNumber) }}: {{ $transfer->expense_transaction->account->number}}
                        </p>
                    @endif

                    @if (!$hideFromAccountBankName)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ $transfer->expense_transaction->account->bank_name }}
                        </p>
                    @endif

                    @if (!$hideFromAccountBankPhone)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ $transfer->expense_transaction->account->bank_phone }}
                        </p>
                    @endif

                    @if (!$hideFromAccountBankAddress)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ $transfer->expense_transaction->account->bank_address }}
                        </p>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
@endif

@if (!$hideToAccount)
    <table class="border-bottom-1" style="width: 100%; margin-top:15px;">
        <tbody>
            <tr>
                <td style="width: 60%; padding-bottom: 15px;">
                    @if (!$hideToAccountTitle)
                        <h2 class="mb-1" style="font-size: 16px;">
                            {{ trans($textToAccountTitle) }}
                        </h2>
                    @endif

                    @if (!$hideToAccountName)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ $transfer->income_transaction->account->name }}
                        </p>
                    @endif

                    @if (!$hideToAccountNumber)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ trans($textToAccountNumber) }}: {{ $transfer->income_transaction->account->number }}
                        </p>
                    @endif

                    @if (!$hideToAccountBankName)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ $transfer->income_transaction->account->bank_name }}
                        </p>
                    @endif

                    @if (!$hideToAccountBankPhone)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ $transfer->income_transaction->account->bank_phone }}
                        </p>
                    @endif

                    @if (!$hideToAccountBankAddress)
                        <p style="margin: 0px; padding: 0px; font-size: 14px;">
                            {{ $transfer->income_transaction->account->bank_address }}
                        </p>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
@endif
