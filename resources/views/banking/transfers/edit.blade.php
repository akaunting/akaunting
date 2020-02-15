@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.transfers', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($transfer, [
            'id' => 'transfer',
            'method' => 'PATCH',
            'route' => ['transfers.update', $transfer->id],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::selectGroup('from_account_id', trans('transfers.from_account'), 'university', $accounts, $transfer->from_account_id, ['required' => 'required', 'change' => 'onChangeAccount']) }}

                    {{ Form::selectGroup('to_account_id', trans('transfers.to_account'), 'university', $accounts, $transfer->to_account_id) }}

                    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'currency' => $currency], $transfer->amount) }}

                    {{ Form::dateGroup('transferred_at', trans('general.date'), 'calendar', ['id' => 'transferred_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($transfer->transferred_at)->toDateString()) }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, $transfer->payment_method) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                    {!! Form::hidden('currency_code', $currency->code, ['id' => 'currency_code', 'v-model' => 'form.currency_code']) !!}
                    {!! Form::hidden('currency_rate', $currency->rate, ['id' => 'currency_rate', 'v-model' => 'form.currency_rate']) !!}
                </div>
            </div>

            @permission('update-banking-transfers')
                <div class="card-footer">
                    <div class="row save-buttons">
                        {{ Form::saveButtons('transfers.index') }}
                    </div>
                </div>
            @endpermission
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/transfers.js?v=' . version('short')) }}"></script>
@endpush
