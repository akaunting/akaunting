<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\CompanyDeleted;
use App\Events\Common\CompanyDeleting;
use App\Interfaces\Job\ShouldDelete;
use App\Traits\Users;

class DeleteCompany extends Job implements ShouldDelete
{
    use Users;

    protected $current_company_id;

    public function booted(...$arguments): void
    {
        $this->current_company_id = company_id();
    }

    public function handle(): bool
    {
        $this->authorize();

        $this->model->makeCurrent();

        $this->model->relationships_to_delete = $this->getRelationshipsToDelete();

        event(new CompanyDeleting($this->model, $this->current_company_id));

        \DB::transaction(function () {
            $this->deleteRelationships($this->model, $this->model->relationships_to_delete);

            $this->model->delete();
        });

        event(new CompanyDeleted($this->model, $this->current_company_id));

        company($this->current_company_id)->makeCurrent();

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't delete active company
        if ($this->model->id == $this->current_company_id) {
            $message = trans('companies.error.delete_active');

            throw new \Exception($message);
        }

        // Check if user can access company
        if ($this->isNotUserCompany($this->model->id)) {
            $message = trans('companies.error.not_user_company');

            throw new \Exception($message);
        }
    }

    public function getRelationshipsToDelete(): array
    {
        return [
            'accounts',
            'categories',
            'contact_persons',
            'contacts',
            'currencies',
            'dashboards',
            'document_histories',
            'document_item_taxes',
            'document_items',
            'document_totals',
            'documents',
            'email_templates',
            'item_taxes',
            'items',
            'media',
            'module_histories',
            'modules',
            'reconciliations',
            'recurring',
            'reports',
            'settings',
            'taxes',
            'transaction_taxes',
            'transactions',
            'transfers',
            'widgets',
        ];
    }
}
