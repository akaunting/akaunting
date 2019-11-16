<?php

namespace App\Listeners\Income;

use App\Events\Income\InvoiceSent as Event;
use App\Models\Income\InvoiceHistory;

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
        if ($event->invoice->invoice_status_code != 'partial') {
            $event->invoice->invoice_status_code = 'sent';

            $event->invoice->save();
        }

        // Add invoice history
        InvoiceHistory::create([
            'company_id' => $event->invoice->company_id,
            'invoice_id' => $event->invoice->id,
            'status_code' => 'sent',
            'notify' => 0,
            'description' => trans('invoices.mark_sent'),
        ]);
    }
}
