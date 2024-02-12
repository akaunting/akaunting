<?php

namespace App\Providers;

use App\Models\Setting\Currency;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Support\Str;
use Validator;

class Validation extends Provider
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
        },
            trans('validation.custom.invalid_currency', ['attribute' => $currency_code])
        );

        $amount = null;

        Validator::extend('amount', function ($attribute, $value, $parameters, $validator) use (&$amount) {
            $status = false;

            if ($value > 0 || in_array($value, $parameters)) {
                $status = true;
            }

            if (! preg_match("/^(?=.*?[0-9])[0-9.,]+$/", $value)) {
                $status = false;
            }

            $amount = $value;

            return $status;
        },
            trans('validation.custom.invalid_amount', ['attribute' => $amount])
        );

        Validator::extend('extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();

            return !empty($extension) && in_array($extension, $parameters);
        },
            trans('validation.custom.invalid_extension')
        );

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
