<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Document\CreateDocumentHistory;
use App\Events\Document\PaidAmountCalculated;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Traits\Currencies;
use App\Utilities\Date;

class CreateBankingDocumentTransaction extends Job
{
    use Currencies;

    protected $model;

    protected $request;

    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @param  $model
     * @param  $request
     */
    public function __construct($model, $request)
    {
        $this->model = $model;
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Transaction
     */
    public function handle()
    {
        $this->prepareRequest();

        $this->checkAmount();

        \DB::transaction(function () {
            $this->transaction = $this->dispatch(new CreateTransaction($this->request));

            // Upload attachment
            if ($this->request->file('attachment')) {
                $media = $this->getMedia($this->request->file('attachment'), 'transactions');

                $this->transaction->attachMedia($media, 'attachment');
            }

            $this->model->save();

            $this->createHistory();
        });

        return $this->transaction;
    }

    protected function prepareRequest()
    {
        if (!isset($this->request['amount'])) {
            $this->model->paid_amount = $this->model->paid;
            event(new PaidAmountCalculated($this->model));

            $this->request['amount'] = $this->model->amount - $this->model->paid_amount;
        }

        $currency_code = !empty($this->request['currency_code']) ? $this->request['currency_code'] : $this->model->currency_code;

        $this->request['company_id'] = $this->model->company_id;
        $this->request['currency_code'] = isset($this->request['currency_code']) ? $this->request['currency_code'] : $this->model->currency_code;
        $this->request['paid_at'] = isset($this->request['paid_at']) ? $this->request['paid_at'] : Date::now()->format('Y-m-d');
        $this->request['currency_rate'] = config('money.' . $currency_code . '.rate');
        $this->request['account_id'] = isset($this->request['account_id']) ? $this->request['account_id'] : setting('default.account');
        $this->request['document_id'] = isset($this->request['document_id']) ? $this->request['document_id'] : $this->model->id;
        $this->request['contact_id'] = isset($this->request['contact_id']) ? $this->request['contact_id'] : $this->model->contact_id;
        $this->request['category_id'] = isset($this->request['category_id']) ? $this->request['category_id'] : $this->model->category_id;
        $this->request['payment_method'] = isset($this->request['payment_method']) ? $this->request['payment_method'] : setting('default.payment_method');
        $this->request['notify'] = isset($this->request['notify']) ? $this->request['notify'] : 0;

        if ($this->request['mark_paid'] || $this->request['account_id'] == setting('default.account')) {
            $account = Account::find((int) $this->request['account_id']);

            $code = $account->currency_code;
            $rate = config('money.' . $account->currency_code . '.rate');

            if ($account->currency_code != $this->model->currency_code) {
                $this->request['currency_code'] = $code;
                $this->request['currency_rate'] = $rate;

                $this->request['amount'] = $this->convertBetween($this->request['amount'], $this->model->currency_code, $this->model->currency_rate, $code, $rate);
            }
        }
    }

    protected function checkAmount()
    {
        $code = $this->request['currency_code'];
        $rate = $this->request['currency_rate'];

        $precision = config('money.' . $code . '.precision');

        $amount = $this->request['amount'] = round($this->request['amount'], $precision);

        if ($this->model->currency_code != $code) {
            $converted_amount = $this->convertBetween($amount, $code, $rate, $this->model->currency_code, $this->model->currency_rate);

            $amount = round($converted_amount, $precision);
        }

        $this->model->paid_amount = $this->model->paid;
        event(new PaidAmountCalculated($this->model));

        $total_amount = round($this->model->amount - $this->model->paid_amount, $precision);

        unset($this->model->reconciled);
        unset($this->model->paid_amount);

        $compare = bccomp($amount, $total_amount, $precision);

        if ($compare === 1) {
            $error_amount = $total_amount;

            if ($this->model->currency_code != $code) {
                $converted_amount = $this->convertBetween($total_amount, $this->model->currency_code, $this->model->currency_rate, $code, $rate);

                $error_amount = round($converted_amount, $precision);
            }

            $message = trans('messages.error.over_payment', ['amount' => money($error_amount, $code, true)]);

            throw new \Exception($message);
        } else {
            $this->model->status = ($compare === 0) ? 'paid' : 'partial';
        }

        return true;
    }

    protected function createHistory()
    {
        $history_desc = money((double) $this->transaction->amount, (string) $this->transaction->currency_code, true)->format() . ' ' . trans_choice('general.payments', 1);

        $this->dispatch(new CreateDocumentHistory($this->model, 0, $history_desc));
    }
}
