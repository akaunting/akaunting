<?php

namespace Akaunting\DebugbarCollector;

use App\Models\Banking\Account;
use App\Models\Module\Module;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Traits\Modules;
use App\Utilities\Date;
use App\Utilities\Info;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\Renderable;
use Illuminate\Support\Facades\Cache;

class AkauntingCollector extends DataCollector implements DataCollectorInterface, Renderable
{
    use Modules;

    /**
     * Called by the DebugBar when data needs to be collected
     *
     * @return array Collected data
     */
    public function collect()
    {
        $modules = $default_account = $default_currency = [];
        $default_income_category = $default_expense_category = [];

        $info = Info::all();
        $akaunting_version = ! empty($info['akaunting']) ? $info['akaunting'] : '0.0.0';
        $total_companies = ! empty($info['companies']) ? $info['companies'] : '0';
        $total_users = ! empty($info['users']) ? $info['users'] : '0';
        $company_id = company_id();
        $company_date_format = company_date_format() . ' (' . company_date(Date::createFromTimestamp(0)) . ')';

        if ($account_id = setting('default.account', null)) {
            $default_account = Account::find($account_id)?->getAttributes();
        }

        if ($currency_code = setting('default.currency')) {
            $default_currency = Currency::code($currency_code)->first()?->getAttributes();
        }

        if ($income_category_id = setting('default.income_category', null)) {
            $default_income_category = Category::find($income_category_id)?->getAttributes();
        }

        if ($expense_category_id = setting('default.expense_category', null)) {
            $default_expense_category = Category::find($expense_category_id)?->getAttributes();
        }

        $default_locale = setting('default.locale');
        $default_payment_method = setting('default.payment_method');

        Module::all(['alias', 'enabled'])->each(function ($module) use (&$modules) {
            $versions = Cache::get('versions');

            $exists = $this->moduleExists($module->alias);

            $modules[$module->alias] = [
                'Installed Version' => $exists ? module($module->alias)->get('version') : 'N/A',
                'Latest Version' => isset($versions[$module->alias]) ? $versions[$module->alias] : 'N/A',
                'Enabled' => $module->enabled,
                'Exists' => $exists,
            ];
        });

        return [
            'Akaunting Version' => $akaunting_version,
            'Total Companies' => $total_companies,
            'Total Users' => $total_users,
            'Company ID' => $company_id,
            'Company Date Format' => $company_date_format,
            'Default Account' => DataCollector::getDefaultVarDumper()->renderVar($default_account),
            'Default Currency' => DataCollector::getDefaultVarDumper()->renderVar($default_currency),
            'Default Income Category' => DataCollector::getDefaultVarDumper()->renderVar($default_income_category),
            'Default Expense Category' => DataCollector::getDefaultVarDumper()->renderVar($default_expense_category),
            'Default Language' => $default_locale,
            'Default Payment Method' => $default_payment_method,
            'Modules' => DataCollector::getDefaultVarDumper()->renderVar($modules),
        ];
    }

    /**
     * Returns the unique name of the collector
     *
     * @return string
     */
    public function getName()
    {
        return 'akaunting';
    }

    public function getWidgets()
    {
        return [
            "akaunting" => [
                "title" => "Akaunting",
                "icon" => "archive",
                "widget" => "PhpDebugBar.Widgets.HtmlVariableListWidget",
                "map" => "akaunting",
                "default" => "{}",
            ],
        ];
    }
}
