<?php

namespace App\Utilities;

use Akaunting\Money\Money;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use Illuminate\Support\Str;

class Overrider
{
    public static $company_id;

    public static function load($type)
    {
        // Overrides apply per company
        $company_id = company_id();
        if (empty($company_id)) {
            return;
        }

        static::$company_id = $company_id;

        $method = 'load' . ucfirst($type);

        static::$method();
    }

    protected static function loadSettings()
    {
        // Timezone
        $timezone = setting('localisation.timezone');

        if (empty($timezone)) {
            $timezone = config('setting.fallback.localisation.timezone');
        }

        config(['app.timezone' => $timezone]);
        date_default_timezone_set(config('app.timezone'));

        // Email
        $email_protocol = setting('email.protocol', 'mail');
        config(['mail.default' => $email_protocol]);
        config(['mail.from.name' => setting('company.name')]);
        config(['mail.from.address' => setting('company.email')]);

        if ($email_protocol == 'sendmail') {
            config(['mail.mailers.sendmail.path' => setting('email.sendmail_path')]);
        } elseif ($email_protocol == 'smtp') {
            config(['mail.mailers.smtp.host' => setting('email.smtp_host')]);
            config(['mail.mailers.smtp.port' => setting('email.smtp_port')]);
            config(['mail.mailers.smtp.username' => setting('email.smtp_username')]);
            config(['mail.mailers.smtp.password' => setting('email.smtp_password')]);
            config(['mail.mailers.smtp.encryption' => setting('email.smtp_encryption')]);
        }

        // Locale
        if (! session('locale')) {
            $locale = user()->locale ?? setting('default.locale');

            app()->setLocale($locale);
        }

        // Set locale for Money package
        Money::setLocale(app()->getLocale());

        // Money
        config(['money.defaults.currency' => setting('default.currency')]);

        // Set app url dynamically if empty
        if (! config('app.url')) {
            config(['app.url' => url('/')]);
        }
    }

    protected static function loadCategoryTypes()
    {
        $category = new Category;

        $income_types  = $category->getIncomeCategoryTypes('string');
        $expense_types = $category->getExpenseCategoryTypes('string');
        $item_types    = $category->getItemCategoryTypes('string');
        $other_types    = $category->getOtherCategoryTypes('string');

        $search_string = config('search-string');

        foreach ($search_string as $model => &$model_config) {
            $route = $model_config['columns']['category_id']['route'] ?? null;

            // Only update category_id routes that point to categories.index
            if (!is_array($route) || ($route[0] ?? '') !== 'categories.index' || !isset($route[1])) {
                continue;
            }

            // Longest match first (income,expense must come before income)
            $replacements = [
                'type:' . Category::INCOME_TYPE . ',' . Category::EXPENSE_TYPE => 'type:' . $income_types . ',' . $expense_types,
                'type:' . Category::INCOME_TYPE  => 'type:' . $income_types,
                'type:' . Category::EXPENSE_TYPE => 'type:' . $expense_types,
                'type:' . Category::ITEM_TYPE    => 'type:' . $item_types,
                'type:' . Category::OTHER_TYPE    => 'type:' . $other_types,
            ];

            foreach ($replacements as $search => $replace) {
                if (Str::contains($route[1], $search)) {
                    $model_config['columns']['category_id']['route'][1] = Str::replace($search, $replace, $route[1]);
                    break;
                }
            }
        }

        config(['search-string' => $search_string]);
    }

    protected static function loadCurrencies()
    {
        $currencies = Currency::all();

        foreach ($currencies as $currency) {
            // If currency is not set in config, add it
            if (! config("money.currencies.{$currency->code}")) {
                config(['money.currencies.' . $currency->code => [
                    'code' => $currency->code,
                    'subunit' => 100,
                ]]);
            }

            config(['money.currencies.' . $currency->code . '.name' => $currency->name]);
            config(['money.currencies.' . $currency->code . '.rate' => $currency->rate]);
            config(['money.currencies.' . $currency->code . '.precision' => $currency->precision]);
            config(['money.currencies.' . $currency->code . '.symbol' => $currency->symbol]);
            config(['money.currencies.' . $currency->code . '.symbol_first' => $currency->symbol_first]);
            config(['money.currencies.' . $currency->code . '.decimal_mark' => $currency->decimal_mark]);
            config(['money.currencies.' . $currency->code . '.thousands_separator' => $currency->thousands_separator]);
        }

        // Set currencies with new settings
        \Akaunting\Money\Currency::setCurrencies(config('money.currencies'));
    }
}
