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
            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->transfer, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'transfers');

                    $this->transfer->attachMedia($media, 'attachment');
                }
            } elseif (!$this->request->file('attachment') && $this->transfer->attachment) {
                $this->deleteMediaModel($this->transfer, 'attachment', $this->request);
            }

            $expense_currency_code = $this->getCurrencyCode('from');
            $income_currency_code = $this->getCurrencyCode('to');

            $expense_currency_rate = $this->getCurrencyRate('from');
            $income_currency_rate = $this->getCurrencyRate('to');

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

    protected function getCurrencyCode($type)
    {
        $currency_code = $this->request->get($type . '_account_currency_code');

        if (empty($currency_code)) {
            $currency_code = Account::where('id', $this->request->get($type . '_account_id'))->pluck('currency_code')->first();
        }

        return $currency_code;
    }

    protected function getCurrencyRate($type)
    {
        $currency_rate = $this->request->get($type . '_account_rate');

        if (empty($currency_rate)) {
            $currency_rate = config('money.' . $this->getCurrencyCode($type) . '.rate');
        }

        return $currency_rate;
    }
}
