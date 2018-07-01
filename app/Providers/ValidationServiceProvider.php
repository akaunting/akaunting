<?php

namespace App\Providers;

use App\Models\Setting\Currency;
use Illuminate\Support\ServiceProvider;
use Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $currency_code = null;

        Validator::extend('currency', function ($attribute, $value, $parameters, $validator) use(&$currency_code) {
                $status = false;

                $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

                if (array_key_exists($value, $currencies)) {
                    $status = true;
                }

                $currency_code = $value;

                return $status;
            },
            trans('validation.custom.invalid_currency', ['attribute' => $currency_code])
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
