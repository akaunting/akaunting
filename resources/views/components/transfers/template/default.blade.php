<div class="print-template">
    @stack('from_account_start')
    <table class="border-bottom-1">
        <tbody>
            @stack('from_account_id_input_start')
            <tr>
                <td style="width: 60%; padding: 0 0 15px 0;">
                    <div class="mb-1 font-semibold" style="font-size: 14px; margin-bottom: 15px;">
                        {{ trans('transfers.from_account') }}
                    </div>

                    <table>
                        @stack('from_account_name_input_start')
                        <tr>
                            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('accounts.account_name') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->expense_account->name }}
                            </td>
                        </tr>
                        @stack('from_account_name_input_end')
                    </table>

                    <table>
                        @stack('from_account_number_input_start')
                        <tr>
                            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('accounts.number') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->expense_account->number }}
                            </td>
                        </tr>
                        @stack('from_account_number_input_end')
                    </table>

                    @if (! empty($transfer->expense_account->bank_name))
                    <table>
                        @stack('from_account_bank_name_input_start')
                        <tr>
                            <td valign="top" class="font-semibold"  style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('accounts.bank_name') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->expense_account->bank_name }}
                            </td>
                        </tr>
                        @stack('from_account_bank_name_input_end')
                    </table>
                    @endif

                    @if (! empty($transfer->expense_account->bank_phone))
                    <table>
                        @stack('from_account_phone_input_start')
                        <tr>
                            <td valign="top" class="font-semibold"  style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('general.phone') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->expense_account->bank_phone }}
                            </td>
                        </tr>
                        @stack('from_account_phone_input_end')
                    </table>
                    @endif

                    @if (! empty($transfer->expense_account->bank_address))
                    <table>
                        @stack('from_account_address_input_start')
                        <tr>
                            <td valign="top" class="font-semibold"  style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('general.address') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->expense_account->bank_address }}
                            </td>
                        </tr>
                        @stack('from_account_address_input_end')
                    </table>
                    @endif
                </td>
            </tr>
            @stack('from_account_id_input_end')
        </tbody>
    </table>
    @stack('from_account_end')

    @stack('to_account_start')
    <table class="border-bottom-1" style="margin-top:15px;">
        <tbody>
            @stack('to_account_id_input_start')
            <tr>
                <td style="width: 60%; padding: 0 0 15px 0;">
                    <div class="mb-1 font-semibold" style="font-size: 14px; margin-bottom: 15px;">
                        {{ trans('transfers.to_account') }}
                    </div>

                    <table>
                        @stack('to_account_name_input_start')
                        <tr>
                            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('accounts.account_name') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->income_account->name }}
                            </td>
                        </tr>
                        @stack('to_account_name_input_end')
                    </table>

                    <table>
                        @stack('to_account_number_input_start')
                        <tr>
                            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('accounts.number') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->income_account->number }}
                            </td>
                        </tr>
                        @stack('to_account_number_input_end')
                    </table>

                    @if (! empty($transfer->income_account->bank_name))
                    <table>
                        @stack('to_account_bank_name_input_start')
                        <tr>
                            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('accounts.bank_name') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->income_account->bank_name }}
                            </td>
                        </tr>
                        @stack('to_account_bank_name_input_end')
                    </table>
                    @endif

                    @if (! empty($transfer->income_account->bank_phone))
                    <table>
                        @stack('to_account_phone_input_start')
                        <tr>
                            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('general.phone') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->income_account->bank_phone }}
                            </td>
                        </tr>
                        @stack('to_account_phone_input_end')
                    </table>
                    @endif

                    @if (! empty($transfer->income_account->bank_address))
                    <table>
                        @stack('to_account_address_input_start')
                        <tr>
                            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                                {{ trans('general.address') }}
                            </td>

                            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                                {{ $transfer->income_account->bank_address }}
                            </td>
                        </tr>
                        @stack('to_account_address_input_end')
                    </table>
                    @endif
                </td>
            </tr>
            @stack('to_account_id_input_end')
        </tbody>
    </table>
    @stack('to_account_end')

    @stack('details_start')
    <table style="margin-top:15px;">
        <tr>
            <td style="padding:0 0 15px 0;">
                <div class="text-left text-uppercase font-semibold" style="font-size: 14px;">
                    {{ trans_choice('transfers.details', 2) }}
                </div>
            </td>
        </tr>
    </table>

    <table class="border-bottom-1" style="padding-bottom:15px;">
        @stack('transferred_at_input_start')
        <tr>
            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                {{ trans('general.date') }}
            </td>

            <td valign="top" valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                <x-date date="{{ $transfer->expense_transaction->paid_at}}" />
            </td>
        </tr>
        @stack('transferred_at_input_end')

        @stack('payment_method_input_start')
        <tr>
            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                {{ trans_choice('general.payment_methods', 1) }}
            </td>

            <td valign="top" valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                <x-payment-method :method="$transfer->expense_transaction->payment_method" />
            </td>
        </tr>
        @stack('payment_method_input_end')

        @stack('reference_input_start')
        <tr>
            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                {{ trans('general.reference') }}
            </td>

            <td valign="top" valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                {{ $transfer->expense_transaction->reference }}
            </td>
        </tr>
        @stack('reference_input_end')

        @stack('description_input_start')
        <tr>
            <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px;">
                {{ trans('general.description') }}
            </td>

            <td valign="top" valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding: 8px 0 0 0; font-size: 12px;">
                {{ $transfer->expense_transaction->description }}
            </td>
        </tr>
        @stack('description_input_end')

        @stack('amount_input_start')
        {{-- The reason for adding the amount part here is because the amount style is broken in the view. --}}
        @stack('amount_input_end')

        <tr>
            <td></td>
        </tr>
    </table>
    @stack('details_end')


    <table style="text-align: right; margin-top:55px;">
        <tr>
            <td valign="center" style="width:80%; display:block; float:right; background-color: #55588B; -webkit-print-color-adjust: exact; color:#ffffff; border-radius: 5px;">
                <table>
                    <tr>
                        <td valign="center" class="font-semibold" style="padding:0; font-size: 14px; color:#ffffff;">
                            <span class="ml-2">
                                {{ trans('general.amount') }}
                            </span>

                            <x-money :amount="$transfer->expense_transaction->amount" :currency="$transfer->expense_transaction->currency_code" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
