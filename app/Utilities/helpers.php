<?php

use App\Models\Common\Company;
use App\Traits\DateTime;
use App\Utilities\Date;
use App\Utilities\Widgets;

if (!function_exists('user')) {
    /**
     * Get the authenticated user.
     *
     * @return \App\Models\Auth\User
     */
    function user()
    {
        // Get user from api/web
        if (request()->isApi()) {
            $user = app('Dingo\Api\Auth\Auth')->user();
        } else {
            $user = auth()->user();
        }

        return $user;
    }
}

if (!function_exists('user_id')) {
    /**
     * Get id of current user.
     *
     * @return int
     */
    function user_id()
    {
        return optional(user())->id;
    }
}

if (!function_exists('company_date_format')) {
    /**
     * Format the given date based on company settings.
     *
     * @return string
     */
    function company_date_format()
    {
        $date_time = new class() {
            use DateTime;
        };

        return $date_time->getCompanyDateFormat();
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
        return Date::parse($date)->format(company_date_format());
    }
}

if (!function_exists('show_widget')) {
    /**
     * Show a widget.
     *
     * @return string
     */
    function show_widget()
    {
        $arguments = func_get_args();

        $model = array_shift($arguments);

        return Widgets::show($model, ...$arguments);
    }
}

if (!function_exists('company')) {
    /**
     * Get current/any company model.
     *
     * @param int|null $id
     *
     * @return Company|null
     */
    function company($id = null)
    {
        $company = null;

        if (is_null($id)) {
            $company = Company::getCurrent();
        }

        if (is_numeric($id)) {
            $company = Company::find($id);
        }

        return $company;
    }
}

if (!function_exists('company_id')) {
    /**
     * Get id of current company.
     *
     * @return int
     */
    function company_id()
    {
        return optional(company())->id;
    }
}

if (!function_exists('should_queue')) {
    /**
     * Check if queue is enabled.
     *
     * @return bool
     */
    function should_queue() {
        return config('queue.default') != 'sync';
    }
}

if (!function_exists('cache_prefix')) {
    /**
     * Cache system added company_id prefix.
     *
     * @return string
     */
    function cache_prefix() {
        return company_id() . '_';
    }
}
