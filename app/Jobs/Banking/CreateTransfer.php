<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;

class CreateTransfer extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Transfer
     */
    public function handle()
    {
        $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

        $expense_currency_code = Account::where('id', $this->request->get('from_account_id'))->pluck('currency_code')->first();
        $income_currency_code = Account::where('id', $this->request->get('to_account_id'))->pluck('currency_code')->first();

        $expense_transaction = Transaction::create([
            'company_id' => $this->request['company_id'],
            'type' => 'expense',
            'account_id' => $this->request->get('from_account_id'),
            'paid_at' => $this->request->get('transferred_at'),
            'currency_code' => $expense_currency_code,
            'currency_rate' => $currencies[$expense_currency_code],
            'amount' => $this->request->get('amount'),
            'contact_id' => 0,
            'description' => $this->request->get('description'),
            'category_id' => Category::transfer(), // Transfer Category ID
            'payment_method' => $this->request->get('payment_method'),
            'reference' => $this->request->get('reference'),
        ]);

        // Convert amount if not same currency
        if ($expense_currency_code != $income_currency_code) {
            $default_currency = setting('default.currency', 'USD');

            $default_amount = $this->request->get('amount');

            if ($default_currency != $expense_currency_code) {
                $default_amount_model = new Transfer();

                $default_amount_model->default_currency_code = $default_currency;
                $default_amount_model->amount = $this->request->get('amount');
                $default_amount_model->currency_code = $expense_currency_code;
                $default_amount_model->currency_rate = $currencies[$expense_currency_code];

                $default_amount = $default_amount_model->getAmountConvertedToDefault();
            }

            $transfer_amount = new Transfer();

            $transfer_amount->default_currency_code = $expense_currency_code;
            $transfer_amount->amount = $default_amount;
            $transfer_amount->currency_code = $income_currency_code;
            $transfer_amount->currency_rate = $currencies[$income_currency_code];

            $amount = $transfer_amount->getAmountConvertedFromDefault();
        } else {
            $amount = $this->request->get('amount');
        }

        $income_transaction = Transaction::create([
            'company_id' => $this->request['company_id'],
            'type' => 'income',
            'account_id' => $this->request->get('to_account_id'),
            'paid_at' => $this->request->get('transferred_at'),
            'currency_code' => $income_currency_code,
            'currency_rate' => $currencies[$income_currency_code],
            'amount' => $amount,
            'contact_id' => 0,
            'description' => $this->request->get('description'),
            'category_id' => Category::transfer(), // Transfer Category ID
            'payment_method' => $this->request->get('payment_method'),
            'reference' => $this->request->get('reference'),
        ]);

        $transfer = Transfer::create([
            'company_id' => $this->request['company_id'],
            'expense_transaction_id' => $expense_transaction->id,
            'income_transaction_id' => $income_transaction->id,
        ]);

        return $transfer;
    }
}
