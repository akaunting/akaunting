<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\CompanyCreated;
use App\Events\Common\CompanyCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Company;
use Illuminate\Support\Facades\Artisan;

class CreateCompany extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Company
    {
        $current_company_id = company_id();

        event(new CompanyCreating($this->request));

        \DB::transaction(function () {
            $this->model = Company::create($this->request->all());

            $this->model->makeCurrent();

            $this->callSeeds();

            $this->updateSettings();
        });

        event(new CompanyCreated($this->model));

        if (!empty($current_company_id)) {
            company($current_company_id)->makeCurrent();
        }

        return $this->model;
    }

    protected function callSeeds(): void
    {
        // Set custom locale
        if ($this->request->has('locale')) {
            app()->setLocale($this->request->get('locale'));
        }

        // Company seeds
        Artisan::call('company:seed', [
            'company' => $this->model->id
        ]);

        if (!$user = user()) {
            return;
        }

        // Attach company to user logged in
        $user->companies()->attach($this->model->id);

        // User seeds
        Artisan::call('user:seed', [
            'user' => $user->id,
            'company' => $this->model->id,
        ]);
    }

    protected function updateSettings(): void
    {
        if ($this->request->file('logo')) {
            $company_logo = $this->getMedia($this->request->file('logo'), 'settings', $this->model->id);

            if ($company_logo) {
                $this->model->attachMedia($company_logo, 'company_logo');

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
            'company.city' => $this->request->get('city'),
            'company.zip_code' => $this->request->get('zip_code'),
            'company.state' => $this->request->get('state'),
            'company.country' => $this->request->get('country'),
            'default.currency' => $this->request->get('currency'),
            'default.locale' => $this->request->get('locale', 'en-GB'),
            'default.country' => $this->request->get('country'),
        ]);

        if (!empty($this->request->settings)) {
            foreach ($this->request->settings as $name => $value) {
                setting()->set([$name => $value]);
            }
        }

        setting()->save();
    }
}
