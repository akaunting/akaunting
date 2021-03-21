<?php

namespace App\Traits;

use App\Traits\SearchString;
use Carbon\CarbonPeriod;
use Date;

trait DateTime
{
    use SearchString;

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
        if (!config('app.installed') && (config('app.env') !== 'testing')) {
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
        $year = $this->getSearchStringValue('year', $now->year);

        $financial_start = $this->getFinancialStart($year);

        // Check if FS has been customized
        if ($now->startOfYear()->format('Y-m-d') === $financial_start->format('Y-m-d')) {
            $start = Date::parse($year . '-01-01')->startOfDay()->format('Y-m-d H:i:s');
            $end = Date::parse($year . '-12-31')->endOfDay()->format('Y-m-d H:i:s');
        } else {
            $start = $financial_start->startOfDay()->format('Y-m-d H:i:s');
            $end = $financial_start->addYear(1)->subDays(1)->endOfDay()->format('Y-m-d H:i:s');
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
                    $groups[$group] = [];
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

    public function getFinancialStart($year = null)
    {
        $now = Date::now();
        $start = Date::now()->startOfYear();

        $setting = explode('-', setting('localisation.financial_start'));

        $day = !empty($setting[0]) ? $setting[0] : $start->day;
        $month = !empty($setting[1]) ? $setting[1] : $start->month;
        $year = $year ?? $this->getSearchStringValue('year', $now->year);

        $financial_start = Date::create($year, $month, $day);

        if ((setting('localisation.financial_denote') == 'ends') && ($financial_start->dayOfYear != 1)) {
            $financial_start->subYear();
        }

        return $financial_start;
    }

    public function getFinancialYear($year = null)
    {
        $start = $this->getFinancialStart($year);

        return CarbonPeriod::create($start, $start->copy()->addYear()->subDay()->endOfDay());
    }

    public function getFinancialQuarters($year = null)
    {
        $quarters = [];
        $start = $this->getFinancialStart($year);

        for ($i = 0; $i < 4; $i++) {
            $quarters[] = CarbonPeriod::create($start->copy()->addQuarters($i), $start->copy()->addQuarters($i + 1)->subDay()->endOfDay());
        }

        return $quarters;
    }

    public function getMonthlyDateFormat($year = null)
    {
        $format = 'M';

        if ($this->getFinancialStart($year)->month != 1) {
            $format = 'M Y';
        }

        return $format;
    }

    public function getQuarterlyDateFormat($year = null)
    {
        $format = 'M';

        if ($this->getFinancialStart($year)->month != 1) {
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
