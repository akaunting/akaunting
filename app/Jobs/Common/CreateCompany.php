<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\CompanyCreated;
use App\Events\Common\CompanyCreating;
use App\Models\Common\Company;
use Artisan;

class CreateCompany extends Job
{
    protected $company;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Company
     */
    public function handle()
    {
        $current_company_id = company_id();

        event(new CompanyCreating($this->request));

        \DB::transaction(function () {
            $this->company = Company::create($this->request->all());

            $this->company->makeCurrent();

            $this->callSeeds();

            $this->updateSettings();
        });

        event(new CompanyCreated($this->company));

        if (!empty($current_company_id)) {
            company($current_company_id)->makeCurrent();
        }

        return $this->company;
    }

    protected function callSeeds()
    {
        // Set custom locale
        if ($this->request->has('locale')) {
            app()->setLocale($this->request->get('locale'));
        }

        // Company seeds
        Artisan::call('company:seed', [
            'company' => $this->company->id
        ]);

        if (!$user = user()) {
            return;
        }

        // Attach company to user logged in
        $user->companies()->attach($this->company->id);

        // User seeds
        Artisan::call('user:seed', [
            'user' => $user->id,
            'company' => $this->company->id,
        ]);
    }

    protected function updateSettings()
    {
        if ($this->request->file('logo')) {
            $company_logo = $this->getMedia($this->request->file('logo'), 'settings', $this->company->id);

            if ($company_logo) {
                $this->company->attachMedia($company_logo, 'company_logo');

                setting()->set('company.logo', $company_logo->id);
            }
        }

        // Create settings
        setting()->set([
            'company.name' => $this->request->get('name'),
            'company.email' => $this->request->get('email'),
            'company.tax_number' => $this->request->get('tax_number'),
            'company.phone' => $this->request->get('phone'),
            'company.address' => $this->request->get('address'),
            'default.currency' => $this->request->get('currency'),
            'default.locale' => $this->request->get('locale', 'en-GB'),
        ]);

        if (!empty($this->request->settings)) {
            foreach ($this->request->settings as $name => $value) {
                setting()->set([$name => $value]);
            }
        }

        setting()->save();
    }
}
