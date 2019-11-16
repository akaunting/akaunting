<?php

namespace App\Observers;

use App\Models\Banking\Transaction as Model;
use App\Models\Income\InvoiceHistory;
use App\Models\Expense\BillHistory;

class Transaction
{
    /**
     * Listen to the deleted event.
     *
     * @param  Model  $transaction
     * @return void
     */
    public function deleted(Model $transaction)
    {
        if (!empty($transaction->document_id)) {
            if ($transaction->type == 'income') {
                $this->updateInvoice($transaction);
            } else {
                $this->updateBill($transaction);
            }
        }
    }

    protected function updateInvoice($transaction)
    {
        $invoice = $transaction->invoice;

        if ($invoice->payments->count() > 1) {
            $invoice->invoice_status_code = 'partial';
        } else {
            $invoice->invoice_status_code = 'sent';
        }

        $invoice->save();

        $desc_amount = money((float) $transaction->amount, (string) $transaction->currency_code, true)->format();

        $description = $desc_amount . ' ' . trans_choice('general.payments', 1);

        // Add invoice history
        InvoiceHistory::create([
            'company_id' => $invoice->company_id,
            'invoice_id' => $invoice->id,
            'status_code' => $invoice->invoice_status_code,
            'notify' => 0,
            'description' => trans('messages.success.deleted', ['type' => $description]),
        ]);
    }

    protected function updateBill($transaction)
    {
        $bill = $transaction->bill;

        if ($bill->payments->count() > 1) {
            $bill->bill_status_code = 'partial';
        } else {
            $bill->bill_status_code = 'received';
        }

        $bill->save();

        $desc_amount = money((float) $transaction->amount, (string) $transaction->currency_code, true)->format();

        $description = $desc_amount . ' ' . trans_choice('general.payments', 1);

        // Add bill history
        BillHistory::create([
            'company_id' => $bill->company_id,
            'bill_id' => $bill->id,
            'status_code' => $bill->bill_status_code,
            'notify' => 0,
            'description' => trans('messages.success.deleted', ['type' => $description]),
        ]);
    }
}