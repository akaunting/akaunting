

<div class="card" style="padding: 0; padding-left: 15px; padding-right: 15px; border-radius: 0; box-shadow: 0 4px 16px rgba(0,0,0,.2);">
    <div class="card-body show-card-body">
        @if ($transferTemplate)
            @switch($transferTemplate)
                @case('second')
                    <x-transfers.template.second
                        :transfer="$transfer"

                        hide-from-account="{{ $hideFromAccount }}"
                        hide-from-account-title="{{ $hideFromAccountTitle }}"
                        hide-from-account-name="{{ $hideFromAccountName }}"
                        hide-from-account-number="{{ $hideFromAccountNumber }}"
                        hide-from-account-bank-name="{{ $hideFromAccountBankName }}"
                        hide-from-account-bank-phone="{{ $hideFromAccountBankPhone }}"
                        hide-from-account-bank-address="{{ $hideFromAccountBankAddress }}"

                        text-from-account-title="{{ $textFromAccountTitle }}"
                        text-from-account-number="{{ $textFromAccountNumber }}"

                        hide-to-account="{{ $hideToAccount }}"
                        hide-to-account-title="{{ $hideToAccountTitle }}"
                        hide-to-account-name="{{ $hideToAccountName }}"
                        hide-to-account-number="{{ $hideToAccountNumber }}"
                        hide-to-account-bank-name="{{ $hideToAccountBankName }}"
                        hide-to-account-bank-phone="{{ $hideToAccountBankPhone }}"
                        hide-to-account-bank-address="{{ $hideToAccountBankAddress }}"

                        text-to-account-title="{{ $textToAccountTitle }}"
                        text-to-account-number="{{ $textToAccountNumber }}"

                        hide-details="{{ $hideDetails }}"
                        hide-detail-title="{{ $hideDetailTitle }}"
                        hide-detail-date="{{ $hideDetailDate }}"
                        hide-detail-payment-method="{{ $hideDetailPaymentMethod }}"
                        hide-detail-reference="{{ $hideDetailReference }}"
                        hide-detail-description="{{ $hideDetailDescription }}"
                        hide-detail-amount="{{ $hideDetailAmount }}"
                
                        text-detail-title="{{ $textDetailTitle }}"
                        text-detail-date="{{ $textDetailDate }}"
                        text-detail-payment-method="{{ $textDetailPaymentMethod }}"
                        text-detail-reference="{{ $textDetailReference }}"
                        text-detail-description="{{ $textDetailDescription }}"
                        text-detail-amount="{{ $textDetailAmount }}"
                    />
                    @break
                @case('third')
                    <x-transfers.template.third
                        :transfer="$transfer"

                        hide-from-account="{{ $hideFromAccount }}"
                        hide-from-account-title="{{ $hideFromAccountTitle }}"
                        hide-from-account-name="{{ $hideFromAccountName }}"
                        hide-from-account-number="{{ $hideFromAccountNumber }}"
                        hide-from-account-bank-name="{{ $hideFromAccountBankName }}"
                        hide-from-account-bank-phone="{{ $hideFromAccountBankPhone }}"
                        hide-from-account-bank-address="{{ $hideFromAccountBankAddress }}"

                        text-from-account-title="{{ $textFromAccountTitle }}"
                        text-from-account-number="{{ $textFromAccountNumber }}"

                        hide-to-account="{{ $hideToAccount }}"
                        hide-to-account-title="{{ $hideToAccountTitle }}"
                        hide-to-account-name="{{ $hideToAccountName }}"
                        hide-to-account-number="{{ $hideToAccountNumber }}"
                        hide-to-account-bank-name="{{ $hideToAccountBankName }}"
                        hide-to-account-bank-phone="{{ $hideToAccountBankPhone }}"
                        hide-to-account-bank-address="{{ $hideToAccountBankAddress }}"

                        text-to-account-title="{{ $textToAccountTitle }}"
                        text-to-account-number="{{ $textToAccountNumber }}"

                        hide-details="{{ $hideDetails }}"
                        hide-detail-title="{{ $hideDetailTitle }}"
                        hide-detail-date="{{ $hideDetailDate }}"
                        hide-detail-payment-method="{{ $hideDetailPaymentMethod }}"
                        hide-detail-reference="{{ $hideDetailReference }}"
                        hide-detail-description="{{ $hideDetailDescription }}"
                        hide-detail-amount="{{ $hideDetailAmount }}"
                
                        text-detail-title="{{ $textDetailTitle }}"
                        text-detail-date="{{ $textDetailDate }}"
                        text-detail-payment-method="{{ $textDetailPaymentMethod }}"
                        text-detail-reference="{{ $textDetailReference }}"
                        text-detail-description="{{ $textDetailDescription }}"
                        text-detail-amount="{{ $textDetailAmount }}"
                    />
                    @break
                @default
                    <x-transfers.template.ddefault
                        :transfer="$transfer"

                        hide-from-account="{{ $hideFromAccount }}"
                        hide-from-account-title="{{ $hideFromAccountTitle }}"
                        hide-from-account-name="{{ $hideFromAccountName }}"
                        hide-from-account-number="{{ $hideFromAccountNumber }}"
                        hide-from-account-bank-name="{{ $hideFromAccountBankName }}"
                        hide-from-account-bank-phone="{{ $hideFromAccountBankPhone }}"
                        hide-from-account-bank-address="{{ $hideFromAccountBankAddress }}"

                        text-from-account-title="{{ $textFromAccountTitle }}"
                        text-from-account-number="{{ $textFromAccountNumber }}"

                        hide-to-account="{{ $hideToAccount }}"
                        hide-to-account-title="{{ $hideToAccountTitle }}"
                        hide-to-account-name="{{ $hideToAccountName }}"
                        hide-to-account-number="{{ $hideToAccountNumber }}"
                        hide-to-account-bank-name="{{ $hideToAccountBankName }}"
                        hide-to-account-bank-phone="{{ $hideToAccountBankPhone }}"
                        hide-to-account-bank-address="{{ $hideToAccountBankAddress }}"

                        text-to-account-title="{{ $textToAccountTitle }}"
                        text-to-account-number="{{ $textToAccountNumber }}"

                        hide-details="{{ $hideDetails }}"
                        hide-detail-title="{{ $hideDetailTitle }}"
                        hide-detail-date="{{ $hideDetailDate }}"
                        hide-detail-payment-method="{{ $hideDetailPaymentMethod }}"
                        hide-detail-reference="{{ $hideDetailReference }}"
                        hide-detail-description="{{ $hideDetailDescription }}"
                        hide-detail-amount="{{ $hideDetailAmount }}"
                
                        text-detail-title="{{ $textDetailTitle }}"
                        text-detail-date="{{ $textDetailDate }}"
                        text-detail-payment-method="{{ $textDetailPaymentMethod }}"
                        text-detail-reference="{{ $textDetailReference }}"
                        text-detail-description="{{ $textDetailDescription }}"
                        text-detail-amount="{{ $textDetailAmount }}"
                    />
            @endswitch
        @else
            @include($transferTemplate)
        @endif
    </div>
</div>
