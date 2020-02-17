@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.revenues', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'revenues.store',
            'id' => 'revenue',
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

                    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'autofocus' => 'autofocus', 'currency' => $currency], 0) }}

                    {{ Form::selectGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, setting('default.account'), ['required' => 'required', 'change' => 'onChangeAccount']) }}

                    {{ Form::selectAddNewGroup('contact_id', trans_choice('general.customers', 1), 'user', $customers, setting('default.contact'), ['path' => route('modals.customers.create')]) }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, setting('default.category'), ['required' => 'required', 'path' => route('modals.categories.create') . '?type=income']) }}

                    {{ Form::recurring('create') }}

                    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('default.payment_method')) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                    {{ Form::fileGroup('attachment', trans('general.attachment')) }}

                    {{ Form::selectGroup('document_id', trans_choice('general.invoices', 1), 'file-invoice', [], null, ['disabled' => 'disabled']) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('revenues.index') }}
                </div>
            </div>

            {{ Form::hidden('type', 'income') }}
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/sales/revenues.js?v=' . version('short')) }}"></script>
@endpush
