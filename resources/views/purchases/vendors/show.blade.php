@extends('layouts.admin')

@section('title', $vendor->name)

@section('content')
    <div class="row">
        <div class="col-xl-3">
            <ul class="list-group mb-4">
                @stack('vendor_bills_count_start')
                <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                    {{ trans_choice('general.bills', 2) }}
                    <span class="badge badge-primary badge-pill">{{ $counts['bills'] }}</span>
                </li>
                @stack('vendor_bills_count_end')

                @stack('vendor_transactions_count_start')
                <li class="list-group-item d-flex justify-content-between align-items-center border-0 border-top-1">
                    {{ trans_choice('general.transactions', 2) }}
                    <span class="badge badge-primary badge-pill">{{ $counts['transactions'] }}</span>
                </li>
                @stack('vendor_transactions_count_end')
            </ul>

            <ul class="list-group mb-4">
                @stack('vendor_email_start')
                <li class="list-group-item border-0">
                    <div class="font-weight-600">{{ trans('general.email') }}</div>
                    <div><small class="long-texts" title="{{ $vendor->email }}">{{ $vendor->email }}</small></div>
                </li>
                @stack('vendor_email_end')

                @stack('vendor_phone_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('general.phone') }}</div>
                    <div><small class="long-texts" title="{{ $vendor->phone }}">{{ $vendor->phone }}</small></div>
                </li>
                @stack('vendor_phone_end')

                @stack('vendor_website_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('general.website') }}</div>
                    <div><small class="long-texts" title="{{ $vendor->website }}">{{ $vendor->website }}</small></div>
                </li>
                @stack('vendor_website_end')

                @stack('vendor_tax_number_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('general.tax_number') }}</div>
                    <div><small class="long-texts" title="{{ $vendor->tax_number }}">{{ $vendor->tax_number }}</small></div>
                </li>
                @stack('vendor_tax_number_end')

                @stack('vendor_address_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('general.address') }}</div>
                    <div><small>{{ $vendor->address }}</small></div>
                </li>
                @stack('vendor_address_end')

                @if ($vendor->reference)
                    @stack('vendor_reference_start')
                    <li class="list-group-item border-0 border-top-1">
                        <div class="font-weight-600">{{ trans('general.reference') }}</div>
                        <div><small class="long-texts" title="{{ $vendor->reference }}">{{ $vendor->reference }}</small></div>
                    </li>
                    @stack('vendor_reference_end')
                @endif
            </ul>

            @stack('vendor_edit_button_start')
            <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-info btn-block edit-sv"><i class="fas fa-edit"></i><b>{{ trans('general.edit') }}</b></a>
            @stack('vendor_edit_button_end')
        </div>

        <div class="col-xl-9">
            <div class="row mb--3">
                @stack('vendor_paid_card_start')
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
                @stack('vendor_paid_card_end')

                @stack('vendor_open_card_start')
                <div class="col-md-4">
                    <div class="card bg-gradient-warning border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('widgets.open_bills') }}</h5>
                                    <div class="dropdown-divider"></div>
                                    <span class="h2 font-weight-bold mb-0 text-white">@money($amounts['open'], setting('default.currency'), true)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('vendor_open_card_end')

                @stack('vendor_overdue_card_start')
                <div class="col-md-4">
                    <div class="card bg-gradient-danger border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('widgets.overdue_bills') }}</h5>
                                    <div class="dropdown-divider"></div>
                                    <span class="h2 font-weight-bold mb-0 text-white">@money($amounts['overdue'], setting('default.currency'), true)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('vendor_overdue_card_end')
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            @stack('vendor_transactions_tab_start')
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="transactions-tab" data-toggle="tab" href="#transactions-content" role="tab" aria-controls="transactions-content" aria-selected="true">{{ trans_choice('general.transactions', 2) }}</a>
                            </li>
                            @stack('vendor_transactions_tab_end')

                            @stack('vendor_bills_tab_start')
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="bills-tab" data-toggle="tab" href="#bills-content" role="tab" aria-controls="bills-content" aria-selected="false">{{ trans_choice('general.bills', 2) }}</a>
                            </li>
                            @stack('vendor_bills_tab_end')
                        </ul>
                    </div>
                    <div class="card">
                        <div class="tab-content" id="myTabContent">
                            @stack('vendor_transactions_content_start')
                            <div class="tab-pane fade show active" id="transactions-content" role="tabpanel" aria-labelledby="transactions-tab">
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
                                                    <td class="col-xs-6 col-sm-2">@date($item->paid_at)</td>
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
                            @stack('vendor_transactions_content_end')

                            @stack('vendor_bills_content_start')
                            <div class="tab-pane fade" id="bills-content" role="tabpanel" aria-labelledby="bills-tab">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover" id="tbl-bills">
                                        <thead class="thead-light">
                                            <tr class="row table-head-line">
                                                <th class="col-xs-4 col-sm-1">{{ trans_choice('general.numbers', 1) }}</th>
                                                <th class="col-xs-4 col-sm-3 text-right">{{ trans('general.amount') }}</th>
                                                <th class="col-sm-3 d-none d-sm-block text-left">{{ trans('bills.bill_date') }}</th>
                                                <th class="col-sm-3 d-none d-sm-block text-left">{{ trans('bills.due_date') }}</th>
                                                <th class="col-xs-4 col-sm-2">{{ trans_choice('general.statuses', 1) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bills as $item)
                                                <tr class="row align-items-center border-top-1 tr-py">
                                                    <td class="col-xs-4 col-sm-1"><a href="{{ route('bills.show', $item->id) }}">{{ $item->bill_number }}</a></td>
                                                    <td class="col-xs-4 col-sm-3 text-right">@money($item->amount, $item->currency_code, true)</td>
                                                    <td class="col-sm-3 d-none d-sm-block text-left">@date($item->billed_at)</td>
                                                    <td class="col-sm-3 d-none d-sm-block text-left">@date($item->due_at)</td>
                                                    <td class="col-xs-4 col-sm-2"><span class="badge badge-pill badge-{{ $item->status_label }} my--2">{{ trans('bills.statuses.' . $item->status) }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer py-4 table-action">
                                    <div class="row">
                                        @include('partials.admin.pagination', ['items' => $bills, 'type' => 'bills'])
                                    </div>
                                </div>
                            </div>
                            @stack('vendor_bills_content_end')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/purchases/vendors.js?v=' . version('short')) }}"></script>
@endpush
