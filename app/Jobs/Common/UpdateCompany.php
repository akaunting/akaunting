<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Company;
use App\Traits\Users;

class UpdateCompany extends Job
{
    use Users;

    protected $company;

    protected $request;

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
    }

    /**
     * Execute the job.
     *
     * @return Company
     */
    public function handle()
    {
        // Check if user can access company
        $this->authorize();

        // Update company
        $this->company->update($this->request->all());

        // Clear current settings
        setting()->setExtraColumns(['company_id' => $this->company->id]);
        setting()->forgetAll();

        // Load settings based on the given company
        setting()->load(true);

        if ($this->request->has('name')) {
            setting()->set('company.name', $this->request->get('name'));
        }

        if ($this->request->has('email')) {
            setting()->set('company.email', $this->request->get('email'));
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
        setting()->forgetAll();

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
        if (($this->request->get('enabled', 1) == 0) && ($this->company->id == session('company_id'))) {
            $message = trans('companies.error.disable_active');

            throw new \Exception($message);
        }

        // Check if user can access company
        if (!$this->isUserCompany($this->company->id)) {
            $message = trans('companies.error.not_user_company');

            throw new \Exception($message);
        }
    }
}
