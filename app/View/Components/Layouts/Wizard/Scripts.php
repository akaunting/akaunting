<?php

namespace App\View\Components\Layouts\Wizard;

use Akaunting\Money\Currency as MoneyCurrency;
use App\Abstracts\View\Component;
use App\Models\Common\Media;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Modules;

class Scripts extends Component
{
    use Modules;

    public $company;

    public $translations;

    public $currencies;

    public $currency_codes;

    public $taxes;

    public $modules;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->company = $this->getCompany();

        $this->translations = $this->getTransalations();

        $this->currencies = $this->getCurrencies();

        // Prepare codes
        $this->currency_codes = $this->getCurrencyCodes();

        $this->taxes = $this->getTaxes();

        $this->modules = $this->getFeaturedModules([
            'query' => [
                'limit' => 5
            ]
        ]);

        return view('components.layouts.wizard.scripts');
    }

    protected function getCompany()
    {
        $company = company();

        $company->api_key = setting('apps.api_key');
        $company->financial_start = setting('localisation.financial_start');

        $logo_id = setting('company.logo');

        $logo = false;

        if ($logo_id) {
            $logo = Media::find($logo_id);

            if ($logo) {
                $logo->path = route('uploads.get', $logo->id);
            }
        }

        $company->logo = $logo;

        return $company;
    }

    /* Wizard page transactions */
    protected function getTransalations()
    {
        return [
            'company' => [
                'title' => trans_choice('general.companies', 1),
                'api_key' => trans('modules.api_key'),
                'form_enter' => trans('general.form.enter'),
                'get_api_key' => trans('modules.get_api_key'),
                'tax_number' => trans('general.tax_number'),
                'financial_start' => trans('settings.localisation.financial_start'),
                'address' => trans('settings.company.address'),
                'logo' => trans('settings.company.logo'),
                'skip' => trans('companies.skip_step'),
                'save' => trans('general.save'),
                'country' => trans_choice('general.countries', 1),
            ],

            'currencies' => [
                'title' => trans_choice('general.currencies', 2),
                'add_new' => trans('general.add_new'),
                'no_currency' => trans('currencies.no_currency'),
                'new_currency' => trans('currencies.new_currency'),
                'create_currency' => trans('currencies.create_currency'),
                'name' => trans('general.name'),
                'code' => trans('currencies.code'),
                'rate' => trans('currencies.rate'),
                'enabled' => trans('general.enabled'),
                'actions' =>  trans('general.actions') ,
                'yes' => trans('general.yes'),
                'no' => trans('general.no'),
                'edit' => trans('general.edit'),
                'delete' => trans('general.delete'),
                'save' => trans('general.save'),
                'precision' => trans('currencies.precision'),
                'symbol' => trans('currencies.symbol.symbol'),
                'position' => trans('currencies.symbol.position'),
                'decimal_mark' => trans('currencies.decimal_mark'),
                'thousands_separator' => trans('currencies.thousands_separator'),
                'previous' => trans('pagination.previous'),
                'next' => trans('pagination.next'),
                'delete_confirm' => trans('general.delete_confirm'),
                'cancel' => trans('general.cancel'),
            ],

            'taxes' => [
                'title' => trans_choice('general.taxes', 2),
                'add_new' =>  trans('general.add_new'),
                'no_taxes' => trans('taxes.no_taxes'),
                'create_task' => trans('taxes.create_task'),
                'new_tax' => trans('taxes.new_tax'),
                'name' => trans('general.name'),
                'rate_percent' => trans('taxes.rate_percent'),
                'enabled' => trans('general.enabled'),
                'actions' => trans('general.actions'),
                'yes' => trans('general.yes'),
                'no' => trans('general.no'),
                'edit' => trans('general.edit'),
                'delete' => trans('general.delete'),
                'name' => trans('general.name'),
                'rate' => trans('currencies.rate'),
                'enabled' => trans('general.enabled'),
                'save' => trans('general.save'),
                'previous' => trans('pagination.previous'),
                'next' => trans('pagination.next'),
                'cancel' => trans('general.cancel'),
            ],

            'finish' => [
                'title' => trans('modules.ready'),
                'recommended_apps' => trans('modules.recommended_apps'),
                'no_apps' =>  trans('modules.no_apps'),
                'developer' => trans('modules.developer'),
                'from' => trans('general.from'),
                'apps_managing' =>  trans('modules.apps_managing'),
                'reviews' => trans('modules.tab.reviews'),
                'previous' => trans('companies.skip_step'),
                'go_to_dashboard' => trans('general.go_to_dashboard'),
                'error_message' => trans('errors.title.500'),
            ]
        ];
    }

    protected function getCurrencies()
    {
        return Currency::all();
    }

    protected function getCurrencyCodes()
    {
        $codes = [];
        $money_currencies = MoneyCurrency::getCurrencies();

        foreach ($money_currencies as $key => $item) {
            $codes[$key] = $key;
        }

        return $codes;
    }

    protected function getTaxes()
    {
        return Tax::all();
    }
}
