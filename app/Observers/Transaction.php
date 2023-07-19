<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Events\Document\TransactionsCounted;
use App\Jobs\Banking\UpdateTransaction;
use App\Jobs\Document\CreateDocumentHistory;
use App\Models\Banking\Transaction as Model;
use App\Models\Document\Document;
use App\Traits\Jobs;

class Transaction extends Observer
{
    use Jobs;

    /**
     * Listen to the deleted event.
     *
     * @param  Model  $transaction
     * @return void
     */
    public function deleted(Model $transaction)
    {
        if (! empty($transaction->document_id)) {
            $type = ($transaction->type == 'income') ? Document::INVOICE_TYPE : Document::BILL_TYPE;

            $this->updateDocument($transaction, $type);
        }

        if (! empty($transaction->split_id)) {
            $this->updateTransaction($transaction);
        }
    }

    protected function updateDocument($transaction, $type)
    {
        $document = $transaction->{$type};

        if (empty($document)) {
            return;
        }

        $document->transactions_count = $document->transactions->count();

        event(new TransactionsCounted($document));

        $document->status = ($type == Document::INVOICE_TYPE) ? 'sent' : 'received';

        if ($document->transactions_count > 0) {
            $document->status = 'partial';
        }

        unset($document->transactions_count);

        $document->save();

        $this->dispatch(new CreateDocumentHistory($document, 0, $this->getDescription($transaction)));
    }

    protected function getDescription($transaction)
    {
        $amount = money((double) $transaction->amount, (string) $transaction->currency_code)->format();

        return trans('messages.success.deleted', ['type' => $amount . ' ' . trans_choice('general.payments', 1)]);
    }

    protected function updateTransaction($transaction)
    {
        $splitted_transaction = Model::find($transaction->split_id);

        if ($splitted_transaction->splits->count() == 0) {
            $values = [
                'type' => $transaction->type,
            ];

            $this->dispatch(new UpdateTransaction($splitted_transaction, $values));
        }
    }
}
