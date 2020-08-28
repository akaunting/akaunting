<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Traits\Currencies;

class UpdateTransfer extends Job
{
    use Currencies;

    protected $transfer;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $transfer
     * @param  $request
     */
    public function __construct($transfer, $request)
    {
        $this->transfer = $transfer;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Transfer
     */
    public function handle()
    {
        \DB::transaction(function () {
            $expense_currency_code = Account::where('id', $this->request->get('from_account_id'))->pluck('currency_code')->first();
            $income_currency_code = Account::where('id', $this->request->get('to_account_id'))->pluck('currency_code')->first();

            $expense_currency_rate = config('money.' . $expense_currency_code . '.rate');
            $income_currency_rate = config('money.' . $income_currency_code . '.rate');

            $expense_transaction = Transaction::findOrFail($this->transfer->expense_transaction_id);
            $income_transaction = Transaction::findOrFail($this->transfer->income_transaction_id);

            $expense_transaction->update([
                'company_id' => $this->request['company_id'],
                'type' => 'expense',
                'account_id' => $this->request->get('from_account_id'),
                'paid_at' => $this->request->get('transferred_at'),
                'currency_code' => $expense_currency_code,
                'currency_rate' => $expense_currency_rate,
                'amount' => $this->request->get('amount'),
                'contact_id' => 0,
                'description' => $this->request->get('description'),
                'category_id' => Category::transfer(), // Transfer Category ID
                'payment_method' => $this->request->get('payment_method'),
                'reference' => $this->request->get('reference'),
            ]);

            $amount = $this->request->get('amount');

            // Convert amount if not same currency
            if ($expense_currency_code != $income_currency_code) {
                $amount = $this->convertBetween($amount, $expense_currency_code, $expense_currency_rate, $income_currency_code, $income_currency_rate);
            }

            $income_transaction->update([
                'company_id' => $this->request['company_id'],
                'type' => 'income',
                'account_id' => $this->request->get('to_account_id'),
                'paid_at' => $this->request->get('transferred_at'),
                'currency_code' => $income_currency_code,
                'currency_rate' => $income_currency_rate,
                'amount' => $amount,
                'contact_id' => 0,
                'description' => $this->request->get('description'),
                'category_id' => Category::transfer(), // Transfer Category ID
                'payment_method' => $this->request->get('payment_method'),
                'reference' => $this->request->get('reference'),
            ]);

            $this->transfer->update([
                'company_id' => $this->request['company_id'],
                'expense_transaction_id' => $expense_transaction->id,
                'income_transaction_id' => $income_transaction->id,
            ]);
        });

        return $this->transfer;
    }
}
