@stack('company_start')
@if (!$hideCompany)
<table class="border-bottom-1">
    <tr>
        @if (!$hideCompanyLogo)
        <td style="width:20%; padding: 0 0 15px 0;" valign="top">
            @stack('company_logo_start')
            @if (!empty($transaction->contact->logo) && !empty($transaction->contact->logo->id))
                <img src="{{ Storage::url($transaction->contact->logo->id) }}" height="70" width="70" alt="{{ $transaction->contact_name }}" />
            @else
                <img src="{{ $logo }}" height="70" width="70" alt="{{ setting('company.name') }}" />
            @endif
            @stack('company_logo_end')
        </td>
        @endif

        @if (!$hideCompanyDetails)
        <td class="text" style="width: 80%; padding: 0 0 15px 0;">
            @stack('company_details_start')
            @if (!$hideCompanyName)
                <h2 class="text-semibold text">
                    {{ setting('company.name') }}
                </h2>
            @endif

            @if (!$hideCompanyAddress)
                <p>{!! (setting('company.address')) !!}</p>
            @endif

            @if (!$hideCompanyTaxNumber)
                @if (setting('company.tax_number'))
                    <p>
                        {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                    </p>
                @endif
            @endif

            @if (!$hideCompanyPhone)
                @if (setting('company.phone'))
                    <p>
                        {{ setting('company.phone') }}
                    </p>
                @endif
            @endif

            @if (!$hideCompanyEmail)
                    <p>{{ setting('company.email') }}</p>
                @endif
            @stack('company_details_end')
        </td>
        @endif
    </tr>
</table>
@endif
@stack('company_end')

@if (!$hideContentTitle)
    <table>
        <tr>
            <td style="width: 60%; padding: 15px 0 15px 0;">
                <h2 style="font-size: 12px; font-weight:600;">
                    {{ trans($textContentTitle) }}
                </h2>
            </td>
        </tr>
    </table>
@endif

<table>
    @stack('number_input_start')
    @if (!$hideNumber)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans_choice($textNumber, 1) }}:
            </td>

            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                {{ $transaction->number }}
            </td>
        </tr>
    @endif
    @stack('number_input_end')

    @stack('paid_at_input_start')
    @if (!$hidePaidAt)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans($textPaidAt) }}:
            </td>

            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                @date($transaction->paid_at)
            </td>
        </tr>
    @endif
    @stack('paid_at_input_end')

    @stack('account_id_input_start')
    @if (!$hideAccount)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans_choice($textAccount, 1) }}:
            </td>

            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                {{ $transaction->account->name }}
            </td>
        </tr>
    @endif
    @stack('account_id_input_end')

    @stack('category_id_input_start')
    @if (!$hideCategory)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans_choice($textCategory, 1) }}:
            </td>

            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                {{ $transaction->category->name }}
            </td>
        </tr>
    @endif
    @stack('category_id_input_end')

    @stack('payment_method_input_start')
    @if (!$hidePaymentMethods)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans_choice($textPaymentMethods, 1) }}:
            </td>

            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                {{ !empty($payment_methods[$transaction->payment_method]) ? $payment_methods[$transaction->payment_method] : trans('general.na') }}
            </td>
        </tr>
    @endif
    @stack('payment_method_input_end')

    @stack('reference_input_start')
    @if (!$hideReference)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans($textReference) }}:
            </td>

            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                {{ $transaction->reference }}
            </td>
        </tr>
    @endif
    @stack('reference_input_end')

    @stack('description_input_start')
    @if (!$hideDescription)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans($textDescription) }}:
            </td>

            <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                <p style="font-size:12px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin: 0;">
                    {!! nl2br($transaction->description) !!}
                </p>
            </td>
        </tr>
    @endif
    @stack('description_input_end')
</table>

<table class="border-top-1" style="margin-top:15px;">
    <tr>
        <td style="width: 60%; padding: 15px 0 15px 0;">
            <h2 style="font-size: 12px; font-weight:600;">
                {{ trans($textPaidBy) }}
            </h2>
        </td>
    </tr>
</table>

<table class="border-bottom-1" style="padding-bottom:15px;">
    @if (!$hideContact)

    @if (! $hideContactInfo)
        <tr>
            <td style="margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                <strong>{{ trans($textContactInfo) }}</strong><br>
            </td>
        </tr>
    @endif

    @stack('name_input_start')
    @if (!$hideContactName)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans('general.name') }}
            </td>
            <td class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                {{ $transaction->contact->name }}
            </td>
        </tr>
    @endif
    @stack('name_input_end')

    @stack('address_input_start')
    @if (!$hideContactAddress)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans('general.address') }}
            </td>
            <td class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                {!! nl2br($transaction->contact->address) !!}
            </td>
        </tr>
    @endif
    @stack('address_input_end')

    @stack('tax_number_input_start')
    @if (!$hideContactTaxNumber)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans('general.tax_number') }}
            </td>
            <td class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                @if ($transaction->contact->tax_number)
                {{ trans('general.tax_number') }}: {{ $transaction->contact->tax_number }}
                @endif
            </td>
        </tr>
    @endif
    @stack('tax_number_input_end')

    @stack('phone_input_start')
    @if (!$hideContactPhone)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans('general.phone') }}
            </td>
            <td class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                @if ($transaction->contact->phone)
                {{ $transaction->contact->phone }}
                @endif
            </td>
        </tr>
    @endif
    @stack('phone_input_end')

    @stack('email_start')
    @if (!$hideContactEmail)
        <tr>
            <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                {{ trans('general.email') }}
            </td>
            <td class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                {{ $transaction->contact->email }}
            </td>
        </tr>
    @endif
    @stack('email_input_end')
    @endif
    <tr>
        <td></td>
    </tr>
</table>

@if (!$hideRelated)
    @if ($transaction->document)
    <table>
        <tr>
            <td style="padding:15px 0 0 0;">
                <h2 style="font-size: 12px; font-weight:600; margin-bottom: 15px;">{{ trans($textRelatedTransansaction) }}</h2>
            </td>
        </tr>
    </table>

    <table class="table" cellspacing="0" cellpadding="0" style="padding:15px 0 0 0;">
        <thead style="color:#424242; font-size:12px;">
            <tr class="border-bottom-1">
                <th class="item text-alignment-left text-left" style="padding:5px 0;">
                    @if (!$hideRelatedDocumentNumber)
                        <span style="font-size: 13px;">{{ trans_choice($textRelatedDocumentNumber, 1) }}</span> <br />
                    @endif

                    @if (!$hideRelatedContact)
                        <span style="font-weight:500;"> {{ trans_choice($textRelatedContact, 1) }} </span>
                    @endif
                </th>


                @if (!$hideRelatedDocumentDate)
                <th class="price" style=" padding:5px 0; text-align:center;">
                    {{ trans($textRelatedDocumentDate) }}
                </th>
                @endif


                <th class="price text-alignment-right text-right" style="padding: 5px 0;">
                    @if (!$hideRelatedDocumentAmount)
                        <span style="font-size: 13px;">{{ trans($textRelatedDocumentAmount) }}</span><br />
                    @endif

                    @if (!$hideRelatedAmount)
                        <span style="font-weight:500;">{{ trans($textRelatedAmount) }}</span>
                    @endif
                </th>

            </tr>
        </thead>

        <tbody>
            <tr>
                <td class="item text-alignment-left text-left" style="color:#424242; font-size:12px; padding-left:0;">
                    @if (!$hideRelatedDocumentNumber)
                        <a class="text-medium" style="border-bottom:1px solid;" href="{{ route($routeDocumentShow, $transaction->document->id) }}">
                            {{ $transaction->document->document_number }}
                        </a> <br />
                    @endif

                    @if (!$hideRelatedContact)
                        <span style="color: #6E6E6E"> {{ $transaction->document->contact_name }} </span>
                    @endif
                </td>

                @if (!$hideRelatedDocumentDate)
                <td class="price" style="color:#424242; font-size:12px; text-align:center;">
                    @date($transaction->document->due_at)
                </td>
                @endif

                <td class="price text-alignment-right text-right" style="color:#424242; font-size:12px; padding-right:0;">
                    @if (!$hideRelatedDocumentAmount)
                        @money($transaction->document->amount, $transaction->document->currency_code, true) <br />
                    @endif

                    @if (!$hideRelatedAmount)
                        <span style="color: #6E6E6E"> @money($transaction->amount, $transaction->currency_code, true) </span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    @endif
@endif

@if (!$hideAmount)
    <table style="text-align: right; margin-top:55px;">
        <tr>
            <td valign="center" style="width:80%; display:block; float:right; background-color: #55588B; -webkit-print-color-adjust: exact; color:#ffffff; border-radius: 5px;">
                <table>
                    <tr>
                        <td valign="center" style="width: 80%; padding:0; font-size: 14px; font-weight:600; color:#ffffff;">
                            {{ trans($textAmount) }}:
                        </td>
                        <td valign="center" style="width: 20%; padding:0; font-size: 14px; color:#ffffff;">
                            @money($transaction->amount, $transaction->currency_code, true)
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endif
