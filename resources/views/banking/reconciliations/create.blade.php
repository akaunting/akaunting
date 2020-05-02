@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.reconciliations', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'method' => 'GET',
            'route' => 'reconciliations.create',
            'id' => 'form-create-reconciliation',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button mb-0',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row align-items-center">
                    {{ Form::dateGroup('started_at', trans('reconciliations.start_date'), 'calendar', ['id' => 'started_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], request('started_at', Date::now()->firstOfMonth()->toDateString()), 'col-xl-3') }}

                    {{ Form::dateGroup('ended_at', trans('reconciliations.end_date'), 'calendar', ['id' => 'ended_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], request('ended_at', Date::now()->endOfMonth()->toDateString()), 'col-xl-3') }}

                    {{ Form::moneyGroup('closing_balance', trans('reconciliations.closing_balance'), 'balance-scale', ['required' => 'required', 'autofocus' => 'autofocus', 'currency' => $currency], request('closing_balance', 0.00), 'col-xl-2') }}

                    {{ Form::selectAddNewGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, request('account_id', setting('default.account')), ['required' => 'required', 'path' => route('modals.accounts.create'), 'change' => 'onChangeAccount'], 'col-xl-2') }}

                    <div class="col-xl-2">
                        {!! Form::button('<span class="fa fa-list"></span> &nbsp;' . trans('reconciliations.transactions'), ['type' => 'button', '@click' => 'onReconcilition', 'class' => 'btn btn-success header-button-top']) !!}
                    </div>
                </div>
            </div>

        {!! Form::close() !!}
    </div>

    <div class="card">
        <div class="card-header border-0">
            <h3 class="mb-0">{{ trans_choice('general.transactions', 2) }}</h3>
        </div>

        {!! Form::open([
            'id' => 'reconciliation',
            'route' => 'reconciliations.store',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'role' => 'form',
            'class' => 'form-loading-button mb-0',
        ]) !!}

            {{ Form::hidden('account_id', $account->id) }}
            {{ Form::hidden('currency_code', $currency->code, ['id' => 'currency_code']) }}
            {{ Form::hidden('opening_balance', $opening_balance, ['id' => 'opening_balance']) }}
            {{ Form::hidden('closing_balance', request('closing_balance', '0'), ['id' => 'closing_balance']) }}
            {{ Form::hidden('started_at', request('started_at')) }}
            {{ Form::hidden('ended_at', request('ended_at')) }}
            {{ Form::hidden('reconcile', '0', ['id' => 'hidden-reconcile']) }}

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-xs-4 col-sm-3 col-md-2 long-texts">{{ trans('general.date') }}</th>
                            <th class="col-md-2 text-center d-none d-md-block">{{ trans('general.description') }}</th>
                            <th class="col-md-2 col-sm-3 col-md-3 d-none d-sm-block">{{ trans_choice('general.contacts', 1) }}</th>
                            <th class="col-xs-4 col-sm-3 col-md-2 text-right">{{ trans('reconciliations.deposit') }}</th>
                            <th class="col-xs-4 col-sm-3 col-md-2 text-right long-texts">{{ trans('reconciliations.withdrawal') }}</th>
                            <th class="col-md-1 text-right d-none d-md-block">{{ trans('general.clear') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transactions as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-xs-4 col-sm-3 col-md-2 long-texts">@date($item->paid_at)</td>
                                <td class="col-md-2 text-center d-none d-md-block">{{ $item->description }}</td>
                                <td class="col-md-2 col-sm-3 col-md-3 d-none d-sm-block">{{ $item->contact->name }}</td>
                                @if ($item->type == 'income')
                                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">N/A</td>
                                @else
                                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">N/A</td>
                                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                @endif
                                <td class="col-md-1 text-right d-none d-md-block">
                                    <div class="custom-control custom-checkbox">
                                        {{ Form::checkbox($item->type . '_' . $item->id, $item->amount, $item->reconciled, [
                                            'data-field' => 'transactions',
                                            'v-model' => 'form.transactions.' . $item->type . '_' . $item->id,
                                            'id' => 'transaction-' . $item->id . '-'. $item->type,
                                            'class' => 'custom-control-input',
                                            '@change' => 'onCalculate'
                                        ]) }}
                                        <label class="custom-control-label" for="transaction-{{ $item->id . '-'. $item->type }}"></label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($transactions->count())
                    <table class="table">
                        <tbody>
                            <tr class="row">
                                <th class="col-md-9 col-lg-11 text-right d-none d-md-block">{{ trans('reconciliations.closing_balance') }}:</th>
                                <td id="closing-balance" class="col-md-3 col-lg-1 text-right d-none d-md-block">
                                    {{ Form::moneyGroup('closing_balance_total', '', '', ['disabled' => true, 'required' => 'required', 'v-model' => 'totals.closing_balance', 'currency' => $currency, 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                    <span id="closing-balance-total" v-if="totals.closing_balance" v-html="totals.closing_balance"></span>
                                    <span v-else>@money(0, $account->currency_code, true)</span>
                                </td>
                            </tr>
                            <tr class="row">
                                <th class="col-md-9 col-lg-11 text-right d-none d-md-block">{{ trans('reconciliations.cleared_amount') }}:</th>
                                <td id="cleared-amount" class="col-md-3 col-lg-1 text-right d-none d-md-block">
                                    {{ Form::moneyGroup('cleared_amount_total', '', '', ['disabled' => true, 'required' => 'required', 'v-model' => 'totals.cleared_amount', 'currency' => $currency, 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                    <span id="cleared-amount-total" v-if="totals.cleared_amount" v-html="totals.cleared_amount"></span>
                                    <span v-else>@money(0, $account->currency_code, true)</span>
                                </td>
                            </tr>
                            <tr :class="difference" class="row">
                                <th class="col-md-9 col-lg-11 text-right d-none d-md-block">{{ trans('general.difference') }}:</th>
                                <td id="difference" class="col-md-3 col-lg-1 text-right d-none d-md-block">
                                    {{ Form::moneyGroup('difference_total', '', '', ['disabled' => true, 'required' => 'required', 'v-model' => 'totals.difference', 'currency' => $currency, 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                    <span id="difference-total" v-if="totals.difference" v-html="totals.difference"></span>
                                    <span v-else>@money(0, $account->currency_code, true)</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        @if ($transactions->count())
                            <div class="float-right">
                                <a href="{{ route('reconciliations.index') }}" class="btn btn-outline-secondary header-button-top"><span class="fa fa-times"></span> &nbsp;{{ trans('general.cancel') }}</a>

                                {!! Form::button(
                                    '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span :class="[{\'opacity-10\': reconcile}]" v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-check"></i></span>' . '<span :class="[{\'opacity-10\': reconcile}]" class="btn-inner--text"> ' . trans('reconciliations.reconcile') . '</span>',
                                    [':disabled' => 'reconcile || form.loading', '@click' => 'onReconcileSubmit', 'type' => 'button', 'class' => 'btn btn-icon btn-info header-button-top', 'data-loading-text' => trans('general.loading')]) !!}

                                {!! Form::button(
                                    '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text"> ' . trans('general.save') . '</span>',
                                    [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success header-button-top', 'data-loading-text' => trans('general.loading')]) !!}
                            </div>
                        @else
                            <div class="text-sm text-muted" id="datatable-basic_info" role="status" aria-live="polite">
                                <small>{{ trans('general.no_records') }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/reconciliations.js?v=' . version('short')) }}"></script>
@endpush
