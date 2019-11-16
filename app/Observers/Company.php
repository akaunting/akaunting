<?php

namespace App\Observers;

use App\Models\Common\Company as Model;
use Artisan;

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
        if (!auth()->check()) {
            return;
        }

        // Attach company to user
        user()->companies()->attach($company->id);
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
            'accounts', 'bill_histories', 'bill_items', 'bill_statuses', 'bills', 'categories', 'contacts',
            'currencies', 'invoice_histories', 'invoice_items', 'invoice_statuses', 'invoices', 'items', 'recurring',
            'settings', 'taxes', 'transactions', 'transfers',
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
