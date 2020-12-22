<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Factory as ViewFactory;

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

        ViewFactory::macro('hasStack', function (...$sections) {
            foreach ($sections as $section) {
                if (isset($this->pushes[$section]) || isset($this->prepends[$section])) {
                    return true;
                }
            }
            return false;
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
