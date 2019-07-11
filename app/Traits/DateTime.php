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
        $chars = ['dash' => '-', 'slash' => '/', 'dot' => '.', 'comma' => ',', 'space' => ' '];

        $date_format = setting('general.date_format', 'd F Y');
        $date_separator = $chars[setting('general.date_separator', 'space')];

        return str_replace(' ', $date_separator, $date_format);
    }

    public function scopeMonthsOfYear($query, $field)
    {
        $year = request('year', Date::now()->year);

        $start = Date::parse($year . '-01-01')->startOfDay()->format('Y-m-d H:i:s');
        $end = Date::parse($year . '-12-31')->endOfDay()->format('Y-m-d  H:i:s');
        
        // check if financial year has been customized
        $financial_start = $this->getFinancialStart();

        if (Date::now()->startOfYear()->format('Y-m-d') !== $financial_start->format('Y-m-d')) {
            if (!is_null(request('year'))) {
                $financial_start->year = $year;
            }

            $start = $financial_start->format('Y-m-d');
            $end = $financial_start->addYear(1)->subDays(1)->format('Y-m-d');
        }

        return $query->whereBetween($field, [$start, $end]);
    }

    public function getTimezones()
    {
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
        $now = Date::now()->startOfYear();

        $setting = explode('-', setting('general.financial_start'));

        $day = !empty($setting[0]) ? $setting[0] : $now->day;
        $month = !empty($setting[1]) ? $setting[1] : $now->month;

        return Date::create(null, $month, $day);
    }
}
