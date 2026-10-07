<?php

namespace App\Traits;

use App\Utilities\Date;
use Carbon\CarbonPeriod;

trait DateTime
{
    public function getCompanyDateFormat(): string
    {
        $default = 'd M Y';

        // Make sure it's installed
        if (! config('app.installed') && ! env_is_testing()) {
            return $default;
        }

        // Make user is logged in
        if (!user()) {
            return $default;
        }

        $chars = ['dash' => '-', 'slash' => '/', 'dot' => '.', 'comma' => ',', 'space' => ' '];

        $date_format = setting('localisation.date_format', $default) ?? $default;

        $char = setting('localisation.date_separator', 'space') ?? 'space';
        $date_separator = $chars[$char];

        return str_replace(' ', $date_separator, $date_format);
    }

    public function scopeDateFilter($query, string $field)
    {
        [$start, $end] = $this->getStartAndEndDates();

        return $query->whereBetween($field, [$start->toDateTimeString(), $end->toDateTimeString()]);
    }

    public function getStartAndEndDates($year = null): array
    {
        if (request()->filled('start_date') && request()->filled('end_date')) {
            $start = Date::parse(request('start_date'))->startOfDay();
            $end = Date::parse(request('end_date'))->endOfDay();
        } else {
            $financial_year = $this->getFinancialYear($year);

            $start = $financial_year->copy()->getStartDate()->startOfDay();
            $end = $financial_year->copy()->getEndDate()->endOfDay();
        }

        return [$start, $end];
    }

    /**
     * The start of the financial year named after a year: the calendar year it starts in, or, when the financial
     * year is denoted by its end, the one it ends in. With no year, the financial year that holds the date or today.
     */
    public function getFinancialStart($year = null, $date = null): Date
    {
        if (is_null($year)) {
            return $this->getFinancialStartOf(is_null($date) ? Date::now() : Date::parse($date));
        }

        $start_of_year = Date::now()->startOfYear();

        $setting = explode('-', setting('localisation.financial_start'));

        $day = ! empty($setting[0]) ? $setting[0] : $start_of_year->day;
        $month = ! empty($setting[1]) ? $setting[1] : $start_of_year->month;
        $year = $year ?? $start_of_year->year;

        $financial_start = Date::create($year, $month, $day);
        if ((setting('localisation.financial_denote') == 'ends') && ($financial_start->dayOfYear != 1) ||
            $financial_start->greaterThan($date ?? Date::now())
        ) {
            $financial_start->subYear();
        }

        return $financial_start;
    }

    /**
     * The start of the financial year that holds a date. The calendar year of the date does not name that year
     * when it is denoted by its end and starts after 1 January: on or past this calendar year's start, the date
     * falls in the year named after the next calendar year, so the year after the named one is taken.
     */
    public function getFinancialStartOf(Date $date): Date
    {
        $start = $this->getFinancialStart($date->year, $date);

        if ($start->copy()->addYear()->lessThanOrEqualTo($date)) {
            $start->addYear();
        }

        return $start;
    }

    /**
     * The year the financial year that holds a date is named after, which getFinancialStart() and
     * getFinancialYear() take: the calendar year it starts in, or the one it ends in when it is denoted by its end.
     */
    public function getFinancialYearName(Date $date): int
    {
        $start = $this->getFinancialStartOf($date);

        return (setting('localisation.financial_denote') == 'ends') ? $start->copy()->addYear()->subDay()->year : $start->year;
    }

    public function getFinancialWeek($year = null, $date = null): CarbonPeriod
    {
        $today = Date::today();
        $financial_weeks = $this->getFinancialPeriodsOfYear('Weeks', 53, $year, $date);

        foreach ($financial_weeks as $week) {
            if ($today->lessThan($week->getStartDate()) || $today->greaterThan($week->getEndDate())) {
                continue;
            }

            $this_week = $week;

            break;
        }

        if (! isset($this_week)) {
            $this_week = $financial_weeks[0];
        }

        return $this_week;
    }

    public function getFinancialMonth($year = null, $date = null): CarbonPeriod
    {
        $today = Date::today();
        $financial_months = $this->getFinancialPeriodsOfYear('Months', 12, $year, $date);

        foreach ($financial_months as $month) {
            if ($today->lessThan($month->getStartDate()) || $today->greaterThan($month->getEndDate())) {
                continue;
            }

            $this_month = $month;

            break;
        }

        if (! isset($this_month)) {
            $this_month = $financial_months[0];
        }

        return $this_month;
    }

    public function getFinancialQuarter($year = null, $date = null): CarbonPeriod
    {
        $today = Date::today();
        $financial_quarters = $this->getFinancialPeriodsOfYear('Quarters', 4, $year, $date);

        foreach ($financial_quarters as $quarter) {
            if ($today->lessThan($quarter->getStartDate()) || $today->greaterThan($quarter->getEndDate())) {
                continue;
            }

            $this_quarter = $quarter;

            break;
        }

        if (! isset($this_quarter)) {
            $this_quarter = $financial_quarters[0];
        }

        return $this_quarter;
    }

    /**
     * The weeks, months or quarters of a whole financial year, to find the one today falls in.
     *
     * Not getFinancialWeeks() and the like: they stop at the request's end date for the report columns, so a
     * past window would leave today out and name its first period This Quarter, This Month or This Week.
     */
    protected function getFinancialPeriodsOfYear(
        string $unit,
        int $count,
        $year = null,
        $date = null,
    ): array {
        $start = $this->getFinancialStart($year, $date);

        $periods = [];

        $function = 'add' . $unit;

        for ($i = 0; $i < $count; $i++) {
            $periods[] = CarbonPeriod::create(
                $start->copy()->{$function}($i),
                $start->copy()->{$function}($i + 1)->subDay()->endOfDay(),
            );
        }

        return $periods;
    }

    public function getFinancialYear($year = null, $date = null): CarbonPeriod
    {
        $financial_start = $this->getFinancialStart($year, $date);

        return CarbonPeriod::create($financial_start, $financial_start->copy()->addYear()->subDay()->endOfDay());
    }

    public function getFinancialWeeks($year = null, $date = null): array
    {
        $weeks = [];

        $start = $this->getFinancialStart($year, $date);

        $w = 52;

        if (request()->filled('start_date') && request()->filled('end_date')) {
            $w = Date::parse($start->copy())->diffInWeeks(Date::parse(request('end_date'))) + 1;
        }

        for ($i = 0; $i < $w; $i++) {
            $weeks[] = CarbonPeriod::create($start->copy()->addWeeks($i), $start->copy()->addWeeks($i + 1)->subDay()->endOfDay());
        }

        return $weeks;
    }

    public function getFinancialMonths($year = null, $date = null): array
    {
        $months = [];

        $start = $this->getFinancialStart($year, $date);

        $m = 12;

        if (request()->filled('start_date') && request()->filled('end_date')) {
            $m = Date::parse($start->copy())->diffInMonths(Date::parse(request('end_date'))) + 1;
        }

        for ($i = 0; $i < $m; $i++) {
            $months[] = CarbonPeriod::create($start->copy()->addMonths($i), $start->copy()->addMonths($i + 1)->subDay()->endOfDay());
        }

        return $months;
    }

    public function getFinancialQuarters($year = null, $date = null): array
    {
        $quarters = [];

        $start = $this->getFinancialStart($year, $date);

        $q = 4;

        /*
            Previously, diffInQuarters was calculated from start_date, which caused errors
            when the financial start value differed from the default value.
            Therefore, this change has been made. Ticket: #8106
         */
        if (request()->filled('start_date') && request()->filled('end_date')) {
            $q = Date::parse($start->copy())->diffInQuarters(Date::parse(request('end_date'))) + 1;
        }

        for ($i = 0; $i < $q; $i++) {
            $quarters[] = CarbonPeriod::create($start->copy()->addQuarters($i), $start->copy()->addQuarters($i + 1)->subDay()->endOfDay());
        }

        return $quarters;
    }

    public function getDatePickerShortcuts(): array
    {
        $today = Date::today();
        $financial_week = $this->getFinancialWeek();
        $financial_month = $this->getFinancialMonth();
        $financial_quarter = $this->getFinancialQuarter();
        $financial_year = $this->getFinancialYear();

        $date_picker_shortcuts = [
            trans('general.date_range.this_week') => [
                'start' => $financial_week->copy()->getStartDate()->toDateString(),
                'end' => $financial_week->copy()->getEndDate()->toDateString(),
            ],
            trans('general.date_range.this_month') => [
                'start' => $financial_month->copy()->getStartDate()->toDateString(),
                'end' => $financial_month->copy()->getEndDate()->toDateString(),
            ],
            trans('general.date_range.this_quarter') => [
                'start' => $financial_quarter->copy()->getStartDate()->toDateString(),
                'end' => $financial_quarter->copy()->getEndDate()->toDateString(),
            ],
            trans('general.date_range.this_year') => [
                'start' => $financial_year->copy()->getStartDate()->toDateString(),
                'end' => $financial_year->copy()->getEndDate()->toDateString(),
            ],
            trans('general.date_range.year_to_date') => [
                'start' => $financial_year->copy()->getStartDate()->toDateString(),
                'end' => $today->copy()->toDateString(),
            ],
            trans('general.date_range.previous_week') => [
                'start' => $financial_week->copy()->getStartDate()->subWeek()->toDateString(),
                'end' => $financial_week->copy()->getStartDate()->subDay()->toDateString(),
            ],
            trans('general.date_range.previous_month') => [
                'start' => $financial_month->copy()->getStartDate()->subMonth()->toDateString(),
                'end' => $financial_month->copy()->getStartDate()->subDay()->toDateString(),
            ],
            trans('general.date_range.previous_quarter') => [
                'start' => $financial_quarter->copy()->getStartDate()->subQuarter()->toDateString(),
                'end' => $financial_quarter->copy()->getStartDate()->subDay()->toDateString(),
            ],
            trans('general.date_range.previous_year') => [
                'start' => $financial_year->copy()->getStartDate()->subYear()->toDateString(),
                'end' => $financial_year->copy()->getStartDate()->subDay()->toDateString(),
            ],
        ];

        return $date_picker_shortcuts;
    }

    public function getDailyDateFormat(): string
    {
        return 'd M Y';
    }

    public function getWeeklyDateFormat(): string
    {
        return 'W - M Y';
    }

    public function getMonthlyDateFormat(): string
    {
        return 'M Y';
    }

    public function getQuarterlyDateFormat(): string
    {
        return 'M Y';
    }

    public function getYearlyDateFormat(): string
    {
        return 'Y';
    }

    public function getPeriodicDate(Date $date, string $period, string $year): string
    {
        $formatted_date = '';

        switch ($period) {
            case 'yearly':
                $financial_year = $this->getFinancialYear($year, $date);

                if ($date->greaterThanOrEqualTo($financial_year->getStartDate()) && $date->lessThanOrEqualTo($financial_year->getEndDate())) {
                    if (setting('localisation.financial_denote') == 'begins') {
                        $formatted_date = $financial_year->copy()->getStartDate()->format($this->getYearlyDateFormat());
                    } else {
                        $formatted_date = $financial_year->copy()->getEndDate()->format($this->getYearlyDateFormat());
                    }
                }

                break;
            case 'quarterly':
                $quarters = $this->getFinancialQuarters($year, $date);

                foreach ($quarters as $quarter) {
                    if ($date->lessThan($quarter->getStartDate()) || $date->greaterThan($quarter->getEndDate())) {
                        continue;
                    }

                    $start = $quarter->copy()->getStartDate()->format($this->getQuarterlyDateFormat($year));
                    $end = $quarter->copy()->getEndDate()->format($this->getQuarterlyDateFormat($year));

                    $formatted_date = $start . ' - ' . $end;

                    break;
                }

                break;
            case 'weekly':
                $weeks = $this->getFinancialWeeks($year, $date);

                foreach ($weeks as $week) {
                    if ($date->lessThan($week->getStartDate()) || $date->greaterThan($week->getEndDate())) {
                        continue;
                    }

                    $start = $week->copy()->getStartDate()->format($this->getDailyDateFormat($year));
                    $end = $week->copy()->getEndDate()->format($this->getDailyDateFormat($year));

                    $formatted_date = $start . ' - ' . $end;

                    break;
                }

                break;
            default:
                $formatted_date = $date->copy()->format($this->getMonthlyDateFormat($year));

                break;
        }

        return $formatted_date;
    }

    public function getTimezones(): array
    {
        // The list of available timezone groups to use.
        $use_zones = ['Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific'];

        // Get the list of time zones from the server.
        $zones = \DateTimeZone::listIdentifiers();

        return collect($zones)
                ->mapWithKeys(function ($timezone) use ($use_zones) {
                    if (! in_array(str($timezone)->before('/'), $use_zones)) {
                        return [];
                    }

                    return [$timezone => str($timezone)->after('/')->value()];
                })
                ->groupBy(function ($item, $key) {
                    return str($key)->before('/')->value();
                }, preserveKeys: true)
                ->toArray();
    }

    // @deprecated 3.1
    public function scopeMonthsOfYear($query, string $field)
    {
        return $this->scopeDateFilter($query, $field);
    }
}
