<?php

use App\Models\Common\Company;
use App\Traits\DateTime;
use App\Traits\Sources;
use App\Traits\Modules;
use App\Utilities\Date;
use App\Utilities\Widgets;
use Illuminate\Support\Facades\Storage;

if (! function_exists('user')) {
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

if (! function_exists('user_id')) {
    /**
     * Get id of current user.
     */
    function user_id(): int|null
    {
        return user()?->id;
    }
}

if (! function_exists('company_date_format')) {
    /**
     * Get the date format of company.
     */
    function company_date_format(): string
    {
        $date_time = new class() {
            use DateTime;
        };

        return $date_time->getCompanyDateFormat();
    }
}

if (! function_exists('company_date')) {
    /**
     * Format the given date based on company settings.
     */
    function company_date($date): string
    {
        return Date::parse($date)->format(company_date_format());
    }
}

if (! function_exists('show_widget')) {
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

if (! function_exists('company')) {
    /**
     * Get current/any company model.
     */
    function company(int|null $id = null): Company|null
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

if (! function_exists('module_is_enabled')) {
    /**
     * Check if a module is enabled.
     */
    function module_is_enabled(string $alias): bool
    {
        $module = new class() {
            use Modules;
        };

        return $module->moduleIsEnabled($alias);
    }
}

if (! function_exists('company_id')) {
    /**
     * Get id of current company.
     */
    function company_id(): int|null
    {
        return company()?->id;
    }
}

if (! function_exists('should_queue')) {
    /**
     * Check if queue is enabled.
     */
    function should_queue(): bool
    {
        return config('queue.default') != 'sync';
    }
}

if (! function_exists('source_name')) {
    /**
     * Get the current source.
     */
    function source_name(string|null $alias = null): string
    {
        $tmp = new class() {
            use Sources;
        };

        return $tmp->getSourceName(null, $alias);
    }
}

if (! function_exists('cache_prefix')) {
    /**
     * Cache system added company_id prefix.
     */
    function cache_prefix(): string
    {
        return company_id() . '_';
    }
}

if (! function_exists('array_values_recursive')) {
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

if (! function_exists('running_in_queue')) {
    /**
     * Detect if application is running in queue.
     */
    function running_in_queue(): bool
    {
        return defined('APP_RUNNING_IN_QUEUE') ?? false;
    }
}

if (! function_exists('simple_icons')) {
    /**
     * Get the simple icon content
     */
    function simple_icons(string $name): string
    {
        $path = base_path('vendor/simple-icons/simple-icons/icons/' . $name . '.svg');

        return file_get_contents($path);
    }
}

if (! function_exists('default_currency')) {
    /**
     * Get the default currency code
     */
    function default_currency(): string
    {
        return setting('default.currency');
    }
}

if (! function_exists('env_is_production')) {
    /**
     * Determine if the application is in the production environment
     */
    function env_is_production(): bool
    {
        return config('app.env') === 'production';
    }
}

if (! function_exists('env_is_development')) {
    /**
     * Determine if the application is in the development environment
     */
    function env_is_development(): bool
    {
        return config('app.env') === 'development';
    }
}

if (! function_exists('env_is_build')) {
    /**
     * Determine if the application is in the build environment
     */
    function env_is_build(): bool
    {
        return config('app.env') === 'build';
    }
}

if (! function_exists('env_is_local')) {
    /**
     * Determine if the application is in the local environment
     */
    function env_is_local(): bool
    {
        return config('app.env') === 'local';
    }
}

if (! function_exists('env_is_testing')) {
    /**
     * Determine if the application is in the testing environment
     */
    function env_is_testing(): bool
    {
        return config('app.env') === 'testing';
    }
}

if (! function_exists('is_local_storage')) {
    /**
     * Determine if the storage is local.
     */
    function is_local_storage(): bool
    {
        $driver = config('filesystems.disks.' . config('filesystems.default') . '.driver');

        return $driver == 'local';
    }
}

if (! function_exists('is_cloud_storage')) {
    /**
     * Determine if the storage is cloud.
     */
    function is_cloud_storage(): bool
    {
        return ! is_local_storage();
    }
}

if (! function_exists('get_storage_path')) {
    /**
     * Get the path from the storage.
     */
    function get_storage_path(string $path = ''): string
    {
        return is_local_storage()
                ? storage_path($path)
                : Storage::path($path);
    }
}
