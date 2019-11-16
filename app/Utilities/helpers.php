<?php

use App\Traits\DateTime;
use Jenssegers\Date\Date;

if (!function_exists('user')) {
    /**
     * Get the authenticated user.
     *
     * @return \App\Models\Auth\User
     */
    function user()
    {
        // Get user from api/web
        if (request()->is('api/*')) {
            $user = app('Dingo\Api\Auth\Auth')->user();
        } else {
            $user = auth()->user();
        }

        return $user;
    }
}

if (!function_exists('company_date')) {
    /**
     * Format the given date based on company settings.
     *
     * @return string
     */
    function company_date($date)
    {
        $date_time = new class() {
            use DateTime;
        };

        return Date::parse($date)->format($date_time->getCompanyDateFormat());
    }
}
