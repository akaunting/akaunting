<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Events\Document\TransactionsCounted;
use App\Jobs\Document\CreateDocumentHistory;
use App\Models\Banking\Transaction as Model;
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
        if (empty($transaction->document_id)) {
            return;
        }

        $function = ($transaction->type == 'income') ? 'updateInvoice' : 'updateBill';

        $this->$function($transaction);
    }

    protected function updateInvoice($transaction)
    {
        $invoice = $transaction->invoice;

        $invoice->transactions_count = $invoice->transactions->count();
        event(new TransactionsCounted($invoice));

        $invoice->status = ($invoice->transactions_count > 0) ? 'partial' : 'sent';

        unset($invoice->transactions_count);

        $invoice->save();

        $this->dispatch(new CreateDocumentHistory($invoice, 0, $this->getDescription($transaction)));
    }

    protected function updateBill($transaction)
    {
        $bill = $transaction->bill;

        $bill->transactions_count = $bill->transactions->count();
        event(new TransactionsCounted($bill));

        $bill->status = ($bill->transactions_count > 0) ? 'partial' : 'received';

        unset($bill->transactions_count);

        $bill->save();

        $this->dispatch(new CreateDocumentHistory($bill, 0, $this->getDescription($transaction)));
    }

    protected function getDescription($transaction)
    {
        $amount = money((double) $transaction->amount, (string) $transaction->currency_code, true)->format();

        return trans('messages.success.deleted', ['type' => $amount . ' ' . trans_choice('general.payments', 1)]);
    }
}
