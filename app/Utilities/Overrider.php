<?php

namespace App\Utilities;

use App\Models\Setting\Currency;

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
        config(['app.timezone' => setting('localisation.timezone', 'UTC')]);
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
        if (session('locale') == '') {
            $locale = (user()->locale) ?? setting('default.locale');

            app()->setLocale($locale);
        }

        // Set app url dynamically if empty
        if (!config('app.url')) {
            config(['app.url' => url('/')]);
        }
    }

    protected static function loadCurrencies()
    {
        $currencies = Currency::all();

        foreach ($currencies as $currency) {
            config(['money.' . $currency->code . '.name' => $currency->name]);
            config(['money.' . $currency->code . '.rate' => $currency->rate]);
            config(['money.' . $currency->code . '.precision' => $currency->precision]);
            config(['money.' . $currency->code . '.symbol' => $currency->symbol]);
            config(['money.' . $currency->code . '.symbol_first' => $currency->symbol_first]);
            config(['money.' . $currency->code . '.decimal_mark' => $currency->decimal_mark]);
            config(['money.' . $currency->code . '.thousands_separator' => $currency->thousands_separator]);
        }

        // Set currencies with new settings
        \Akaunting\Money\Currency::setCurrencies(config('money'));
    }
}
