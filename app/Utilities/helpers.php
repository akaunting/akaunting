<?php

use App\Models\Common\Company;
use App\Traits\DateTime;
use App\Traits\Sources;
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
        return auth()->user();
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
        return user()?->id;
    }
}

if (!function_exists('company_date_format')) {
    /**
     * Get the date format of company.
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
        return company()?->id;
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

if (!function_exists('source_name')) {
    /**
     * Get the current source.
     *
     * @param string|null $alias
     *
     * @return string
     */
    function source_name($alias = null)
    {
        $tmp = new class() {
            use Sources;
        };

        return $tmp->getSourceName(null, $alias);
    }
}

if (!function_exists('cache_prefix')) {
    /**
     * Cache system added company_id prefix.
     *
     * @return string
     */
    function cache_prefix()
    {
        return company_id() . '_';
    }
}

if (!function_exists('array_values_recursive')) {
    /**
     * Get array values recursively.
     */
    function array_values_recursive(array $array): array
    {
        $flat = [];

        foreach($array as $value) {
            if (is_array($value)) {
                $flat = array_merge($flat, array_values_recursive($value));
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }
}

if (!function_exists('running_in_queue')) {
    /**
     * Detect if application is running in queue.
     *
     * @return bool
     */
    function running_in_queue()
    {
        return defined('APP_RUNNING_IN_QUEUE') ?? false;
    }
}

if (!function_exists('simple_icons')) {
    /**
     * Get the simple icon content
     *
     * @return string
     */
    function simple_icons(string $name): string
    {
        $path = base_path('vendor/simple-icons/simple-icons/icons/' . $name . '.svg');

        return file_get_contents($path);
    }
}

if (!function_exists('default_currency')) {
    /**
     * Get the default currency code
     *
     * @return string
     */
    function default_currency(): string
    {
        return setting('default.currency');
    }
}
