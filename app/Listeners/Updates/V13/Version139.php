<?php

namespace App\Listeners\Updates\V13;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use App\Models\Income\InvoiceItem;
use App\Models\Income\InvoiceItemTax;
use App\Models\Setting\Tax;
use Artisan;

class Version139 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.9';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        $this->copyOldInvoiceItemTaxes();

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }

    protected function copyOldInvoiceItemTaxes()
    {
        $company_id = session('company_id');

        $invoice_items = InvoiceItem::where('company_id', '<>', '0')->where('tax_id', '<>', '0')->get();

        foreach ($invoice_items as $invoice_item) {
            session(['company_id' => $invoice_item->company_id]);

            $tax = Tax::where('id', $invoice_item->tax_id)->first();

            if (empty($tax)) {
                continue;
            }

            InvoiceItemTax::create([
                'company_id' => $invoice_item->company_id,
                'invoice_id' => $invoice_item->invoice_id,
                'invoice_item_id' => $invoice_item->id,
                'tax_id' => $invoice_item->tax_id,
                'name' => $tax->name,
                'amount' => $invoice_item->tax,
            ]);
        }

        session(['company_id' => $company_id]);
    }
}
