
@stack('company_start')
@if (!$hideCompany)
    <div class="row border-bottom-1 pt-6 pb-6">
        <div class="col-16">
            <div class="text company">
                @stack('company_logo_start')
                @if (!$hideCompanyLogo)
                    @if (!empty($transaction->contact->logo) && !empty($transaction->contact->logo->id))
                        <img src="{{ Storage::url($transaction->contact->logo->id) }}" height="128" width="128" alt="{{ $transaction->contact->name }}"/>
                    @else
                        <img src="{{ $logo }}" alt="{{ setting('company.name') }}"/>
                    @endif
                @endif
                @stack('company_logo_end')
            </div>
        </div>

        <div class="col-42">
            <div class="text company lead">
                @stack('company_details_start')
                @if (!$hideCompanyDetails)
                    @if (!$hideCompanyName)
                        <h2 class="mb-1">
                            {{ setting('company.name') }}
                        </h2>
                    @endif

                    @if (!$hideCompanyAddress)
                        <p>{!! nl2br(setting('company.address')) !!}</p>
                    @endif

                    @if (!$hideCompanyTaxNumber)
                        <p>
                            @if (setting('company.tax_number'))
                                {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                            @endif
                        </p>
                    @endif

                    @if (!$hideCompanyPhone)
                        <p>
                            @if (setting('company.phone'))
                                {{ setting('company.phone') }}
                            @endif
                        </p>
                    @endif

                    @if (!$hideCompanyEmail)
                        <p>{{ setting('company.email') }}</p>
                    @endif
                @endif
                @stack('company_details_end')
            </div>
        </div>
    </div>
@endif
@stack('company_end')

<div class="row border-bottom-1 w-100 mt-6 pb-6 d-flex flex-column">
    @if (!$hideContentTitle)
        <h2 class="text-center text-uppercase mb-6">
            {{ trans($textContentTitle) }}
        </h2>
    @endif

    <div class="d-flex">
        <div class="d-flex flex-column col-lg-7 pl-0">
            <div class="d-flex mt-3">
                <div class="text company show-company col-lg-4 pl-0">
                    @if (!$hidePaidAt)
                        <p>
                            <strong>{{ trans($textPaidAt) }}:</strong>
                        </p>
                    @endif

                    @if (!$hideAccount)
                        <p>
                            <strong>{{ trans_choice($textAccount, 1) }}:</strong>
                        </p>
                    @endif

                    @if (!$hideCategory)
                        <p>
                            {{ trans_choice($textCategory, 1) }}:</strong>
                        </p>
                    @endif

                    @if (!$hidePaymentMethods)
                        <p>
                            <strong>{{ trans_choice($textPaymentMethods, 1) }}:</strong>
                        </p>
                    @endif

                    @if (!$hideReference)
                        <p>
                            <strong>{{ trans($textReference) }}:</strong>
                        </p>
                    @endif

                    @if (!$hideDescription)
                        <p>
                            <strong>{{ trans($textDescription) }}:</strong>
                        </p>
                    @endif
                </div>

                <div class="text company col-lg-8 pr-0 show-company show-company-value">
                    @if (!$hidePaidAt)
                        <p class="border-bottom-1">
                            @date($transaction->paid_at)
                        </p>
                    @endif

                    @if (!$hideAccount)
                        <p class="border-bottom-1">
                            {{ $transaction->account->name }}
                        </p>
                    @endif

                    @if (!$hideCategory)
                        <p class="border-bottom-1">
                            {{ $transaction->category->name }}
                        </p>
                    @endif

                    @if (!$hidePaymentMethods)
                        <p class="border-bottom-1">
                            {{ $payment_methods[$transaction->payment_method] }}
                        </p>
                    @endif

                    @if (!$hideReference)
                        <p class="border-bottom-1">
                            {{ $transaction->reference }}
                        </p>
                    @endif

                    @if (!$hideDescription)
                        <p>
                            {!! nl2br($transaction->description) !!}
                        </p>
                    @endif
                </div>
            </div>

            @if (!$hideContact)
                <div class="text company mt-5">
                    <h2>{{ trans($textPaidBy) }}</h2>

                    @if ($hideContactInfo)
                        <strong>{{ trans($textContactInfo) }}</strong><br>
                    @endif

                    @stack('name_input_start')
                    @if (!$hideContactName)
                        <strong>{{ $transaction->contact->name }}</strong><br>
                    @endif
                    @stack('name_input_end')

                    @stack('address_input_start')
                    @if (!$hideContactAddress)
                        <p>{!! nl2br($transaction->contact->address) !!}</p>
                    @endif
                    @stack('address_input_end')

                    @stack('tax_number_input_start')
                    @if (!$hideContactTaxNumber)
                        <p>
                            @if ($transaction->contact->tax_number)
                                {{ trans('general.tax_number') }}: {{ $transaction->contact->tax_number }}
                            @endif
                        </p>
                    @endif
                    @stack('tax_number_input_end')

                    @stack('phone_input_start')
                    @if (!$hideContactPhone)
                        <p>
                            @if ($transaction->contact->phone)
                                {{ $transaction->contact->phone }}
                            @endif
                        </p>
                    @endif
                    @stack('phone_input_end')

                    @stack('email_start')
                    @if (!$hideContactEmail)
                        <p>
                            {{ $transaction->contact->email }}
                        </p>
                    @endif
                    @stack('email_input_end')
                </div>
            @endif
        </div>

        @if (!$hideAmount)
            <div class="d-flex flex-column align-items-end col-lg-5 pr-0">
                <div class="card bg-success show-card-bg-success border-0 mt-4 mb-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col card-amount-badge text-center mt-3">
                                <h5 class="text-muted mb-0 text-white">
                                    {{ trans($textAmount) }}
                                </h5>

                                <span class="h2 font-weight-bold mb-0 text-white">
                                    @money($transaction->amount, $transaction->currency_code, true)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if (!$hideReletad)
    @if ($transaction->document)
        <div class="row mt-3 mb-3">
            <div class="col-100">
                <div class="text">
                    <h3>{{ trans($textReleatedTransansaction) }}</h3>

                    <table class="table table-flush table-hover mt-3">
                        <thead class="thead-light">
                            <tr class="border-bottom-1">
                                @if (!$hideReletadDocumentNumber)
                                    <th class="item text-left">
                                        <span>{{ trans_choice($textReleatedDocumentNumber, 1) }}</span>
                                    </th>
                                @endif

                                @if (!$hideReletadContact)
                                    <th class="quantity">
                                        {{ trans_choice($textReleatedContact, 1) }}
                                    </th>
                                @endif

                                @if (!$hideReletadDocumentDate)
                                    <th class="price">
                                        {{ trans($textReleatedDocumentDate) }}
                                    </th>
                                @endif

                                @if (!$hideReletadDocumentAmount)
                                    <th class="price">
                                        {{ trans($textReleatedDocumentAmount) }}
                                    </th>
                                @endif

                                @if (!$hideReletadAmount)
                                    <th class="total">
                                        {{ trans($textReleatedAmount) }}
                                    </th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="border-bottom-1">
                                @if (!$hideReletadDocumentNumber)
                                    <td class="item">
                                        <a href="{{ route($routeDocumentShow, $transaction->document->id) }}">
                                            {{ $transaction->document->document_number }}
                                        </a>
                                    </td>
                                @endif

                                @if (!$hideReletadContact)
                                    <td class="quantity">
                                        {{ $transaction->document->contact_name }}
                                    </td>
                                @endif

                                @if (!$hideReletadDocumentDate)
                                    <td class="price">
                                        @date($transaction->document->due_at)
                                    </td>
                                @endif

                                @if (!$hideReletadDocumentAmount)
                                    <td class="price">
                                        @money($transaction->document->amount, $transaction->document->currency_code, true)
                                    </td>
                                @endif

                                @if (!$hideReletadAmount)
                                    <td class="total">
                                        @money($transaction->amount, $transaction->currency_code, true)
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="row mt-3 mb-3">
            <p class="text-right">
                {{ trans('invoices.overdue_revenue') }}: 
                <strong style="font-weight: bold;">
                    @money($transaction->amount, $transaction->currency_code, true)
                </strong>
            </p>
        </div>
    @endif
@endif