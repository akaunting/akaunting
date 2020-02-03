@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.reconciliations', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($reconciliation, [
            'method' => 'PATCH',
            'route' => ['reconciliations.update', $reconciliation->id],
            'role' => 'form',
            'id' => 'form-reconciliations',
            'class' => 'form-loading-button mb-0'
        ]) !!}

            <div class="card-header border-0">
                <h3 class="box-title">{{ trans_choice('general.transactions', 2) }}</h3>
            </div>

            {{ Form::hidden('account_id', $account->id) }}
            {{ Form::hidden('currency_code', $currency->code, ['id' => 'currency_code']) }}
            {{ Form::hidden('opening_balance', $opening_balance, ['id' => 'opening_balance']) }}
            {{ Form::hidden('closing_balance', $reconciliation->closing_balance, ['id' => 'closing_balance']) }}
            {{ Form::hidden('started_at', $reconciliation->started_at) }}
            {{ Form::hidden('ended_at', $reconciliation->ended_at) }}
            {{ Form::hidden('reconcile', $reconciliation->reconcile, ['id' => 'hidden-reconcile']) }}

            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr class="row">
                            <th  class="col-xs-4 col-sm-3 col-md-2 long-texts">{{ trans('general.date') }}</th>
                            <th class="col-md-2 text-center d-none d-md-block">{{ trans('general.description') }}</th>
                            <th class="col-md-2 col-sm-3 col-md-3 d-none d-sm-block">{{ trans_choice('general.contacts', 1) }}</th>
                            <th class="col-xs-4 col-sm-3 col-md-2 text-right">{{ trans('reconciliations.deposit') }}</th>
                            <th class="col-xs-4 col-sm-3 col-md-2 text-right long-texts">{{ trans('reconciliations.withdrawal') }}</th>
                            <th class="col-md-1 text-right d-none d-md-block">{{ trans('general.clear') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transactions as $item)
                            <tr class="row">
                                <td class="col-xs-4 col-sm-3 col-md-2 long-texts">@date($item->paid_at)</td>
                                <td class="col-md-2 text-center d-none d-md-block">{{ $item->description }}</td>
                                <td class="col-sm-3 col-md-3 d-none d-sm-block">{{ $item->contact->name }}</td>
                                @if ($item->type == 'income')
                                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                    <td class="col-xs-4 col-sm-3 col-md-2 text-right"> </td>
                                @else
                                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">&nbsp;</td>
                                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                @endif
                                <td class="col-md-1 text-right d-none d-md-block">{{ Form::checkbox('transactions['. $item->id . '_'. $item->type . ']', $item->amount, $item->reconciled) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($transactions->count())
                    <table class="table">
                        <tbody>
                            <tr class="row">
                                <th class="col-md-9 col-lg-11 text-right d-none d-md-block">{{ trans('reconciliations.closing_balance') }}:</th>
                                <td id="closing-balance" class="col-md-1 text-right">@money($reconciliation->closing_balance, $account->currency_code, true)</td>
                            </tr>
                            <tr class="row">
                                <th class="col-md-9 col-lg-11 text-right d-none d-md-block">{{ trans('reconciliations.cleared_amount') }}:</th>
                                <td id="cleared-amount" class="col-md-3 col-lg-1 text-right d-none d-md-block">@money('0', $account->currency_code, true)</td>
                            </tr>
                            <tr class="row">
                                <th class="col-md-9 col-lg-11 text-right d-none d-md-block">{{ trans('general.difference') }}:</th>
                                <td id="difference" class="col-md-3 col-lg-1 text-right d-none d-md-block">@money('0', $account->currency_code, true)</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="card-footer">
                <div class="row float-right">
                    @if ($transactions->count())
                        <a href="{{ route('reconciliations.index') }}" class="btn btn-outline-secondary header-button-top"><span class="fa fa-times"></span> &nbsp;{{ trans('general.cancel') }}</a>
                        {!! Form::button(
                            '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-check"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text"> ' . trans('reconciliations.reconcile') . '</span>',
                            [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-info button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}

                        {!! Form::button(
                            '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text"> ' . trans('general.save') . '</span>',
                            [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}
                    @else
                        <small>{{ trans('general.no_records') }}</small>
                    @endif
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/reconciliations.js?v=' . version('short')) }}"></script>
@endpush
