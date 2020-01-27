<?php

namespace App\Traits;

use Date;

trait DateTime
{
    /*
     * Get the date format based on company settings.
     * getDateFormat method is used by Eloquent
     *
     * @return string
     */
    public function getCompanyDateFormat()
    {
        $default = 'd M Y';

        // Make sure it's installed
        if (!env('APP_INSTALLED') && (env('APP_ENV') !== 'testing')) {
            return $default;
        }

        // Make user is logged in
        if (!user()) {
            return $default;
        }

        $chars = ['dash' => '-', 'slash' => '/', 'dot' => '.', 'comma' => ',', 'space' => ' '];

        $date_format = setting('localisation.date_format', $default);
        $date_separator = $chars[setting('localisation.date_separator', 'space')];

        return str_replace(' ', $date_separator, $date_format);
    }

    public function scopeMonthsOfYear($query, $field)
    {
        $now = Date::now();
        $year = request('year', $now->year);

        $financial_start = $this->getFinancialStart();

        // Check if FS has been customized
        if ($now->startOfYear()->format('Y-m-d') === $financial_start->format('Y-m-d')) {
            $start = Date::parse($year . '-01-01')->startOfDay()->format('Y-m-d H:i:s');
            $end = Date::parse($year . '-12-31')->endOfDay()->format('Y-m-d H:i:s');
        } else {
            if (!is_null(request('year'))) {
                $financial_start->year = $year;
            }

            $start = $financial_start->format('Y-m-d H:i:s');
            $end = $financial_start->addYear(1)->subDays(1)->format('Y-m-d H:i:s');
        }

        return $query->whereBetween($field, [$start, $end]);
    }

    public function getTimezones()
    {
        $groups = [];

        // The list of available timezone groups to use.
        $use_zones = array('Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific');

        // Get the list of time zones from the server.
        $zones = \DateTimeZone::listIdentifiers();

        // Build the group lists.
        foreach ($zones as $zone) {
            // Time zones not in a group we will ignore.
            if (strpos($zone, '/') === false) {
                continue;
            }

            // Get the group/locale from the timezone.
            list ($group, $locale) = explode('/', $zone, 2);

            // Only use known groups.
            if (in_array($group, $use_zones)) {
                // Initialize the group if necessary.
                if (!isset($groups[$group])) {
                    $groups[$group] = array();
                }

                // Only add options where a locale exists.
                if (!empty($locale)) {
                    $groups[$group][$zone] = str_replace('_', ' ', $locale);
                }
            }
        }

        // Sort the group lists.
        ksort($groups);

        return $groups;
    }

    public function getFinancialStart()
    {
        $now = Date::now();
        $start = Date::now()->startOfYear();

        $setting = explode('-', setting('localisation.financial_start'));

        $day = !empty($setting[0]) ? $setting[0] : $start->day;
        $month = !empty($setting[1]) ? $setting[1] : $start->month;
        $year = request('year', $now->year);

        $financial_start = Date::create($year, $month, $day);

        // Check if FS is in last calendar year
        if ($now->diffInDays($financial_start, false) > 0) {
            $financial_start->subYear();
        }

        return $financial_start;
    }

    public function getMonthlyDateFormat()
    {
        $format = 'M';

        if ($this->getFinancialStart()->month != 1) {
            $format = 'M Y';
        }

        return $format;
    }

    public function getQuarterlyDateFormat()
    {
        $format = 'M';

        if ($this->getFinancialStart()->month != 1) {
            $format = 'M Y';
        }

        return $format;
    }

    public function getYearlyDateFormat()
    {
        $format = 'Y';

        return $format;
    }
}
