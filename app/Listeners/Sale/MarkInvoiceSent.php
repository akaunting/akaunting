<?php

namespace App\Listeners\Sale;

use App\Events\Sale\InvoiceSent as Event;
use App\Models\Sale\InvoiceHistory;

class MarkInvoiceSent
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Mark invoice as sent
        if ($event->invoice->status != 'partial') {
            $event->invoice->status = 'sent';

            $event->invoice->save();
        }

        // Add invoice history
        InvoiceHistory::create([
            'company_id' => $event->invoice->company_id,
            'invoice_id' => $event->invoice->id,
            'status' => 'sent',
            'notify' => 0,
            'description' => trans('invoices.mark_sent'),
        ]);
    }
}
