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
                    {{ Form::selectGroup('from_account_id', trans('transfers.from_account'), 'university', $accounts, null, ['required' => 'required', 'change' => 'onChangeFromAccount']) }}

                    {{ Form::selectGroup('to_account_id', trans('transfers.to_account'), 'university', $accounts, null, ['required' => 'required', 'change' => 'onChangeToAccount']) }}

                    <div class="d-none w-100" :class="[{'d-flex' : show_rate}]">
                        {!! Form::hidden('from_currency_code', null, ['id' => 'from_currency_code', 'v-model' => 'form.from_currency_code']) !!}

                        {{ Form::textGroup('from_account_rate', trans('transfers.from_account_rate'), 'sliders-h', [':disabled' => "form.from_currency_code == '" . setting('default.currency') . "'"]) }}

                        {!! Form::hidden('to_currency_code', null, ['id' => 'to_currency_code', 'v-model' => 'form.to_currency_code']) !!}

                        {{ Form::textGroup('to_account_rate', trans('transfers.to_account_rate'), 'sliders-h', [':disabled' => "form.to_currency_code == '" . setting('default.currency') . "'"]) }}
                    </div>

                    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'currency' => $currency, 'dynamic-currency' => 'currency'], 0) }}

                    {{ Form::dateGroup('transferred_at', trans('general.date'), 'calendar', ['id' => 'transferred_at', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('default.payment_method')) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                    {{ Form::fileGroup('attachment', trans('general.attachment'), '', ['dropzone-class' => 'w-100', 'multiple' => 'multiple', 'options' => ['acceptedFiles' => $file_types]], null , 'col-md-12') }}

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
    <script type="text/javascript">
    </script>

    <script src="{{ asset('public/js/banking/transfers.js?v=' . version('short')) }}"></script>
@endpush
