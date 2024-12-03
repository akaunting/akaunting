<?php

namespace App\Providers;

use App\Models\Setting\Currency;
use App\Utilities\Modules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Support\Str;

class Validation extends Provider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Check currency code has 3 characters and is valid
        Validator::extend('currency', function ($attribute, $value, $parameters, $validator) {
            $status = false;

            if (!is_string($value)) {
                return $status;
            }

            $currencies = Currency::enabled()->pluck('code')->toArray();

            if (in_array($value, $currencies)) {
                $status = true;
            }

            if (strlen($value) != 3) {
                return $status;
            }

            $currency_code = $value;

            return $status;
        });

        // Custom message for currency validation
        Validator::replacer('currency', function($message, $attribute, $rule, $parameters) {
            return trans('validation.custom.invalid_currency', ['attribute' => $attribute]);
        });

        // Check currency code is valid
        Validator::extend('currency_code', function ($attribute, $value, $parameters, $validator) {
            $status = false;

            $currency_code = $value;

            $currencies = config('money.currencies');

            if (array_key_exists($value, $currencies)) {
                $status = true;
            }

            return $status;
        });

        // Custom message for currency code validation
        Validator::replacer('currency_code', function($message, $attribute, $rule, $parameters) {
            return trans('validation.custom.invalid_currency', ['attribute' => $attribute]);
        });

        // Check amount is valid
        Validator::extend('amount', function ($attribute, $value, $parameters, $validator) {
            $status = false;

            if ($value > 0 || in_array($value, $parameters)) {
                $status = true;
            }

            if (! preg_match("/^(?=.*?[0-9])[0-9.,]+$/", $value)) {
                $status = false;
            }

            return $status;
        });

        // Custom message for amount validation
        Validator::replacer('amount', function($message, $attribute, $rule, $parameters) {
            return trans('validation.custom.invalid_amount', ['attribute' => $attribute]);
        });

        // Check extension is valid
        Validator::extend('extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();

            return !empty($extension) && in_array($extension, $parameters);
        },
            trans('validation.custom.invalid_extension')
        );

        // Check colour is valid
        Validator::extend('colour', function ($attribute, $value, $parameters, $validator) {
            $status = false;

            $colors = ['gray', 'red', 'yellow', 'green', 'blue', 'indigo', 'purple', 'pink'];
            $variants = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900];

            foreach ($colors as $color) {
                if (! Str::contains($value, $color)) {
                    continue;
                }

                foreach ($variants as $variant) {
                    $name = $color . '-' . $variant;

                    if (Str::contains($value, $name)) {
                        $status = true;

                        break;
                    }
                }

                if ($status) {
                    break;
                }
            }

            if (! $status && Str::contains($value, '#')) {
                $status = true;
            }

            return $status;
        },
            trans('validation.custom.invalid_colour')
        );

        // Check payment method is valid
        Validator::extend('payment_method', function ($attribute, $value, $parameters, $validator) {
            $status = false;

            $methods = Modules::getPaymentMethods('all');

            if (array_key_exists($value, $methods)) {
                $status = true;
            }

            return $status;
        },
            trans('validation.custom.invalid_payment_method')
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
