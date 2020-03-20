@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.payments', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'payments.store',
            'id' => 'payment',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button needs-validation',
            'novalidate' => 'true'
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::dateGroup('paid_at', trans('general.date'), 'calendar', ['id' => 'paid_at', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], request()->get('paid_at', Date::now()->toDateString())) }}

                    {!! Form::hidden('currency_code', $account_currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
                    {!! Form::hidden('currency_rate', '1', ['id' => 'currency_rate']) !!}

                    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'currency' => $currency], 0.00) }}

                    {{ Form::selectAddNewGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, setting('default.account'), ['required' => 'required', 'path' => route('modals.accounts.create'), 'change' => 'onChangeAccount']) }}

                    {{ Form::selectAddNewGroup('contact_id', trans_choice('general.vendors', 1), 'user', $vendors, setting('default.vendor'), ['path' => route('modals.vendors.create')]) }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, setting('default.category'), ['required' => 'required', 'path' => route('modals.categories.create') . '?type=expense']) }}

                    {{ Form::recurring('create') }}

                    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('default.payment_method')) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                    {{ Form::fileGroup('attachment', trans('general.attachment')) }}

                    {{ Form::selectGroup('document_id', trans_choice('general.bills', 1), 'file-invoice', [], null, ['disabled' => 'true']) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('payments.index') }}
                </div>
            </div>

            {{ Form::hidden('type', 'expense') }}
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/purchases/payments.js?v=' . version('short')) }}"></script>
@endpush
