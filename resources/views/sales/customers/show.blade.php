@extends('layouts.admin')

@section('title', $customer->name)

@section('new_button')
    <div class="dropup header-drop-top">
        <button type="button" class="btn btn-white btn-sm" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-chevron-down"></i>&nbsp; {{ trans('general.more_actions') }}
        </button>

        <div class="dropdown-menu" role="menu">
            @stack('button_dropdown_start')

            @stack('duplicate_button_start')
            @can('create-sales-customers')
                <a class="dropdown-item" href="{{ route('customers.duplicate', $customer->id) }}">
                    {{ trans('general.duplicate') }}
                </a>
            @endcan
            @stack('duplicate_button_end')

            <div class="dropdown-divider"></div>

            @stack('invoice_button_start')
            @can('create-sales-invoices')
                <a class="dropdown-item" href="{{ route('customers.create-invoice', $customer->id) }}">
                    {{ trans('invoices.create_invoice') }}
                </a>
            @endcan
            @stack('invoice_button_end')

            @stack('revenue_button_start')
            @can('create-sales-revenues')
                <a class="dropdown-item" href="{{ route('customers.create-revenue', $customer->id) }}">
                    {{ trans('revenues.create_revenue') }}
                </a>
            @endcan
            @stack('revenue_button_end')

            <div class="dropdown-divider"></div>

            @stack('delete_button_start')
            @can('delete-sales-customers')
                {!! Form::deleteLink($customer, 'customers.destroy') !!}
            @endcan
            @stack('delete_button_end')

            @stack('button_dropdown_end')
        </div>
    </div>

    @stack('edit_button_start')
    @can('update-sales-customers')
        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-white btn-sm">
            {{ trans('general.edit') }}
        </a>
    @endcan
    @stack('edit_button_end')
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3">
            <ul class="list-group mb-4">
                @stack('customer_invoices_count_start')
                <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                    {{ trans_choice('general.invoices', 2) }}
                    <span class="badge badge-primary badge-pill">{{ $counts['invoices'] }}</span>
                </li>
                @stack('customer_invoices_count_end')

                @stack('customer_transactions_count_start')
                <li class="list-group-item d-flex justify-content-between align-items-center border-0 border-top-1">
                    {{ trans_choice('general.transactions', 2) }}
                    <span class="badge badge-primary badge-pill">{{ $counts['transactions'] }}</span>
                </li>
                @stack('customer_transactions_count_end')
            </ul>

            <ul class="list-group mb-4">
                @stack('customer_email_start')
                <li class="list-group-item border-0">
                    <div class="font-weight-600">{{ trans('general.email') }}</div>
                    <div><small class="long-texts" title="{{ $customer->email }}">{{ $customer->email }}</small></div>
                </li>
                @stack('customer_email_end')

                @stack('customer_phone_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('general.phone') }}</div>
                    <div><small class="long-texts" title="{{ $customer->phone }}">{{ $customer->phone }}</small></div>
                </li>
                @stack('customer_phone_end')

                @stack('customer_website_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('general.website') }}</div>
                    <div><small class="long-texts" title="{{ $customer->website }}">{{ $customer->website }}</small></div>
                </li>
                @stack('customer_website_end')

                @stack('customer_tax_number_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('general.tax_number') }}</div>
                    <div><small class="long-texts" title="{{ $customer->tax_number }}">{{ $customer->tax_number }}</small></div>
                </li>
                @stack('customer_tax_number_end')

                @stack('customer_address_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('general.address') }}</div>
                    <div><small>{{ $customer->address }}</small></div>
                </li>
                @stack('customer_address_end')

                @if ($customer->reference)
                    @stack('customer_reference_start')
                    <li class="list-group-item border-0 border-top-1">
                        <div class="font-weight-600">{{ trans('general.reference') }}</div>
                        <div><small class="long-texts" title="{{ $customer->reference }}">{{ $customer->reference }}</small></div>
                    </li>
                    @stack('customer_reference_end')
                @endif
            </ul>

            @stack('customer_edit_button_start')
            @stack('customer_edit_button_end')
        </div>

        <div class="col-xl-9">
            <div class="row mb--3">
                @stack('customer_paid_card_start')
                <div class="col-md-4">
                    <div class="card bg-gradient-success border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('general.paid') }}</h5>
                                    <div class="dropdown-divider"></div>
                                    <span class="h2 font-weight-bold mb-0 text-white">@money($amounts['paid'], setting('default.currency'), true)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('customer_paid_card_end')

                @stack('customer_open_card_start')
                <div class="col-md-4">
                    <div class="card bg-gradient-warning border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('widgets.open_invoices') }}</h5>
                                    <div class="dropdown-divider"></div>
                                    <span class="h2 font-weight-bold mb-0 text-white">@money($amounts['open'], setting('default.currency'), true)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('customer_open_card_end')

                @stack('customer_overdue_card_start')
                <div class="col-md-4">
                    <div class="card bg-gradient-danger border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('widgets.overdue_invoices') }}</h5>
                                    <div class="dropdown-divider"></div>
                                    <span class="h2 font-weight-bold mb-0 text-white">@money($amounts['overdue'], setting('default.currency'), true)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('customer_overdue_card_end')
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            @stack('customer_invoices_tab_start')
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="invoices-tab" data-toggle="tab" href="#invoices-content" role="tab" aria-controls="invoices-content" aria-selected="true">
                                    {{ trans_choice('general.invoices', 2) }}
                                </a>
                            </li>
                            @stack('customer_invoices_tab_end')

                            @stack('customer_transactions_tab_start')
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="transactions-tab" data-toggle="tab" href="#transactions-content" role="tab" aria-controls="transactions-content" aria-selected="false">
                                    {{ trans_choice('general.transactions', 2) }}
                                </a>
                            </li>
                            @stack('customer_transactions_tab_end')
                        </ul>
                    </div>

                    <div class="card">
                        <div class="tab-content" id="cutomer-tab-content">
                            @stack('customer_invoices_content_start')
                            <div class="tab-pane fade show active" id="invoices-content" role="tabpanel" aria-labelledby="invoices-tab">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover" id="tbl-invoices">
                                        <thead class="thead-light">
                                            <tr class="row table-head-line">
                                                <th class="col-xs-4 col-sm-1">{{ trans_choice('general.numbers', 1) }}</th>
                                                <th class="col-xs-4 col-sm-3 text-right">{{ trans('general.amount') }}</th>
                                                <th class="col-sm-3 d-none d-sm-block text-left">{{ trans('invoices.invoice_date') }}</th>
                                                <th class="col-sm-3 d-none d-sm-block text-left">{{ trans('invoices.due_date') }}</th>
                                                <th class="col-xs-4 col-sm-2">{{ trans_choice('general.statuses', 1) }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($invoices as $item)
                                                <tr class="row align-items-center border-top-1 tr-py">
                                                    <td class="col-xs-4 col-sm-1"><a href="{{ route('invoices.show', $item->id) }}">{{ $item->document_number }}</a></td>
                                                    <td class="col-xs-4 col-sm-3 text-right">@money($item->amount, $item->currency_code, true)</td>
                                                    <td class="col-sm-3 d-none d-sm-block text-left">@date($item->issued_at)</td>
                                                    <td class="col-sm-3 d-none d-sm-block text-left">@date($item->due_at)</td>
                                                    <td class="col-xs-4 col-sm-2"><span class="badge badge-pill badge-{{ $item->status_label }} my--2">{{ trans('documents.statuses.' . $item->status) }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-footer py-4 table-action">
                                    <div class="row">
                                        @include('partials.admin.pagination', ['items' => $invoices, 'type' => 'invoices'])
                                    </div>
                                </div>
                            </div>
                            @stack('customer_invoices_content_end')

                            @stack('customer_transactions_content_start')
                            <div class="tab-pane fade" id="transactions-content" role="tabpanel" aria-labelledby="transactions-tab">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover" id="tbl-transactions">
                                        <thead class="thead-light">
                                            <tr class="row table-head-line">
                                                <th class="col-xs-6 col-sm-2">{{ trans('general.date') }}</th>
                                                <th class="col-xs-6 col-sm-2 text-right">{{ trans('general.amount') }}</th>
                                                <th class="col-sm-4 d-none d-sm-block">{{ trans_choice('general.categories', 1) }}</th>
                                                <th class="col-sm-4 d-none d-sm-block">{{ trans_choice('general.accounts', 1) }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($transactions as $item)
                                                <tr class="row align-items-center border-top-1 tr-py">
                                                    <td class="col-xs-6 col-sm-2"><a href="{{ route('revenues.show', $item->id) }}">@date($item->paid_at)</a></td>
                                                    <td class="col-xs-6 col-sm-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                                    <td class="col-sm-4 d-none d-sm-block">{{ $item->category->name }}</td>
                                                    <td class="col-sm-4 d-none d-sm-block">{{ $item->account->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-footer py-4 table-action">
                                    <div class="row">
                                        @include('partials.admin.pagination', ['items' => $transactions, 'type' => 'transactions'])
                                    </div>
                                </div>
                            </div>
                            @stack('customer_transactions_content_end')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
