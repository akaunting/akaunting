<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Jobs\Purchase\CreateBillHistory;
use App\Jobs\Sale\CreateInvoiceHistory;
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

        $invoice->status = ($invoice->transactions->count() > 1) ? 'partial' : 'sent';

        $invoice->save();

        $this->dispatch(new CreateInvoiceHistory($invoice, 0, $this->getDescription($transaction)));
    }

    protected function updateBill($transaction)
    {
        $bill = $transaction->bill;

        $bill->status = ($bill->transactions->count() > 1) ? 'partial' : 'received';

        $bill->save();

        $this->dispatch(new CreateBillHistory($bill, 0, $this->getDescription($transaction)));
    }

    protected function getDescription($transaction)
    {
        $amount = money((double) $transaction->amount, (string) $transaction->currency_code, true)->format();

        return trans('messages.success.deleted', ['type' => $amount . ' ' . trans_choice('general.payments', 1)]);
    }
}
