<div class="modal-body pb-0">
    {!! Form::open([
        'route' => ['modals.invoices.invoice.transactions.store', $invoice->id],
        'id' => 'transaction',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'transaction_form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}
        <div class="row">
            <base-alert type="warning" v-if="typeof transaction_form.response !== 'undefined' && transaction_form.response.error" v-html="transaction_form.response.message"></base-alert>

            {{ Form::dateGroup('paid_at', trans('general.date'), 'calendar', ['id' => 'paid_at', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off', 'v-model' => 'transaction_form.paid_at', 'v-error' => 'payment.errors.get("paid_at")', 'v-error-message' => 'payment.errors.get("paid_at")'], Date::now()->toDateString()) }}

            {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'autofocus' => 'autofocus', 'v-model' => 'transaction_form.amount', 'v-error' => 'payment.errors.get("amount")', 'v-error-message' => 'payment.errors.get("amount")', 'currency' => $currency], $invoice->grand_total) }}

            {{ Form::selectGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, setting('default.account'), ['required' => 'required', 'v-model' => 'transaction_form.account_id', 'v-error' => 'payment.errors.get("account_id")', 'v-error-message' => 'payment.errors.get("account_id")', 'change' => 'onChangePaymentAccount']) }}

            {{ Form::textGroup('currency', trans_choice('general.currencies', 1), 'exchange-alt', ['disabled' => 'true', 'v-model' => 'transaction_form.currency', 'v-error' => 'payment.errors.get("currency")', 'v-error-message' => 'payment.errors.get("currency")'], $currencies[$invoice->currency_code]) }}

            {{ Form::textareaGroup('description', trans('general.description'), '', null, ['rows' => '3', 'v-model' => 'transaction_form.description', 'v-error' => 'payment.errors.get("description")', 'v-error-message' => 'payment.errors.get("description")']) }}

            {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('default.payment_method'), ['required' => 'requied', 'v-model' => 'transaction_form.payment_method', 'v-error' => 'payment.errors.get("payment_method")', 'v-error-message' => 'payment.errors.get("payment_method")']) }}

            {{ Form::textGroup('reference', trans('general.reference'), 'fa fa-file', ['v-model' => 'transaction_form.reference', 'v-error' => 'payment.errors.get("reference")', 'v-error-message' => 'payment.errors.get("reference")']) }}

            {!! Form::hidden('invoice_id', $invoice->id, ['id' => 'invoice_id', 'class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::hidden('category_id', $invoice->category->id, ['id' => 'category_id', 'class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::hidden('currency_code', $invoice->currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::hidden('currency_rate', $invoice->currency_rate, ['id' => 'currency_rate', 'class' => 'form-control', 'required' => 'required']) !!}

            {!! Form::hidden('type', 'income') !!}
        </div>
    {!! Form::close() !!}
</div>
