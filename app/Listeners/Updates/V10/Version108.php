<?php

namespace App\Listeners\Updates\V10;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use App\Models\Common\Company;
use App\Models\Expense\Bill;
use App\Models\Expense\BillStatus;

class Version108 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.0.8';

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

        $this->updateSettings();
        $this->updateBills();
    }

    private function updateSettings()
    {
        // Set new invoice settings
        setting(['general.invoice_number_prefix' => setting('general.invoice_prefix', 'INV-')]);
        setting(['general.invoice_number_digit' => setting('general.invoice_digit', '5')]);
        setting(['general.invoice_number_next' => setting('general.invoice_start', '1')]);

        setting()->forget('general.invoice_prefix');
        setting()->forget('general.invoice_digit');
        setting()->forget('general.invoice_start');

        setting()->save();
    }

    private function updateBills()
    {
        // Create new bill statuses
        $companies = Company::all();

        foreach ($companies as $company) {
            $rows = [
                [
                    'company_id' => $company->id,
                    'name' => trans('bills.status.draft'),
                    'code' => 'draft',
                ],
                [
                    'company_id' => $company->id,
                    'name' => trans('bills.status.received'),
                    'code' => 'received',
                ],
            ];

            foreach ($rows as $row) {
                BillStatus::create($row);
            }
        }

        $bills = Bill::all();

        foreach ($bills as $bill) {
            if (($bill->bill_status_code != 'new') || ($bill->bill_status_code != 'updated')) {
                continue;
            }

            $bill->bill_status_code = 'draft';
            $bill->save();
        }

        $new = BillStatus::where('code', 'new');
        $new->delete();
        $new->forceDelete();

        $updated = BillStatus::where('code', 'updated');
        $updated->delete();
        $updated->forceDelete();
    }
}
