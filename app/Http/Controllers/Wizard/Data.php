<?php

namespace App\Http\Controllers\Wizard;

use App\Abstracts\Http\Controller;

use Akaunting\Money\Currency as MoneyCurrency;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Modules;
use App\Models\Common\Company;

class Data extends Controller
{
    use Modules;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-common-companies')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-common-companies')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-common-companies')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-common-companies')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        $translations = [
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

        $currencies = Currency::collect();

        // Prepare codes
        $codes = [];
        $money_currencies = MoneyCurrency::getCurrencies();

        foreach ($money_currencies as $key => $item) {
            $codes[$key] = $key;
        }

        $taxes = Tax::collect();

        $modules = $this->getFeaturedModules([
            'query' => [
                'limit' => 4
            ]
        ]);

        $company = company();

        $company->api_key = setting('apps.api_key');
        $company->financial_start = setting('localisation.financial_start');

        if ($company->company_logo) {
            $logo = $company->logo;

            $logo->path = route('uploads.get', $logo->id);

            $company->logo = $logo;
        }

        return response()->json([
            'success' => true,
            'errors' => false,
            'message' => 'Get all data...',
            'data' => [
                'translations' => $translations,
                'company' => $company,
                'currencies' => $currencies,
                'currency_codes' => $codes,
                'taxes' => $taxes,
                'modules' => $modules,
            ],
        ]);
    }
}
