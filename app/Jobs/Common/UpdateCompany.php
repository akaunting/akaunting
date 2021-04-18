<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\CompanyUpdated;
use App\Events\Common\CompanyUpdating;
use App\Models\Common\Company;
use App\Traits\Users;

class UpdateCompany extends Job
{
    use Users;

    protected $company;

    protected $request;

    protected $current_company_id;

    /**
     * Create a new job instance.
     *
     * @param  $company
     * @param  $request
     */
    public function __construct($company, $request)
    {
        $this->company = $company;
        $this->request = $this->getRequestInstance($request);
        $this->current_company_id = company_id();
    }

    /**
     * Execute the job.
     *
     * @return Company
     */
    public function handle()
    {
        $this->authorize();

        event(new CompanyUpdating($this->company, $this->request));

        \DB::transaction(function () {
            $this->company->update($this->request->all());

            $this->company->makeCurrent();

            if ($this->request->has('name')) {
                setting()->set('company.name', $this->request->get('name'));
            }

            if ($this->request->has('email')) {
                setting()->set('company.email', $this->request->get('email'));
            }

            if ($this->request->has('tax_number')) {
                setting()->set('company.tax_number', $this->request->get('tax_number'));
            }

            if ($this->request->has('phone')) {
                setting()->set('company.phone', $this->request->get('phone'));
            }

            if ($this->request->has('address')) {
                setting()->set('company.address', $this->request->get('address'));
            }

            if ($this->request->has('currency')) {
                setting()->set('default.currency', $this->request->get('currency'));
            }

            if ($this->request->has('locale')) {
                setting()->set('default.locale', $this->request->get('locale'));
            }

            if ($this->request->file('logo')) {
                $company_logo = $this->getMedia($this->request->file('logo'), 'settings', $this->company->id);

                if ($company_logo) {
                    $this->company->attachMedia($company_logo, 'company_logo');

                    setting()->set('company.logo', $company_logo->id);
                }
            }

            setting()->save();
        });

        event(new CompanyUpdated($this->company, $this->request));

        if (!empty($this->current_company_id)) {
            company($this->current_company_id)->makeCurrent();
        }

        return $this->company;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        // Can't disable active company
        if (($this->request->get('enabled', 1) == 0) && ($this->company->id == $this->current_company_id)) {
            $message = trans('companies.error.disable_active');

            throw new \Exception($message);
        }

        // Check if user can access company
        if ($this->isNotUserCompany($this->company->id)) {
            $message = trans('companies.error.not_user_company');

            throw new \Exception($message);
        }
    }
}
