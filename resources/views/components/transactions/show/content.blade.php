<div class="flex flex-col lg:flex-row my-10 lg:space-x-24 rtl:space-x-reverse space-y-12 lg:space-y-0">
    <div class="w-full lg:w-5/12 space-y-12">
        @stack('recurring_message_start')

        @if (! $hideRecurringMessage)
            @if (($recurring = $transaction->recurring) && ($next = $recurring->getNextRecurring()))
                @php
                    $recurring_message = trans('recurring.message', [
                        'type' => mb_strtolower(trans_choice($textRecurringType, 1)),
                        'date' => $next->format(company_date_format())
                    ]);
                @endphp

                <x-documents.show.message type="recurring" background-color="bg-blue-100" text-color="text-blue-600" message="{{ $recurring_message }}" />
            @endif

            @if ($parent = $transaction->parent)
                @php
                    $recurring_message = trans('recurring.message_parent', [
                        'type' => mb_strtolower(trans_choice($textRecurringType, 1)),
                        'link' => '<a href="' . route(config('type.transaction.' . $transaction->parent->type . '.route.prefix') . '.show', $parent->id) . '"><u>' . $parent->number . '</u></a>',
                    ]);
                @endphp

                <x-documents.show.message type="recurring" background-color="bg-blue-100" text-color="text-blue-600" message="{{ $recurring_message }}" />
            @endif
        @endif

        @stack('recurring_message_end')

        @stack('row_create_start')
        @if (! $hideCreated)
        <x-transactions.show.create
            type="{{ $type }}"
            :transaction="$transaction"
        />
        @endif
        @stack('row_create_end')

        @stack('schedule_start')
        @if (! $hideSchedule)
            <x-transactions.show.schedule
                type="{{ $type }}"
                :transaction="$transaction"
            />
        @endif
        @stack('schedule_end')

        @stack('children_start')
        @if (! $hideChildren)
            <x-transactions.show.children
                type="{{ $type }}"
                :transaction="$transaction"
            />
        @endif
        @stack('children_end')

        @stack('transfer_start')
        @if (! $hideTransfer)
            <x-transactions.show.transfer
                type="{{ $type }}"
                :transaction="$transaction"
            />
        @endif
        @stack('transfer_end')

        @stack('attachment_start')
        @if (! $hideAttachment)
            <x-transactions.show.attachment
                type="{{ $type }}"
                :transaction="$transaction"
                :attachment="$attachment"
            />
        @endif
        @stack('attachment_end')
    </div>

    <div class="w-full lg:w-7/12">
        @stack('transaction_start')
            <x-transactions.show.template
                type="{{ $type }}"
                :transaction="$transaction"
                transaction-template="{{ $transactionTemplate }}"
                logo="{{ $logo }}"
                hide-company="{{ $hideCompany }}"
                hide-company-logo="{{ $hideCompanyLogo }}"
                hide-company-details="{{ $hideCompanyDetails }}"
                hide-company-name="{{ $hideCompanyName }}"
                hide-company-address="{{ $hideCompanyAddress }}"
                hide-company-tax-number="{{ $hideCompanyTaxNumber }}"
                hide-company-phone="{{ $hideCompanyPhone }}"
                hide-company-email="{{ $hideCompanyEmail }}"

                hide-content-title="{{ $hideContentTitle }}"
                hide-number="{{ $hideNumber }}"
                hide-paid-at="{{ $hidePaidAt }}"
                hide-account="{{ $hideAccount }}"
                hide-category="{{ $hideCategory }}"
                hide-payment-methods="{{ $hidePaymentMethods }}"
                hide-reference="{{ $hideReference }}"
                hide-description="{{ $hideDescription }}"
                hide-amount="{{ $hideAmount }}"

                text-content-title="{{ $textContentTitle }}"
                text-number="{{ $textNumber }}"
                text-paid-at="{{ $textPaidAt }}"
                text-account="{{ $textAccount }}"
                text-category="{{ $textCategory }}"
                text-payment-methods="{{ $textPaymentMethods }}"
                text-reference="{{ $textReference }}"
                text-description="{{ $textDescription }}"
                text-paid-by="{{ $textPaidBy }}"

                hide-contact="{{ $hideContact }}"
                hide-contact-info="{{ $hideContactInfo }}"
                hide-contact-name="{{ $hideContactName }}"
                hide-contact-address="{{ $hideContactAddress }}"
                hide-contact-tax-number="{{ $hideContactTaxNumber }}"
                hide-contact-phone="{{ $hideContactPhone }}"
                hide-contact-email="{{ $hideContactEmail }}"

                hide-related="{{ $hideRelated }}"
                hide-related-document-number="{{ $hideRelatedDocumentNumber }}"
                hide-related-contact="{{ $hideRelatedContact }}"
                hide-related-document-date="{{ $hideRelatedDocumentDate }}"
                hide-related-document-amount="{{ $hideRelatedDocumentAmount }}"
                hide-related-amount="{{ $hideRelatedAmount }}"

                text-related-transaction="{{ $textRelatedTransansaction }}"
                text-related-document-number="{{ $textRelatedDocumentNumber }}"
                text-related-contact="{{ $textRelatedContact }}"
                text-related-document-date="{{ $textRelatedDocumentDate }}"
                text-related-document-amount="{{ $textRelatedDocumentAmount }}"
                text-related-amount="{{ $textRelatedAmount }}"

                route-document-show="{{ $routeDocumentShow }}"
            />
        @stack('transaction_end')
    </div>

    <x-form.input.hidden name="transaction_id" :value="$transaction->id" />
    <x-form.input.hidden name="{{ $type . '_id' }}" :value="$transaction->id" />
</div>
