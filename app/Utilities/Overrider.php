<?php

namespace App\Utilities;

use App\Models\Setting\Currency;

class Overrider
{
    public static $company_id;

    public static function load($type)
    {
        // Overrides apply per company
        $company_id = session('company_id');
        if (empty($company_id)) {
            return;
        }

        static::$company_id = $company_id;

        $method = 'load' . ucfirst($type);

        static::$method();
    }

    protected static function loadSettings()
    {
        // Set the active company settings
        setting()->setExtraColumns(['company_id' => static::$company_id]);
        setting()->load(true);

        // Timezone
        config(['app.timezone' => setting('general.timezone', 'UTC')]);
        date_default_timezone_set(config('app.timezone'));

        // Email
        $email_protocol = setting('general.email_protocol', 'mail');
        config(['mail.driver' => $email_protocol]);
        config(['mail.from.name' => setting('general.company_name')]);
        config(['mail.from.address' => setting('general.company_email')]);

        if ($email_protocol == 'sendmail') {
            config(['mail.sendmail' => setting('general.email_sendmail_path')]);
        } elseif ($email_protocol == 'smtp') {
            config(['mail.host' => setting('general.email_smtp_host')]);
            config(['mail.port' => setting('general.email_smtp_port')]);
            config(['mail.username' => setting('general.email_smtp_username')]);
            config(['mail.password' => setting('general.email_smtp_password')]);
            config(['mail.encryption' => setting('general.email_smtp_encryption')]);
        }

        // Session
        config(['session.lifetime' => setting('general.session_lifetime', '30')]);

        // Locale
        if (session('locale') == '') {
            //App::setLocale(setting('general.default_language'));
            //Session::put('locale', setting('general.default_language'));
            config(['app.locale' => setting('general.default_locale')]);
        }

        // Set app url dynamically
        config(['app.url' => url('/')]);
    }

    protected static function loadCurrencies()
    {
        $currencies = Currency::all();

        foreach ($currencies as $currency) {
            if (!isset($currency->precision)) {
                continue;
            }

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