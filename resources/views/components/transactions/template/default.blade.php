@stack('company_start')
@if (!$hideCompany)
    <table class="border-bottom-1">
        <tr>
            <td style="width:5%;" valign="top">
                @stack('company_logo_start')
                @if (!$hideCompanyLogo)
                    @if (!empty($document->contact->logo) && !empty($document->contact->logo->id))
                        <img src="{{ Storage::url($document->contact->logo->id) }}" height="128" width="128" alt="{{ $document->contact_name }}" />
                    @else
                        <img src="{{ $logo }}" alt="{{ setting('company.name') }}" />
                    @endif
                @endif
                @stack('company_logo_end')
            </td>
            <td style="width: 60%;">
                @stack('company_details_start')
                @if (!$hideCompanyDetails)
                    @if (!$hideCompanyName)
                        <h2 class="mb-1" style="font-size: 16px;">
                            {{ setting('company.name') }}
                        </h2>
                    @endif

                @if (!$hideCompanyAddress)
                    <p style="margin:0; padding:0; font-size:14px;">{!! nl2br(setting('company.address')) !!}</p>
                @endif

                @if (!$hideCompanyTaxNumber)
                    <p style="margin:0; padding:0; font-size:14px;">
                        @if (setting('company.tax_number'))
                            {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                        @endif
                    </p>
                @endif

                @if (!$hideCompanyPhone)
                    <p style="margin:0; padding:0; font-size:14px;">
                        @if (setting('company.phone'))
                            {{ setting('company.phone') }}
                        @endif
                    </p>
                @endif

                @if (!$hideCompanyEmail)
                    <p style="margin:0; padding:0; font-size:14px;">{{ setting('company.email') }}</p>
                @endif
                @endif
                @stack('company_details_end')
            </td>
        </tr>
    </table>
@endif
@stack('company_end')

@if (!$hideContentTitle)
    <table>
        <tr>
            <td style="padding-bottom: 0; padding-top: 32px;">
                <h2 class="text-center text-uppercase" style="font-size: 16px;">{{ trans('invoices.revenue_made') }}</h2>
            </td>
        </tr>
    </table>
@endif

<table>
    <tr>
        <td style="width: 70%; padding-top:0; padding-bottom:0;">
            <table>
                @if (!$hidePaidAt)
                    <tr>
                        <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">{{ trans('general.date') }}:</td>
                        <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;"> @date($transaction->paid_at)</td>
                    </tr>
                @endif

                @if (!$hideAccount)
                    <tr>
                        <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">{{ trans_choice('general.accounts', 1) }}:</td>
                        <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">{{ $transaction->account->name }}</td>
                    </tr>
                @endif

                @if (!$hideCategory)
                    <tr>
                        <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">{{ trans_choice('general.categories', 1) }}:</td>
                        <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">{{ $transaction->category->name }}</td>
                    </tr>
                @endif

                @if (!$hidePaymentMethods)
                    <tr>
                        <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">{{ trans_choice('general.payment_methods', 1) }}:</td>
                        <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">{{ $payment_methods[$transaction->payment_method] }}</td>
                    </tr>
                @endif

                @if (!$hideReference)
                    <tr>
                        <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">{{ trans('general.reference') }}:</td>
                        <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">{{ $transaction->reference }}</td>
                    </tr>
                @endif

                @if (!$hideDescription)
                    <tr>
                        <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">{{ trans('general.description') }}:</td>
                        <td style="width:80%; padding-bottom:3px; font-size:14px;">
                            <p style="font-size:14px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin: 0;">{!! nl2br($transaction->description) !!}</p>
                        </td>
                    </tr>
                @endif
                @if (!$hideContact)
                    <tr>
                        <td style="padding-top:45px; padding-bottom:0;">
                            <h2 style="font-size: 16px;">{{ trans('general.paid_by') }}</h2>
                        </td>
                    </tr>
                @if ($hideContactInfo)
                    <tr>
                        <td style="padding-bottom:5px; padding-top:0; font-size:14px;">
                            <strong>{{ trans($textContactInfo) }}</strong><br>
                        </td>
                    </tr>
                @endif

                @stack('name_input_start')
                @if (!$hideContactName)
                    <tr>
                        <td style="padding-bottom:5px; padding-top:0; font-size:14px;">
                            <strong>{{ $transaction->contact->name }}</strong><br>
                        </td>
                    </tr>
                @endif
                @stack('name_input_end')

                @stack('address_input_start')
                @if (!$hideContactAddress)
                    <tr>
                        <td style="padding-bottom:5px; padding-top:0; font-size:14px;">
                            <p style="margin:0; padding:0; font-size:14px;">{!! nl2br($transaction->contact->address) !!}</p>
                        </td>
                    </tr>
                @endif
                @stack('address_input_end')

                @stack('tax_number_input_start')
                @if (!$hideContactTaxNumber)
                    <tr>
                        <td style="padding-bottom:5px; padding-top:0; font-size:14px;">
                            <p style="margin:0; padding:0; font-size:14px;">
                                @if ($transaction->contact->tax_number)
                                {{ trans('general.tax_number') }}: {{ $transaction->contact->tax_number }}
                                @endif
                            </p>
                        </td>
                    </tr>
                @endif
                @stack('tax_number_input_end')

                @stack('phone_input_start')
                @if (!$hideContactPhone)
                    <tr>
                        <td style="padding-bottom:0; padding-top:0; font-size:14px;">
                            <p style="margin:0; padding:0; font-size:14px;">
                                @if ($transaction->contact->phone)
                                {{ $transaction->contact->phone }}
                                @endif
                            </p>
                        </td>
                    </tr>
                @endif
                @stack('phone_input_end')

                @stack('email_start')
                @if (!$hideContactEmail)
                    <tr>
                        <td style="padding-bottom:0; padding-top:0; font-size:14px;">
                            <p style="margin:0; padding:0; font-size:14px;">
                                {{ $transaction->contact->email }}
                            </p>
                        </td>
                    </tr>
                @endif
                @stack('email_input_end')
                @endif
            </table>
        </td>
        @if (!$hideAmount)
            <td style="width:30%; padding-top:32px; padding-left: 25px;" valign="top">
                <table>
                    <tr>
                        <td style="background-color: #6da252; -webkit-print-color-adjust: exact; width: 280px; font-weight:bold !important; display:block;">
                            <h5 class="text-muted mb-0 text-white" style="font-size: 20px; color:#ffffff; text-align:center; margin-top: 16px;">{{ trans('general.amount') }}</h5>
                            <p class="font-weight-bold mb-0 text-white" style="font-size: 32px; color:#ffffff; text-align:center;">
                                @money($transaction->amount, $transaction->currency_code, true)
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        @endif
    </tr>
</table>

<table>
    <tr>
        <td class="border-bottom-1" style="padding-bottom: 0; padding-top:16px;"></td>
    </tr>
</table>

@if (!$hideReletad)
    @if ($transaction->document)
        <table>
            <tr>
                <td style="padding-bottom: 0; padding-top:36px;">
                    <h2 style="font-size: 16px;">{{ trans('invoices.related_revenue') }}</h2>
                </td>
            </tr>
        </table>
        <table class="table table-flush table-hover" cellspacing="0" cellpadding="0" style="margin-bottom: 36px;">
            <thead style="background-color: #f6f9fc; -webkit-print-color-adjust: exact; font-family: Arial, sans-serif; color:#8898aa; font-size:11px;">
                <tr class="border-bottom-1">
                    <th class="item text-left" style="text-align: left; text-transform: uppercase; font-family: Arial, sans-serif;">
                        <span>{{ trans_choice('general.numbers', 1) }}</span>
                    </th>

                    <th class="quantity" style="text-align: left; text-transform: uppercase; font-family: Arial, sans-serif;">
                        {{ trans_choice('general.customers', 1) }}
                    </th>

                    <th class="price" style="text-align: left; text-transform: uppercase; font-family: Arial, sans-serif;">
                        {{ trans('invoices.invoice_date') }}
                    </th>

                    <th class="price" style="text-align: left; text-transform: uppercase; font-family: Arial, sans-serif;">
                        {{ trans('invoices.invoice_date') }}
                    </th>

                    <th class="total" style="text-align: left; text-transform: uppercase; font-family: Arial, sans-serif;">
                        {{ trans('general.amount') }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="item" style="color:#525f7f; font-size:13px;">
                        <a style="color:#6da252 !important;" href="{{ route('invoices.show' , $transaction->document->id) }}">
                            {{ $transaction->document->document_number }}
                        </a>
                    </td>

                    <td class="quantity" style="color:#525f7f; font-size:13px;">
                        {{ $transaction->document->contact_name }}
                    </td>

                    <td class="price" style="color:#525f7f; font-size:13px;">
                        @date($transaction->document->due_at)
                    </td>

                    <td class="price" style="color:#525f7f; font-size:13px;">
                        @money($transaction->document->amount, $transaction->document->currency_code, true)
                    </td>

                    <td class="total" style="color:#525f7f; font-size:13px;">
                        @money($transaction->amount, $transaction->currency_code, true)
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <table>
            <tr>
                <td style="padding-bottom:0; font-size:14px;">
                    <p style="margin-bottom:0; font-size:14px;">
                        {{ trans('invoices.overdue_revenue') }}: <strong style="font-weight: bold;">@money($transaction->amount, $transaction->currency_code, true)</strong>
                    </p>
                </td>
            </tr>
        </table>
    @endif
@endif