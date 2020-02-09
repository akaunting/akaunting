@extends('layouts.admin')

@section('title', $customer->name)

@section('content')
    <div class="row">
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header border-bottom-0 show-transaction-card-header">
                    <a class="text-sm font-weight-600">{{ trans_choice('general.invoices', 2) }}</a> <a class="float-right text-xs">{{ $counts['invoices'] }}</a>
                </div>
                <div class="card-footer show-transaction-card-footer">
                    <a class="text-sm font-weight-600">{{ trans_choice('general.transactions', 2) }}</a> <a class="float-right text-xs">{{ $counts['transactions'] }}</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('auth.profile') }}</h4>
                </div>
                <div class="card-body d-grid">
                    <a class="text-sm font-weight-600">{{ trans('general.email') }}</a> <a class="text-xs long-texts">{{ $customer->email }}</a>
                        <div class="dropdown-divider"></div>
                    <a class="text-sm font-weight-600">{{ trans('general.phone') }}</a> <a class="text-xs long-texts">{{ $customer->phone }}</a>
                          <div class="dropdown-divider"></div>
                    <a class="text-sm font-weight-600">{{ trans('general.website') }}</a> <a class="text-xs long-texts">{{ $customer->website }}</a>
                        <div class="dropdown-divider"></div>
                    <a class="text-sm font-weight-600">{{ trans('general.tax_number') }}</a> <a class="text-xs long-texts">{{ $customer->tax_number }}</a>
                    @if ($customer->reference)
                        <div class="dropdown-divider"></div>
                        <a class="text-sm font-weight-600">{{ trans('general.reference') }}</a> <a class="text-xs long-texts">{{ $customer->reference }}</a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('general.address') }}</h4>
                </div>
                <div class="card-body">
                    <a class="text-xs m-0">
                        {{ $customer->address }}
                    </a>
                </div>
            </div>

            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-info btn-block edit-sv"><i class="fas fa-edit"></i><b>{{ trans('general.edit') }}</b></a>
        </div>

        <div class="col-xl-9">
            <div class="row mb--3">
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
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fas fa-hand-holding-usd mr-2"></i>{{ trans_choice('general.transactions', 2) }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="fa fa-money-bill mr-2"></i>{{ trans_choice('general.invoices', 2) }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover" id="tbl-transactions">
                                        <thead class="thead-light">
                                            <tr class="row table-head-line">
                                                <th class="col-xs-6 col-sm-3">{{ trans('general.date') }}</th>
                                                <th class="col-xs-6 col-sm-3 text-right">{{ trans('general.amount') }}</th>
                                                <th class="col-sm-3 d-none d-sm-block">{{ trans_choice('general.categories', 1) }}</th>
                                                <th class="col-sm-3 d-none d-sm-block">{{ trans_choice('general.accounts', 1) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transactions as $item)
                                                <tr class="row align-items-center border-top-1 tr-py">
                                                    <td class="col-xs-6 col-sm-3">@date($item->paid_at)</td>
                                                    <td class="col-xs-6 col-sm-3 text-right">@money($item->amount, $item->currency_code, true)</td>
                                                    <td class="col-sm-3 d-none d-sm-block">{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                                                    <td class="col-sm-3 d-none d-sm-block">{{ $item->account->name }}</td>
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

                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
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
                                                    <td class="col-xs-4 col-sm-1"><a href="{{ route('invoices.show', $item->id) }}">{{ $item->invoice_number }}</a></td>
                                                    <td class="col-xs-4 col-sm-3 text-right">@money($item->amount, $item->currency_code, true)</td>
                                                    <td class="col-sm-3 d-none d-sm-block text-left">@date($item->invoiced_at)</td>
                                                    <td class="col-sm-3 d-none d-sm-block text-left">@date($item->due_at)</td>
                                                    <td class="col-xs-4 col-sm-2"><span class="badge badge-pill badge-{{ $item->status_label }} my--2">{{ trans('invoices.statuses.' . $item->status) }}</span></td>
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
