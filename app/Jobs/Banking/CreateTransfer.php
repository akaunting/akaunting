<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Banking\CreateTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transfer;
use App\Models\Setting\Category;
use App\Traits\Currencies;

class CreateTransfer extends Job implements HasOwner, HasSource, ShouldCreate
{
    use Currencies;

    public function handle(): Transfer
    {
        \DB::transaction(function () {
            $expense_currency_code = $this->getCurrencyCode('from');
            $income_currency_code = $this->getCurrencyCode('to');

            $expense_currency_rate = $this->getCurrencyRate('from');
            $income_currency_rate = $this->getCurrencyRate('to');

            $expense_transaction = $this->dispatch(new CreateTransaction([
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
                'created_by' => $this->request->get('created_by'),
            ]));

            $amount = $this->request->get('amount');

            // Convert amount if not same currency
            if ($expense_currency_code != $income_currency_code) {
                $amount = $this->convertBetween($amount, $expense_currency_code, $expense_currency_rate, $income_currency_code, $income_currency_rate);
            }

            $income_transaction = $this->dispatch(new CreateTransaction([
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
                'created_by' => $this->request->get('created_by'),
            ]));

            $this->model = Transfer::create([
                'company_id' => $this->request['company_id'],
                'expense_transaction_id' => $expense_transaction->id,
                'income_transaction_id' => $income_transaction->id,
                'created_by' => $this->request->get('created_by'),
            ]);

            // Upload attachment
            if ($this->request->file('attachment')) {
                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'transfers');

                    $this->model->attachMedia($media, 'attachment');
                }
            }
        });

        return $this->model;
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
