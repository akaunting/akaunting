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
        $year = request('year');

        // Get current year if not set
        if (empty($year)) {
            $year = Date::now()->year;
        }

        $start = Date::parse($year . '-01-01')->format('Y-m-d');
        $end = Date::parse($year . '-12-31')->format('Y-m-d');

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
}