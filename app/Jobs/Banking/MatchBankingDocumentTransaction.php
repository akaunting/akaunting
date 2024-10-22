<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Document\PaidAmountCalculated;
use App\Jobs\Document\CreateDocumentHistory;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Traits\Currencies;

class MatchBankingDocumentTransaction extends Job
{
    use Currencies;

    protected $transaction;

    public function __construct(Document $model, Transaction $transaction)
    {
        $this->model = $model;

        $this->transaction = $transaction;
    }

    public function handle(): Transaction
    {
        $this->checkAmount();

        \DB::transaction(function () {
            $this->transaction = $this->dispatch(new UpdateTransaction($this->transaction, [
                'document_id'   => $this->model->id,
                'type'          => $this->transaction->type, // Set missing type get default income type for UpdateTransaction job.
            ]));

            $this->model->save();

            $this->createHistory();
        });

        return $this->transaction;
    }

    protected function checkAmount(): bool
    {
        $code = $this->transaction->currency_code;
        $rate = $this->transaction->currency_rate;

        $precision = currency($code)->getPrecision();

        $amount = $this->transaction->amount = round($this->transaction->amount, $precision);

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

            $message = trans('messages.error.over_match', ['type' => ucfirst($this->model->type), 'amount' => money($error_amount, $code)]);

            throw new \Exception($message);
        } else {
            $this->model->status = ($compare === 0) ? 'paid' : 'partial';
        }

        return true;
    }

    protected function createHistory(): void
    {
        $history_desc = money((double) $this->transaction->amount, (string) $this->transaction->currency_code)->format() . ' ' . trans_choice('general.payments', 1);

        $this->dispatch(new CreateDocumentHistory($this->model, 0, $history_desc));
    }
}
