<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Document\Document;
use App\Traits\Transactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SplitTransaction extends Job implements ShouldUpdate
{
    use Transactions;

    public function handle(): array
    {
        $this->checkAmount();

        DB::transaction(function () {
            foreach ($this->request->items as $item) {
                $transaction            = $this->model->duplicate();
                $transaction->number    = $this->getNextTransactionNumber();
                $transaction->split_id  = $this->model->id;
                $transaction->amount    = $item['amount'];
                $transaction->save();

                $item['split'] = $transaction;

                // Match only if document_id is given
                if (empty($item['document_id'])) {
                    return;
                }

                $document = Document::find($item['document_id']);

                if (empty($document)) {
                    return;
                }

                $this->dispatch(new MatchBankingDocumentTransaction($document, $transaction));
            }

            $this->model->type = config('type.transaction.' . $this->model->type . '.split_type');
            $this->model->save();
        });

        return $this->request->items;
    }

    protected function checkAmount(): bool
    {
        $total_amount = 0;

        foreach ($this->request->items as $item) {
            $total_amount += $item['amount'];
        }

        $precision = config('money.currencies.' . $this->model->currency_code . '.precision');

        $compare = bccomp($total_amount, $this->model->amount, $precision);

        if ($compare !== 0) {
            $error_amount = $this->model->amount;

            $message = trans('messages.error.same_amount', [
                'transaction' => ucfirst(trans_choice('general.' . Str::plural($this->model->type), 1)),
                'amount' => money($error_amount, $this->model->currency_code)
            ]);

            throw new \Exception($message);
        }

        return true;
    }
}
