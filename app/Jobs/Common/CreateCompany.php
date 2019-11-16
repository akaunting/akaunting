<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Company;
use App\Traits\Users;
use Artisan;

class CreateCompany extends Job
{
    use Users;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Company
     */
    public function handle()
    {
        // Clear settings
        setting()->forgetAll();

        $company = Company::create($this->request->all());

        Artisan::call('user:seed', [
            'user' => user()->id,
            'company' => $company->id,
        ]);

        setting()->setExtraColumns(['company_id' => $company->id]);

        if ($this->request->file('logo')) {
            $company_logo = $this->getMedia($this->request->file('logo'), 'settings', $company->id);

            if ($company_logo) {
                $company->attachMedia($company_logo, 'company_logo');

                setting()->set('company.logo', $company_logo->id);
            }
        }

        // Create settings
        setting()->set([
            'company.name' => $this->request->get('name'),
            'company.email' => $this->request->get('email'),
            'company.address' => $this->request->get('address'),
            'default.currency' => $this->request->get('currency'),
            'default.locale' => $this->request->get('locale', 'en-GB'),
        ]);

        setting()->save();
        setting()->forgetAll();

        return $company;
    }
}
