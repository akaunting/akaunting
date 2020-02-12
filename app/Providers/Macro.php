<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class Macro extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Str::macro('filename', function ($string, $separator = '-') {
            // Replace @ with the word 'at'
            $string = str_replace('@', $separator.'at'.$separator, $string);

            // Remove all characters that are not the separator, letters, numbers, or whitespace.
            $string = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', $string);

            // Remove multiple whitespaces
            $string = preg_replace('/\s+/', ' ', $string);

            return $string;
        });
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
