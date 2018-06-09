<?php

namespace App\Observers;

use App\Models\Common\Company as Model;
use Artisan;
use Auth;

class Company
{
    /**
     * Listen to the created event.
     *
     * @param  Model  $company
     * @return void
     */
    public function created(Model $company)
    {
        // Create seeds
        Artisan::call('company:seed', [
            'company' => $company->id
        ]);

        // Check if user is logged in
        if (!Auth::check()) {
            return;
        }

        // Attach company to user
        Auth::user()->companies()->attach($company->id);
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model  $company
     * @return void
     */
    public function deleted(Model $company)
    {
        $tables = [
            'accounts', 'bill_histories', 'bill_items', 'bill_payments', 'bill_statuses', 'bills', 'categories',
            'currencies', 'customers', 'invoice_histories', 'invoice_items', 'invoice_payments', 'invoice_statuses',
            'invoices', 'items', 'payments', 'recurring', 'revenues', 'settings', 'taxes', 'transfers', 'vendors',
        ];

        foreach ($tables as $table) {
            $this->deleteItems($company, $table);
        }
    }

    /**
     * Delete items in batch.
     *
     * @param  Model  $company
     * @param  $table
     * @return void
     */
    protected function deleteItems($company, $table)
    {
        foreach ($company->$table as $item) {
            $item->delete();
        }
    }
}