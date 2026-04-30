<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionSplitted;
use App\Events\Banking\TransactionSplitting;
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

        $this->authorize();

        event(new TransactionSplitting($this->request, $this->model));

        DB::transaction(function () {
            foreach ($this->request->items as $item) {
                $transaction            = $this->model->duplicate();
                $transaction->split_id  = $this->model->id;
                $transaction->amount    = $item['amount'];
                $transaction->save();

                $document = Document::find($item['document_id']);

                $this->dispatch(new MatchBankingDocumentTransaction($document, $transaction));
            }

            $this->model->type = config('type.transaction.' . $this->model->type . '.split_type');
            $this->model->save();
        });

        event(new TransactionSplitted($this->request, $this->model));

        return $this->request->items;
    }

    public function authorize(): void
    {
        foreach ($this->request->items as $item) {
            if (empty($item['document_id'])) {
                throw new \Exception(trans('messages.error.not_found', ['type' => trans_choice('general.documents', 1)]));
            }

            if (! Document::find($item['document_id'])) {
                throw new \Exception(trans('messages.error.not_found', ['type' => trans_choice('general.documents', 1)]));
            }
        }
    }

    protected function checkAmount(): bool
    {
        $total_amount = 0;

        foreach ($this->request->items as $item) {
            $total_amount += $item['amount'];
        }

        $precision = currency($this->model->currency_code)->getPrecision();

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
