<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\CompanyDeleted;
use App\Events\Common\CompanyDeleting;
use App\Traits\Users;

class DeleteCompany extends Job
{
    use Users;

    protected $company;

    protected $current_company_id;

    /**
     * Create a new job instance.
     *
     * @param  $company
     */
    public function __construct($company)
    {
        $this->company = $company;
        $this->current_company_id = company_id();
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->company->makeCurrent();

        event(new CompanyDeleting($this->company, $this->current_company_id));

        \DB::transaction(function () {
            $this->deleteRelationships($this->company, [
                'accounts', 'document_histories', 'document_item_taxes', 'document_items', 'document_totals', 'documents', 'categories',
                'contacts', 'currencies', 'dashboards', 'email_templates', 'items', 'module_histories', 'modules', 'reconciliations',
                'recurring', 'reports', 'settings', 'taxes', 'transactions', 'transfers', 'widgets',
            ]);

            $this->company->users()->detach();

            $this->company->delete();
        });

        event(new CompanyDeleted($this->company, $this->current_company_id));

        company($this->current_company_id)->makeCurrent();

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
        if ($this->company->id == $this->current_company_id) {
            $message = trans('companies.error.delete_active');

            throw new \Exception($message);
        }

        // Check if user can access company
        if ($this->isNotUserCompany($this->company->id)) {
            $message = trans('companies.error.not_user_company');

            throw new \Exception($message);
        }
    }
}
