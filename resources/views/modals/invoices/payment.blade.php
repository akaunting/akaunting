{!! Form::open([
    'id' => 'form-transaction',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => ['modals.invoices.invoice.transactions.store', $invoice->id],
    'novalidate' => true
]) !!}
    <base-alert type="warning" v-if="typeof form.response !== 'undefined' && form.response.error" v-html="form.response.message"></base-alert>

    <div class="row">
        {{ Form::dateGroup('paid_at', trans('general.date'), 'calendar', ['id' => 'paid_at', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

        {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'autofocus' => 'autofocus', 'currency' => $currency, 'dynamic-currency' => 'currency'], $invoice->grand_total) }}

        {{ Form::selectGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, setting('default.account'), ['required' => 'required', 'change' => 'onChangePaymentAccount']) }}

        {{ Form::textGroup('currency', trans_choice('general.currencies', 1), 'exchange-alt', ['disabled' => 'true'], $currencies[$invoice->currency_code]) }}

        {{ Form::textareaGroup('description', trans('general.description'), '', null, ['rows' => '3']) }}

        {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('default.payment_method'), ['required' => 'requied']) }}

        {{ Form::textGroup('reference', trans('general.reference'), 'fa fa-file', []) }}

        {!! Form::hidden('invoice_id', $invoice->id, ['id' => 'invoice_id', 'class' => 'form-control', 'required' => 'required']) !!}
        {!! Form::hidden('category_id', $invoice->category->id, ['id' => 'category_id', 'class' => 'form-control', 'required' => 'required']) !!}
        {!! Form::hidden('amount', $invoice->grand_total, ['id' => 'amount', 'class' => 'form-control', 'required' => 'required']) !!}
        {!! Form::hidden('currency_code', $invoice->currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
        {!! Form::hidden('currency_rate', $invoice->currency_rate, ['id' => 'currency_rate', 'class' => 'form-control', 'required' => 'required']) !!}

        {!! Form::hidden('type', 'income') !!}
    </div>
{!! Form::close() !!}
