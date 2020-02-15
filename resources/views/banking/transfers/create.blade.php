@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.transfers', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'transfers.store',
            'id' => 'transfer',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::selectGroup('from_account_id', trans('transfers.from_account'), 'university', $accounts, null, ['required' => 'required', 'change' => 'onChangeAccount']) }}

                    {{ Form::selectGroup('to_account_id', trans('transfers.to_account'), 'university', $accounts) }}

                    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'currency' => $currency], 0) }}

                    {{ Form::dateGroup('transferred_at', trans('general.date'), 'calendar', ['id' => 'transferred_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('default.payment_method')) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                    {!! Form::hidden('currency_code', null, ['id' => 'currency_code', 'v-model' => 'form.currency_code']) !!}
                    {!! Form::hidden('currency_rate', null, ['id' => 'currency_rate', 'v-model' => 'form.currency_rate']) !!}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('transfers.index') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/transfers.js?v=' . version('short')) }}"></script>
@endpush
