@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.payments', 1)]))

@section('content')
    @if (($recurring = $payment->recurring) && ($next = $recurring->next()))
        <div class="callout callout-info">
            <h4>{{ trans('recurring.recurring') }}</h4>

            <p>{{ trans('recurring.message', [
                    'type' => mb_strtolower(trans_choice('general.payments', 1)),
                    'date' => $next->format($date_format)
                ]) }}
            </p>
        </div>
    @endif

    <div class="card">
        {!! Form::model($payment, [
            'method' => 'PATCH',
            'files' => true,
            'route' => ['payments.update', $payment->id],
            'role' => 'form',
            'id' => 'payment',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'class' => 'form-loading-button',
            'novalidate' => 'true'
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::dateGroup('paid_at', trans('general.date'), 'calendar', ['id' => 'paid_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($payment->paid_at)->toDateString()) }}

                    {!! Form::hidden('currency_code', $payment->currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
                    {!! Form::hidden('currency_rate', null, ['id' => 'currency_rate']) !!}

                    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'autofocus' => 'autofocus', 'currency' => $currency], $payment->amount) }}

                    {{ Form::selectGroup('account_id',  trans_choice('general.accounts', 1), 'university', $accounts, $payment->account_id, ['required' => 'required', 'change' => 'onChangeAccount']) }}

                    {{ Form::selectAddNewGroup('contact_id', trans_choice('general.vendors', 1), 'user', $vendors, $payment->contact_id, []) }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, $payment->category_id, ['required' => 'required']) }}

                    {{ Form::recurring('edit', $payment) }}

                    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, $payment->payment_method) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file',[]) }}

                    {{ Form::fileGroup('attachment', trans('general.attachment')) }}

                    @if ($payment->bill)
                        {{ Form::textGroup('document_id', trans_choice('general.bills', 1), 'file-invoice', ['disabled'], $payment->bill->bill_number) }}
                    @endif
                </div>
            </div>

            @permission('update-expenses-payments')
                <div class="card-footer">
                    <div class="row float-right">
                        {{ Form::saveButtons('expenses/payments') }}
                    </div>
                </div>
            @endpermission
            {{ Form::hidden('type', 'expense') }}
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/expenses/payments.js?v=' . version('short')) }}"></script>
@endpush
