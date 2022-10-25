<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\CompanyUpdated;
use App\Events\Common\CompanyUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Common\Company;
use App\Models\Setting\Currency;
use App\Traits\Users;
use Akaunting\Money\Currency as MoneyCurrency;
use OutOfBoundsException;

class UpdateCompany extends Job implements ShouldUpdate
{
    use Users;

    protected $current_company_id;

    public function booted(...$arguments): void
    {
        $this->current_company_id = company_id();
    }

    public function handle(): Company
    {
        $this->authorize();

        event(new CompanyUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $this->model->update($this->request->all());

            $this->model->makeCurrent();

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

            if ($this->request->has('city')) {
                setting()->set('company.city', $this->request->get('city'));
            }

            if ($this->request->has('zip_code')) {
                setting()->set('company.zip_code', $this->request->get('zip_code'));
            }

            if ($this->request->has('state')) {
                setting()->set('company.state', $this->request->get('state'));
            }

            if ($this->request->has('country')) {
                setting()->set('company.country', $this->request->get('country'));
            }

            if ($this->request->has('currency')) {
                setting()->set('default.currency', $this->request->get('currency'));
            }

            if ($this->request->has('locale')) {
                setting()->set('default.locale', $this->request->get('locale'));
            }

            if ($this->request->file('logo')) {
                $company_logo = $this->getMedia($this->request->file('logo'), 'settings', $this->model->id);

                if ($company_logo) {
                    $this->model->attachMedia($company_logo, 'company_logo');

                    setting()->set('company.logo', $company_logo->id);
                }
            }

            setting()->save();

            $this->updateCurrency();
        });

        event(new CompanyUpdated($this->model, $this->request));

        if (!empty($this->current_company_id)) {
            company($this->current_company_id)->makeCurrent();
        }

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't disable active company
        if (($this->request->get('enabled', 1) == 0) && ($this->model->id == $this->current_company_id)) {
            $message = trans('companies.error.disable_active');

            throw new \Exception($message);
        }

        // Check if user can access company
        if ($this->isNotUserCompany($this->model->id)) {
            $message = trans('companies.error.not_user_company');

            throw new \Exception($message);
        }
    }

    protected function updateCurrency()
    {
        $currency_code = $this->request->get('currency');

        if (empty($currency_code)) {
            return;
        }

        $currency = Currency::where('company_id', $this->model->id)
                            ->where('code', $currency_code)
                            ->first();

        if ($currency) {
            $currency->rate = '1';
            $currency->enabled = '1';

            $currency->save();
        } else {
            try {
                $data = (new MoneyCurrency($currency_code))->toArray()[$currency_code];
                $data['rate'] = '1';
                $data['enabled'] = '1';
                $data['company_id'] = $this->model->id;
                $data['code'] = $currency_code;
                $data['created_from'] = 'core::ui';
                $data['created_by'] = user_id();

                $currency = Currency::create($data);
            } catch (OutOfBoundsException $e) {
            }
        }
    }
}
