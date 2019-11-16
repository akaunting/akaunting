@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.revenues', 1)]))

@section('content')
    @if (($recurring = $revenue->recurring) && ($next = $recurring->next()))
        <div class="callout callout-info">
            <h4>{{ trans('recurring.recurring') }}</h4>

            <p>{{ trans('recurring.message', [
                    'type' => mb_strtolower(trans_choice('general.revenues', 1)),
                    'date' => $next->format($date_format)
                ]) }}
            </p>
        </div>
    @endif

    <div class="card">
        {!! Form::model($revenue, [
            'method' => 'PATCH',
            'files' => true,
            'route' => ['revenues.update', $revenue->id],
            'role' => 'form',
            'id' => 'revenue',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'class' => 'form-loading-button',
            'novalidate' => 'true'
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::dateGroup('paid_at', trans('general.date'), 'calendar', ['id' => 'paid_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($revenue->paid_at)->toDateString()) }}

                    {!! Form::hidden('currency_code', $revenue->currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
                    {!! Form::hidden('currency_rate', null, ['id' => 'currency_rate']) !!}

                    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'autofocus' => 'autofocus', 'currency' => $currency], $revenue->amount) }}

                    {{ Form::selectGroup('account_id',  trans_choice('general.accounts', 1), 'university', $accounts, $revenue->account_id, ['required' => 'required', 'change' => 'onChangeAccount']) }}

                    {{ Form::selectAddNewGroup('contact_id', trans_choice('general.customers', 1), 'user', $customers, $revenue->contact_id, []) }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, $revenue->category_id) }}

                    {{ Form::recurring('edit', $revenue) }}

                    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, $revenue->payment_method) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file',[]) }}

                    {{ Form::fileGroup('attachment', trans('general.attachment')) }}

                    @if ($revenue->invoice)
                        {{ Form::textGroup('document_id', trans_choice('general.invoices', 1), 'file-invoice', ['disabled'], $revenue->invoice->invoice_number) }}
                    @endif
                </div>
            </div>

            @permission('update-incomes-revenues')
                <div class="card-footer">
                    <div class="row float-right">
                        {{ Form::saveButtons('incomes/revenues') }}
                    </div>
                </div>
            @endpermission

            {{ Form::hidden('type', 'income') }}
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/incomes/revenues.js?v=' . version('short')) }}"></script>
@endpush
