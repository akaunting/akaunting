<?php

namespace App\Http\ViewComposers;

use Akaunting\Money\Currency as MoneyCurrency;
use App\Models\Common\Media;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Modules;
use App\Models\Common\Company;
use Illuminate\View\View;

class Wizard
{
    use Modules;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $translations = $this->getTransalations();

        $currencies = $this->getCurrencies();

        // Prepare codes
        $codes = $this->getCurrencyCodes();

        $taxes = $this->getTaxes();

        $modules = $this->getFeaturedModules([
            'query' => [
                'limit' => 4
            ]
        ]);

        $company = $this->getCompany();

        $view->with([
            'translations' => $translations,
            'company' => $company,
            'currencies' => $currencies,
            'currency_codes' => $codes,
            'taxes' => $taxes,
            'modules' => $modules,
        ]);
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
                'skip' => trans('general.skip'),
                'save' => trans('general.save'),
            ],

            'currencies' => [
                'title' => trans_choice('general.currencies', 2),
                'add_new' => trans('general.add_new'),
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
                'title' => trans_choice('general.finish', 1),
                'recommended_apps' => trans('modules.recommended_apps'),
                'no_apps' =>  trans('modules.no_apps'),
                'developer' => trans('modules.developer'),
                'previous' => trans('pagination.previous'),
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
}
