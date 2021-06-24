<div class="row border-bottom-1 pt-6 pb-6">
    <div class="col-16">
      <div class="text company">
          @stack('company_logo_start')
            @if (!$hideCompanyLogo)
                @if (!empty($document->contact->logo) && !empty($document->contact->logo->id))
                    <img src="{{ Storage::url($document->contact->logo->id) }}" height="128" width="128" alt="{{ $document->contact_name }}"/>
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

<div class="row border-bottom-1 w-100 mt-6 pb-6 d-flex flex-column">
    <h2 class="text-center text-uppercase mb-6">{{ trans('invoices.revenue_made') }}</h2>

    <div class="d-flex">
        <div class="d-flex flex-column col-lg-7 pl-0">
            <div class="d-flex mt-3">
                <div class="text company show-company col-lg-4 pl-0">
                    <p>
                        <strong>{{ trans('general.date') }}:</strong>
                    </p>

                    <p>
                        <strong>{{ trans_choice('general.accounts', 1) }}:</strong>
                    </p>

                    <p>
                        {{ trans_choice('general.categories', 1) }}:</strong>
                    </p>

                    <p>
                        <strong>{{ trans_choice('general.payment_methods', 1) }}:</strong>
                    </p>

                    <p>
                        <strong>{{ trans('general.reference') }}:</strong>
                    </p>

                    <p>
                        <strong>{{ trans('general.description') }}:</strong>
                    </p>
                </div>

                <div class="text company col-lg-8 pr-0 show-company show-company-value">
                    <p class="border-bottom-1">
                        @date($transaction->paid_at)
                    </p>

                    <p class="border-bottom-1">
                        {{ $transaction->account->name }}
                    </p>

                    <p class="border-bottom-1">
                        {{ $transaction->category->name }}
                    </p>

                    <p class="border-bottom-1">
                        {{ $payment_methods[$transaction->payment_method] }}
                    </p>

                    <p class="border-bottom-1">
                        {{ $transaction->reference }}
                    </p>

                    <p>
                        {!! nl2br($transaction->description) !!}
                    </p>
                </div>
            </div>

            <div class="text company mt-5">
                <h2>{{ trans('general.paid_by') }}</h2>

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
        </div>

        <div class="d-flex flex-column align-items-end col-lg-5 pr-0">
            <div class="card bg-success show-card-bg-success border-0 mt-4 mb-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col card-amount-badge text-center mt-3">
                            <h5 class="text-muted mb-0 text-white">{{ trans('general.amount') }}</h5>

                            <span class="h2 font-weight-bold mb-0 text-white">
                                @money($transaction->amount, $transaction->currency_code, true)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($transaction->document)
    <div class="row mt-3 mb-3">
        <div class="col-100">
            <div class="text">
                <h3>{{ trans('invoices.related_revenue') }}</h3>

                <table class="table table-flush table-hover mt-3">
                    <thead class="thead-light">
                        <tr class="border-bottom-1">
                            <th class="item text-left">
                                <span>{{ trans_choice('general.numbers', 1) }}</span>
                            </th>

                            <th class="quantity">
                                {{ trans_choice('general.customers', 1) }}
                            </th>

                            <th class="price">
                                {{ trans('invoices.invoice_date') }}
                            </th>

                            <th class="price">
                                {{ trans('invoices.invoice_date') }}
                            </th>

                            <th class="total">
                                {{ trans('general.amount') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-bottom-1">
                            <td class="item">
                                <a href="{{ route('invoices.show' , $transaction->document->id) }}">
                                    {{ $transaction->document->document_number }}
                                </a>
                            </td>

                            <td class="quantity">
                                {{ $transaction->document->contact_name }}
                            </td>

                            <td class="price">
                                @date($transaction->document->due_at)
                            </td>

                            <td class="price">
                                @money($transaction->document->amount, $transaction->document->currency_code, true)
                            </td>

                            <td class="total">
                                @money($transaction->amount, $transaction->currency_code, true)
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="row mt-3 mb-3">
        <p>
            {{ trans('invoices.overdue_revenue') }}: <strong style="font-weight: bold;">@money($transaction->amount, $transaction->currency_code, true)</strong>
        </p>
    </div>
@endif