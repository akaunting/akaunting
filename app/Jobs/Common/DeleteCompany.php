<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Company;
use App\Traits\Users;

class DeleteCompany extends Job
{
    use Users;

    protected $company;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->deleteRelationships($this->company, [
            'accounts', 'bills', 'bill_histories', 'bill_items', 'bill_item_taxes', 'bill_totals', 'categories',
            'contacts', 'currencies', 'dashboards', 'email_templates', 'invoices', 'invoice_histories', 'invoice_items',
            'invoice_item_taxes', 'invoice_totals', 'items', 'modules', 'module_histories', 'reconciliations',
            'recurring', 'reports', 'settings', 'taxes', 'transactions', 'transfers', 'widgets',
        ]);

        $this->company->delete();

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        // Can't delete active company
        if ($this->company->id == session('company_id')) {
            $message = trans('companies.error.delete_active');

            throw new \Exception($message);
        }

        // Check if user can access company
        if (!$this->isUserCompany($this->company->id)) {
            $message = trans('companies.error.not_user_company');

            throw new \Exception($message);
        }
    }
}
