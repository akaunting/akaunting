@extends('layouts.admin')

@section('title', $account->name)

@section('new_button')
    <div class="dropup header-drop-top">    
        <button type="button" class="btn btn-white btn-sm" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-chevron-down"></i>&nbsp; {{ trans('general.more_actions') }}
        </button>

        <div class="dropdown-menu" role="menu">
            @stack('button_dropdown_start')

            @stack('duplicate_button_start')
            @can('create-banking-accounts')
                <a class="dropdown-item" href="{{ route('accounts.duplicate', $account->id) }}">
                    {{ trans('general.duplicate') }}
                </a>
            @endcan
            @stack('duplicate_button_end')

            <div class="dropdown-divider"></div>

            @stack('revenue_button_start')
            @can('create-sales-revenues')
                <a class="dropdown-item" href="{{ route('accounts.create-revenue', $account->id) }}">
                    {{ trans('general.add_income')}}
                </a>
            @endcan
            @stack('revenue_button_end')

            @stack('payment_button_start')
            @can('create-purchases-payments')
                <a class="dropdown-item" href="{{ route('accounts.create-payment', $account->id) }}">
                    {{ trans('general.add_expense') }}
                </a>
            @endcan
            @stack('payment_button_end')

            @stack('transfer_button_start')
            @can('create-banking-transfers')
                <a class="dropdown-item" href="{{ route('accounts.create-transfer', $account->id) }}">
                    {{ trans('general.add_transfer') }}
                </a>
            @endcan
            @stack('transfer_button_end')

            <div class="dropdown-divider"></div>

            @stack('performance_button_start')
            @can('read-banking-accounts')
                <a class="dropdown-item" href="{{ route('accounts.see-performance', $account->id) }}">
                    {{ trans('accounts.see_performance') }}
                </a>
            @endcan
            @stack('performance_button_end')

            <div class="dropdown-divider"></div>

            @stack('delete_button_start')
            @can('delete-sales-customers')
                {!! Form::deleteLink($account, 'accounts.destroy') !!}
            @endcan
            @stack('delete_button_end')

            @stack('button_dropdown_end')
        </div>
        @stack('edit_button_start')
            @can('update-sales-customers')
                <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-white btn-sm">
                    {{ trans('general.edit') }}
                </a>
            @endcan
        @stack('edit_button_end')
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3">
            <ul class="list-group mb-4">
                @stack('account_number_start')
                <li class="list-group-item d-flex justify-content-between align-items-center border-0 font-weight-600">
                    {{ trans_choice('general.accounts', 1) }} {{ trans_choice('accounts.number', 2) }}
                    <small>{{ $account->number}}</small>
                </li>
                @stack('account_number_end')

                @stack('account_currency_start')
                <li class="list-group-item d-flex justify-content-between align-items-center border-0 border-top-1 font-weight-600">
                    {{ trans_choice('general.currencies', 2) }}
                    <small>{{ $account->currency->name}}</small>
                </li>
                @stack('account_currency_end')

                @stack('account_starting_balance_start')
                <li class="list-group-item d-flex justify-content-between align-items-center border-0 border-top-1 font-weight-600">
                    {{ trans_choice('accounts.opening_balance', 2) }}
                    <small>@money($account->opening_balance, $account->currency_code, true)</small>
                </li>
                @stack('account_starting_balance_end')
            </ul>

            <ul class="list-group mb-4">
                @stack('bank_name_start')
                <li class="list-group-item border-0">
                    <div class="font-weight-600">{{ trans('accounts.bank_name') }}</div>
                    <div><small>{{ $account->bank_name }}</small></div>
                </li>
                @stack('bank_name_end')

                @stack('account_phone_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('accounts.bank_phone') }}</div>
                    <div><small>{{ $account->bank_phone }}</small></div>
                </li>
                @stack('account_phone_end')

                @stack('account_address_start')
                <li class="list-group-item border-0 border-top-1">
                    <div class="font-weight-600">{{ trans('accounts.bank_address') }}</div>
                    <div><small>{{ $account->bank_address }}</small></div>
                </li>
                @stack('account_address_end')
            </ul>
        </div>

        <div class="col-xl-9">
            <div class="row mb--3">
                @stack('account_incoming_card_start')
                <div class="col-md-4">
                    <div class="card bg-gradient-info border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('accounts.incoming') }}</h5>
                                    <div class="dropdown-divider"></div>
                                    <span class="h2 font-weight-bold mb-0 text-white">@money($account->income_balance, $account->currency_code, true)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('account_incoming_card_end')

                @stack('account_outgoing_card_start')
                <div class="col-md-4">
                    <div class="card bg-gradient-danger border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('accounts.outgoing') }}</h5>
                                    <div class="dropdown-divider"></div>
                                    <span class="h2 font-weight-bold mb-0 text-white">@money($account->expense_balance, $account->currency_code, true)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('account_outgoing_card_end')

                @stack('account_balance_card_start')
                <div class="col-md-4">
                    <div class="card bg-gradient-success border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('widgets.account_balance') }}</h5>
                                    <div class="dropdown-divider"></div>
                                    <span class="h2 font-weight-bold mb-0 text-white">@money($account->balance, $account->currency_code, true)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('account_balance_card_end')
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            @stack('account_transactions_tab_start')
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="transactions-tab" data-toggle="tab" href="#transactions-content" role="tab" aria-controls="transactions-content" aria-selected="true">
                                    {{ trans_choice('general.transactions', 2) }}
                                </a>
                            </li>
                            @stack('account_transactions_tab_end')

                            @stack('account_transfers_tab_start')
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="transfers-tab" data-toggle="tab" href="#transfers-content" role="tab" aria-controls="transfers-content" aria-selected="false">
                                    {{ trans_choice('general.transfers', 2) }}
                                </a>
                            </li>
                            @stack('account_transfers_tab_end')
                        </ul>
                    </div>

                    <div class="card">
                        <div class="tab-content" id="account-tab-content">
                            @stack('account_transactions_content_start')
                            <div class="tab-pane fade show active" id="transactions-content" role="tabpanel" aria-labelledby="transactions-tab">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover" id="tbl-transactions">
                                        <thead class="thead-light">
                                            <tr class="row table-head-line">
                                                <th class="col-sm-3">{{ trans_choice('general.date', 1) }}</th>
                                                <th class="col-sm-3">{{ trans('general.amount') }}</th>
                                                <th class="col-sm-3">{{ trans_choice('general.types', 1) }}</th>
                                                <th class="col-sm-3">{{ trans_choice('general.categories', 1) }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($transactions as $item)
                                                <tr class="row align-items-center border-top-1 tr-py">
                                                    <td class="col-sm-3"><a href="{{ route($item->route_name, $item->route_id) }}">@date($item->paid_at)</a></td>
                                                    <td class="col-sm-3">@money($item->amount, $item->currency_code, true)</td>
                                                    <td class="col-sm-3">{{ $item->type_title }}</td>
                                                    <td class="col-sm-3">{{ $item->category->name }}</td>
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
                            @stack('account_transactions_content_end')

                            @stack('account_transfers_content_start')
                            <div class="tab-pane fade" id="transfers-content" role="tabpanel" aria-labelledby="transfers-tab">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover" id="tbl-transfers">
                                        <thead class="thead-light">
                                            <tr class="row table-head-line">
                                                <th class="col-sm-3">{{ trans('general.date') }}</th>
                                                <th class="col-sm-3">{{ trans('general.amount') }}</th>
                                                <th class="col-sm-3">{{ trans_choice('transfers.from_account', 1) }}</th>
                                                <th class="col-sm-3">{{ trans_choice('transfers.to_account', 1) }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($transfers as $item)
                                                <tr class="row align-items-center border-top-1 tr-py">
                                                    <td class="col-sm-3"><a href="{{ route('transfers.show', $item->id) }}">@date($item->expense_transaction->paid_at)</a></td>
                                                    <td class="col-sm-3">@money($item->expense_transaction->amount, $item->expense_transaction->currency_code, true)</td>
                                                    <td class="col-sm-3">{{ $item->expense_transaction->account->name }}</td>
                                                    <td class="col-sm-3">{{ $item->income_transaction->account->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-footer py-4 table-action">
                                    <div class="row">
                                        @include('partials.admin.pagination', ['items' => $transfers, 'type' => 'transfers'])
                                    </div>
                                </div>
                            </div>
                            @stack('account_transfers_content_end')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts_start')
    <script src="{{ asset('public/js/banking/accounts.js?v=' . version('short')) }}"></script>
@endpush
