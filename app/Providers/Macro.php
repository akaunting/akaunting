<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Factory as ViewFactory;

class Macro extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Request::macro('isApi', function () {
            return $this->is(config('api.subtype') . '/*');
        });

        Request::macro('isNotApi', function () {
            return !$this->isApi();
        });

        Request::macro('isAuth', function () {
            return $this->is('auth/*');
        });

        Request::macro('isNotAuth', function () {
            return !$this->isAuth();
        });

        Request::macro('isInstall', function () {
            return $this->is('install/*');
        });

        Request::macro('isNotInstall', function () {
            return !$this->isInstall();
        });

        Request::macro('isSigned', function ($company_id) {
            return $this->is($company_id . '/signed/*');
        });

        Request::macro('isNotSigned', function ($company_id) {
            return !$this->isSigned($company_id);
        });

        Request::macro('isPortal', function ($company_id) {
            return $this->is($company_id . '/portal') || $this->is($company_id . '/portal/*');
        });

        Request::macro('isNotPortal', function ($company_id) {
            return !$this->isPortal($company_id);
        });

        Request::macro('isWizard', function ($company_id) {
            return $this->is($company_id . '/wizard') || $this->is($company_id . '/wizard/*');
        });

        Request::macro('isNotWizard', function ($company_id) {
            return !$this->isWizard($company_id);
        });

        Request::macro('isAdmin', function ($company_id) {
            return $this->isNotApi()
                    && $this->isNotAuth()
                    && $this->isNotInstall()
                    && $this->isNotSigned($company_id)
                    && $this->isNotPortal($company_id)
                    && $this->isNotWizard($company_id);
        });

        Request::macro('isNotAdmin', function ($company_id) {
            return !$this->isAdmin($company_id);
        });

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
}
