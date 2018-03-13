<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use App\Models\Company\Company;
use App\Models\Income\InvoiceStatus;
use App\Models\Expense\BillStatus;
use Artisan;

class Version1115 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.1.15';

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

        // Create new bill statuses
        $companies = Company::all();

        foreach ($companies as $company) {
            $invoice = [
                'company_id' => $company->id,
                'name' => trans('invoices.status.delete'),
                'code' => 'delete',
            ];

            InvoiceStatus::create($invoice);

            $bill = [
                'company_id' => $company->id,
                'name' => trans('bills.status.delete'),
                'code' => 'delete',
            ];

            BillStatus::create($bill);
        }
    }
}
