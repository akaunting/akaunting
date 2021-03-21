<?php

namespace Akaunting\Language\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class Language extends Controller
{
    /**
     * Set locale if it's allowed.
     *
     * @param string                   $locale
     * @param \Illuminate\Http\Request $request
     **/
    private function setLocale($locale, $request)
    {
        // Check if is allowed and set default locale if not
        if (!language()->allowed($locale)) {
            $locale = config('app.locale');
        }

        if (Auth::check()) {
            Auth::user()->setAttribute('locale', $locale)->save();
        } else {
            $request->session()->put('locale', $locale);
        }
    }

    /**
     * Set locale and return home url.
     *
     * @param string                   $locale
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     **/
    public function home($locale, Request $request)
    {
        $this->setLocale($locale, $request);

        $url = config('language.url') ? url('/' . $locale) : url('/');

        return redirect($url);
    }

    /**
     * Set locale and return back.
     *
     * @param string                   $locale
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     **/
    public function back($locale, Request $request)
    {
        $this->setLocale($locale, $request);

        $url = config('language.back', 'session') === 'referer'
            ? $this->getUrlFromReferer($locale, $request)
            : $this->getUrlFromSession($locale, $request);

        return redirect(
            $url
            ? $url
            : (config('language.url') ? url('/' . $locale) : url('/'))
        );
    }

    private function getUrlFromSession($locale, Request $request)
    {
        $session = $request->session();

        if (config('language.url')) {
            $previous_url = substr(str_replace(env('APP_URL'), '', $session->previousUrl()), 7);

            if (strlen($previous_url) == 3) {
                $previous_url = substr($previous_url, 3);
            } else {
                $previous_url = substr($previous_url, strrpos($previous_url, '/') + 1);
            }

            $url = rtrim(env('APP_URL'), '/') . '/' . $locale . '/' . ltrim($previous_url, '/');

            $session->setPreviousUrl($url);
        }

        return $session->previousUrl();
    }

    private function getUrlFromReferer($locale, Request $request)
    {
        $url = $request->headers->get('referer');

        if (config('language.url')) {
            $url = substr(str_replace(env('APP_URL'), '', $url), 7);

            if (strlen($url) === 3) {
                $url = substr($url, 3);
            } else {
                $url = substr($url, strrpos($url, '/') + 1);
            }

            $url = rtrim(env('APP_URL'), '/') . '/' . $locale . '/' . ltrim($url, '/');
        }

        return $url;
    }
}
